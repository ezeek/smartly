<?php

require_once __DIR__ . '/vendor/autoload.php';

// include all tile state names for use when building smartly data

include 'assets/data/statelookup.php'; // contains array of each template type and their associated states
include 'assets/data/smartly_mods.php';

$repo_base = array(
  'css_sandbox' => '/var/www/html/smartly-base/smartly.min.css',
  'json_sandbox' => '/var/www/html/smartly-base/smartly.json',
  'head_sandbox' => '/var/www/html/smartly-base/head.json',
  'css_dev' => '/var/www/html/smartly-base/smartly.css',
  'json_dev' => '/var/www/html/smartly-base/smartly.json',
  'head_dev' => '/var/www/html/smartly-base/head.json',
//  'css_dev' => 'https://raw.githubusercontent.com/ezeek/smartly-base/devel/smartly.css',
//  'json_dev' => 'https://raw.githubusercontent.com/ezeek/smartly-base/devel/smartly.json',
//  'head_dev' => 'https://api.github.com/repos/ezeek/smartly-base/commits/HEAD',
  'css' => 'https://raw.githubusercontent.com/ezeek/smartly-base/master/smartly.css',
  'json' => 'https://raw.githubusercontent.com/ezeek/smartly-base/master/smartly.json',
  'head' => 'https://api.github.com/repos/ezeek/smartly-base/commits/HEAD'
);

// prep for allowing custom github user repos

if ($_POST['github_user']) {
  $repo_skin_user = preg_replace("/[^ \w]+/", "", $_POST['github_user']); //
} else {
  $repo_skin_user = 'ezeek';
}

// retrieve selected skin css and json

if ($_POST['skin'] && $_POST['skin'] != 'smartly') {
  $repo_skin = array(
    'css' => 'https://raw.githubusercontent.com/' . $repo_skin_user . '/smartly-skins/master/' . $_POST['skin'] . '/' . $_POST['skin'] . '.css',
    'json' => 'https://raw.githubusercontent.com/' . $repo_skin_user . '/smartly-skins/master/' . $_POST['skin'] . '/' . $_POST['skin'] . '.json',
    'head' => 'https://api.github.com/repos/' . $repo_skin_user . "/smartly-skins/commits/HEAD",
  );
} else {
  $repo_skin = null;
}

// initialize required smartly variables globally


// required by github to retrieve repo HEAD
$fgc_opts = ['http' => ['method' => 'GET','header' => ['User-Agent: PHP']]];
$fgc_context = stream_context_create($fgc_opts);

$smartly_head = get_current_git_commit();
$device_cals_path = '/var/www/html/smartly/assets/data/device_cals.json';
$update_options = array();
$tiles = array();
$smartly_data = array();
$smartly_touched = false;
$base_css = "";
$user_css = "";


// some tile template types have no configurable options, let's define them up here
$template_noconfig = array(
    "date",
    "clock",
    "date-time",
//    "mode",
//    "attribute",
    "dashboard-link",
    "analog-clock",
    "text"
);


// TODO: incorporate all tile template 'options' within statefile lookup.
 
$template_nonudge = array(
/*
  'attribute',
  'temperature',
  'humidity',
  'energy',
  'illuminance'
*/
);



/*
$smartly_css = ["mods" => [
  "title" => array(), // id and replacement title
  "label" => array(), // id and label to add
  "icon" => array() // each state, icon content code and icon font
]];
*/



// define deliiters used when parsing and building smartly css

$smartly_css_delimiters = array(
  "base" => "/* ------- DO NOT EDIT - Smartly Base CSS ------- */",
  "skin" => "/* ------- DO NOT EDIT - Custom Skin CSS ------- */",
  "auto" => "/* ------- DO NOT EDIT - Smartly Generated CSS below ------- */",
  "user" => "/* ------- CUSTOM CSS BELOW THIS LINE - This CSS will be preserved during updates ------- */"
);


/*
 *
 * TODO: continue breaking this horrendous block of code into more functions
 *
*/

// retrieve and decode posted inputjson

if (is_json($_POST['inputjson'])) {
  $inputJSON = json_decode($_POST['inputjson'], true);
} else {
  echo "JSON Error";
  exit(0);
}

//var_dump($inputJSON);
 

// EXTRACT SMARTLYDATA
// retrieve and decode posted smartlydata, and if none posted, attempt to extract from smartly tile

if (is_json($_POST['smartlydata'])) {
  $smartly_data = json_decode($_POST['smartlydata'], true);
  if (!($smartly_data['tiles'])) { // if smartly_data is of pre-global-settings era, update it.
    $smartly_data = array('tiles' => $smartly_data, 'settings' => null);
    $smartly_touched = true;
  }

// if nothing sent via form, try to extract from smartly data tile

} elseif ($inputJSON['tiles'][0]['template'] == "smartly") {
  if (is_json($inputJSON['tiles'][0]['templateExtra'])) {
    $smartly_data = json_decode($inputJSON['tiles'][0]['templateExtra'], true);
    if (!($smartly_data['tiles'])) { // if smartly_data is of pre-global-settings era, update it.
      $smartly_data = array('tiles' => $smartly_data, 'settings' => null);
    }
  }
} else {
  $smartly_data = null;
}


// PARSE UPDATE OPTIONS
// parse selected update options

foreach ($_POST['options'] as $options) {
  $update_options[$options] = true;
}

// retrieve smartly base and skin head

$repo_base_head_json = json_decode(file_get_contents($repo_base['head'], false, $fgc_context), true);
$repo_base_head = "/* " . $repo_base_head_json['commit']['url'] . " */\r\n\r\n";


if ($repo_skin) {
  $repo_skin_head_json = json_decode(file_get_contents($repo_skin['head'], false, $fgc_context), true);
  $repo_skin_head = "/* " . $_POST['skin'] . ": " . $repo_skin_head_json['commit']['url'] . " */\r\n\r\n";
//var_dump($repo_skin);
//var_dump(file_get_contents("https://api.github.com/repos/ezeek/smartly-skins/commits/HEAD"));
} else {
  $repo_skin_head = null;
}


// define smartly base and skin css based on user input

$repo_base_css = $repo_base_head . file_get_contents($repo_base['css_sandbox']); //css_dev

if ($repo_skin) {
  $repo_skin_css = $repo_skin_head . file_get_contents($repo_skin['css']);
} else {
  $repo_skin_css = null;
}



// get updated JSON from repo
$repo_base_json = file_get_contents($repo_base['json_sandbox']); //json_dev
$repo_base_json = json_decode($repo_base_json, true);

// update with most recent smartly-inject bootstrap
$inputJSON['customJS'] = $repo_base_json['customJS'];
$inputJSON['customHTML'] = $repo_base_json['customHTML'];

// retrieve smartly customColors[] and other settings if instructed
if ($update_options['color'] || $update_options['settings']) {
  if ($update_options['color']) {
    $inputJSON['customColors'] = $repo_base_json['customColors'];
  }

  if ($repo_skin) {
    $repo_skin_json = file_get_contents($repo_skin['json']);
    $repo_skin_json = json_decode($repo_skin_json, true);
    // overlay skin json on top of base json
    if ($repo_skin_json['customColors']) {
      foreach ($repo_skin_json['customColors'] as $template) {
         $matched = multi_array_search($inputJSON['customColors'], array('template' => $template['template'], 'state' => $template['state']));
         if (count($matched) > 0) { 
           $inputJSON['customColors'][$matched[0]] = $template;
         } else {
           $inputJSON['customColors'][] = $template;
         }
      }
    }
    $repo_base_json = array_replace_recursive($repo_base_json, $repo_skin_json);

  }

// TODO: make available JSON settings checkbox granular, allowing for user to keep their background image, tile sizing, gap size, etc.
// update customColors[] (color templates) from repo if instructed.

  if ($update_options['settings']) {
    $inputJSON['roundedCorners'] = $repo_base_json['roundedCorners'];
    $inputJSON['hideLabels'] =  $repo_base_json['hideLabels'];
    $inputJSON['colWidth'] = $repo_base_json['colWidth'];
    $inputJSON['hide3dot'] = $repo_base_json['hide3dot'];
    $inputJSON['gridGap'] = $repo_base_json['gridGap'];
    $inputJSON['bgColor'] = $repo_base_json['bgColor'];
    $inputJSON['iconSize'] = $repo_base_json['iconSize'];
    $inputJSON['rowHeight'] = $repo_base_json['rowHeight'];
    $inputJSON['hideIconText'] = $repo_base_json['hideIconText'];
    $inputJSON['background'] = $repo_base_json['background'];
    $inputJSON['fontSize'] = $repo_base_json['fontSize'];
  }
} //END if ($update_options['color'] || $update_options['settings']) {


// set up global variables for automatically determining and setting grid row and column count

$calibrate_rows = 0;
$calibrate_cols = 0;


/*
 * TODO: split into first_run() function
 * 
 * if first time running, smartly tile won't exist so create it
 * with null data to ensure tile array position is mirrored
 * between smartly tiles and inputJSON tiles.
*/

//var_dump($inputJSON['tiles']);

if ($inputJSON['tiles'][0]['template'] != "smartly") {  // first time running

  $workingTiles = $inputJSON['tiles'];
  foreach ($inputJSON['tiles'] as $tile_id => $tile_data) {
    if ($tile_data['colSpan'] > 1) {
      for ($column = $tile_data['col']; $column <= $tile_data['col'] + $tile_data['colSpan'] - 1; $column++) {
        $workingTiles[] = array(
          'template' => 'fake',
          'col' => $column,
          'row' => $tile_data['row'],
          'rowSpan' => $tile_data['rowSpan']
        ); // array
      } // for
    } // if
  } // foreach

//print count($inputJSON['tiles']) . " - orig\n\r";
//print count($workingTiles) . " - after\n\r";

  $grid = array();
  $grid['cols']  = array_column($workingTiles, 'col');
  $grid['rows'] = array_column($workingTiles, 'row');

// sort the tiles with cols ascending, rows ascending
// add $workingTiles as the last parameter, to sort by the common key

  array_multisort($grid['cols'], SORT_ASC, $grid['rows'], SORT_ASC, $workingTiles);

  $offset = array();

// workingTiles has been sorted by column then row
// run through tiles per column from the 1st row down,
// increasing column offset when a rowspan is found. 
// (may not handle 3x height tiles well? may need another for loop)

  foreach ($workingTiles as $tile_id => $tile_data) {

    // create the column offset storage

    if (!(isset($offset[$tile_data['col']]))) { $offset[$tile_data['col']] = 0; } 

      $full_height = array(
        'dimmer',
        'thermostat',
        'bulb',
        'shade',
        'clock',
        'date',
        'dashboard-link',
        'mode',
        'music-player',
        'video-player',
        'volume',
        'weather'
      );

      if (in_array($tile_data['template'], $full_height)) {
        $workingTiles[$tile_id]['row'] = ($tile_data['row'] - 1 + $tile_data['row'] + ($offset[$tile_data['col']] * 1));// - 1;
        $workingTiles[$tile_id]['rowSpan'] = 2;
      } else {
        $workingTiles[$tile_id]['row'] = ($tile_data['row'] - 1 + $tile_data['row'] + ($offset[$tile_data['col']] * 1)) ;
      }

      if ($tile_data['rowSpan'] > 1) {
        $offset[$tile_data['col']] += $tile_data['rowSpan'] - 2; //1;
      } 

      if (in_array($tile_data['template'], $full_height)) {
        $workingTiles[$tile_id]['rowSpan'] = 2;
      }
    }

    // cleanup

    foreach ($workingTiles as $tile_id => $tile_data) {
      if ($tile_data['template'] == 'fake') {
      unset($workingTiles[$tile_id]);
    }
  }

  $smartly_tile = array(
    "template" => "smartly",
    "id" => 0,
    "device" => "smartly",
    "templateExtra" => ""
  );

  // add smartly tile to position 0 of tiles[]

  array_unshift($workingTiles, $smartly_tile);
  $inputJSON['tiles'] = array_values($workingTiles); // Hubitat Dashboard doesn't like indexed array for tiles.
}


/*
 * TODO: split into smartly_regen() function 
 *
 * rebuilds smartly_data tiles based on inputJSON tiles, preserving
 * data from old smartly_data (if tile type is changed) and adding
 * and deleting tiles as necessary
*/

//var_dump($smartly_data, "SDATA");

foreach ($smartly_data['dashboard']['mods'] as $mod_name => $mod_data) {
//echo $mod_name;
}

// build refreshed smartly data from tiles
foreach ($inputJSON['tiles'] as $pos => $tile) {

  // build smartly data for all tiles, excluding smartly data

  if (($tile['template'] != "smartly") && ($tile['device'] != "smartly_zoomy")) {
    $tile_data = array(); // reset tile_data
    $tile_data['id'] = $tile['id'];
    $tile_data['template'] = $tile['template'];
    $tile_data['templateExtra'] = $tile['templateExtra'];
    $tile_data['pos'] = $pos;
/*
    // strip HE native "Custom Icon" data, as it prevents the use of states icons.
    if (array_key_exists('customIcon', $tile)) {
      unset($inputJSON['tiles'][$pos]['customIcon']);
    }
*/
    $maxrow = ($tile['row']) + ($tile['rowSpan']); // - 1);
    $maxcol = ($tile['col']) + ($tile['colSpan']); // - 1);
    if( $maxrow > $calibrate_rows) { $calibrate_rows = $maxrow; } // needs -3 correction for hubitat JSON format
    if( $maxcol > $calibrate_cols) { $calibrate_cols = $maxcol; }
/*
      $tile_data['col'] = $tile['col'];
      $tile_data['colSpan'] = $tile['colSpan'];
      $tile_data['row'] = $tile['row'];
      $tile_data['rowSpan'] = $tile['rowSpan'];
*/

// TODO: this is a mess, it should be processing them based on case statement.
// though, title data is global, every tile will have a title or a label so
// it will be separate from other regen.. it should be cleaned up though.

    // BUILDFIELDS
    // DYNAMIC RETRIEVE AND BUILD STORAGE FOR FIELDS
    // iterate through all available mods

    foreach ($mods_enabled['tiletype'] as $mod => $tiletype) {
      // determine if tiletype is enabled for a particular mod

      if (in_array($tile_data['template'], $tiletype)) {
        // tiletype is found enabled for a mod, import existing data or build field with null.

        $mod_default = null;
        if ($mods_repo['tiletype'][$mod]['default']) { $mod_default =  $mods_repo['tiletype'][$tiletype]['default']; }

        $tile_data['mods'][$mod]['value'] = $smartly_data['tiles'][$tile['id']]['mods'][$mod]['value'] ? $smartly_data['tiles'][$tile['id']]['mods'][$mod]['value'] : $mod_default; //null;

        // LEGACY SUPPORT - pre ['mod']
        $tile_data['mods'][$mod]['value'] = $smartly_data['tiles'][$tile['id']][$mod] ? $smartly_data['tiles'][$tile['id']][$mod] : $tile_data['mods'][$mod]['value'];

        foreach ($mods_repo['tiletype'][$mod]['modifier'] as $modifier_name => $modifier_data) {

          $mod_modifier_default = null;
          if ($mods_repo['dashboard'][$mod]['modifier'][$modifier_name]['default']) { $mod_modifier_default =  $mods_repo['dashboard'][$mod]['modifier'][$modifier_name]['default']; }

          $tile_data['mods'][$mod]['modifier'][$modifier_name]['value'] = $smartly_data['tiles'][$tile['id']]['mods'][$mod]['modifier'][$modifier_name]['value'] ? $smartly_data['tiles'][$tile['id']]['mods'][$mod]['modifier'][$modifier_name]['value'] :$mod_modifier_default; //null;
        }
      }
    }

/*
    foreach ($mods_repo['contrib'] as $mod => $tiletype) {
      // tiletype is found enabled for a mod, import existing data or build field with null.
      $tile_data['mods'][$mod]['value'] = $smartly_data['tiles'][$tile['id']]['mods'][$mod]['value'] ? $smartly_data['tiles'][$tile['id']]['mods'][$mod]['value'] : null;

      foreach ($mods_repo['tiletype'][$mod]['modifier'] as $modifier_name => $modifier_data) {
        $tile_data['mods'][$mod]['modifier'][$modifier_name]['value'] = $smartly_data['tiles'][$tile['id']]['mods'][$mod]['modifier'][$modifier_name]['value'] ? $smartly_data['tiles'][$tile['id']]['mods'][$mod]['modifier'][$modifier_name]['value'] : null;
      }
    }
*/





    // SPECIFIC LEGACY FIXES

    // NUDGE

    if (in_array($tile_data['template'], $mods_enabled['tiles']['nudge']))  {
      if ($tile_data['title_wrap']) {
        $tile_data['mods']['nudge'] = $smartly_data['tiles'][$tile['id']]['title_wrap'] ? $smartly_data['tiles'][$tile['id']]['title_wrap'] :  $tile_data['nudge'];
      } elseif ($tile_data['icon_nudge']) { 
        $tile_data['mods']['nudge'] = $smartly_data['tiles'][$tile['id']]['icon_nudge'] ? $smartly_data['tiles'][$tile['id']]['icon_nudge'] :  $tile_data['nudge'];
      }
    }   

    // ATTRIBUTE // 3RD PARTY

    if ($tile['template'] == "attribute") {
      if ($tile_data['templateExtra']) {
        $thirdparty = $tile_data['templateExtra'];

          // check if explicit match first
        if (array_key_exists($thirdparty, $mods_repo['contrib'])) {

          $mod_default = null;
          if ($mods_repo['contrib'][$thirdparty]['default']) { $mod_default =  $mods_repo['contrib'][$thirdparty]['default']; }

          $tile_data['contrib'][$thirdparty]['value'] = $smartly_data['tiles'][$tile['id']]['contrib'][$thirdparty]['value'] ? $smartly_data['tiles'][$tile['id']]['contrib'][$thirdparty]['value'] : $mod_default; //null;
          foreach ($mods_repo['contrib'][$thirdparty]['modifier'] as $modifier_name => $modifier_data) {

            $mod_modifier_default = null;
            if ($mods_repo['contrib'][$thirdparty]['modifier'][$modifier_name]['default']) { $mod_modifier_default =  $mods_repo['contrib'][$thirdparty]['modifier'][$modifier_name]['default']; }

            $tile_data['contrib'][$thirdparty]['modifier'][$modifier_name]['value'] = $smartly_data['tiles'][$tile['id']]['contrib'][$thirdparty]['modifier'][$modifier_name]['value'] ? $smartly_data['tiles'][$tile['id']]['contrib'][$thirdparty]['modifier'][$modifier_name]['value'] : $mod_modifier_default; //null;
          }
        } else if (strpos($thirdparty, '-') > 0) {

          // check for fuzzy 3rd party patch
          $sub_match_array = explode("-", $tile_data['templateExtra']);
          $sub_match = $sub_match_array[0];

          if (array_key_exists($sub_match,$mods_repo['contrib'])) {

            $mod_default = null;
            if ($mods_repo['contrib'][$sub_match]['default']) { $mod_default =  $mods_repo['contrib'][$sub_match]['default']; }

            $tile_data['contrib'][$sub_match]['value'] = $smartly_data['tiles'][$tile['id']]['contrib'][$sub_match]['value'] ? $smartly_data['tiles'][$tile['id']]['contrib'][$sub_match]['value'] : null;
            foreach ($mods_repo['contrib'][$sub_match]['modifier'] as $modifier_name => $modifier_data) {

              $mod_modifier_default = null;
              if ($mods_repo['contrib'][$sub_match]['modifier'][$modifier_name]['default']) { $mod_modifier_default =  $mods_repo['contrib'][$sub_match]['modifier'][$modifier_name]['default']; }

              $tile_data['contrib'][$sub_match]['modifier'][$modifier_name]['value'] = $smartly_data['tiles'][$tile['id']]['contrib'][$sub_match]['modifier'][$modifier_name]['value'] ? $smartly_data['tiles'][$tile['id']]['contrib'][$sub_match]['modifier'][$modifier_name]['value'] : $mod_modifier_default; //null;
            }
          }
        }

      }

      // DYNAMIC 3RD PARTY ATTRIBUTE MODS

/*
      $templateExtra_vendor = explode('-', $tile_data['templateExtra']);

      if (in_array($templateExtra_vendor[0], $mods['contrib'])) {
        foreach ($mods['contrib'][$templateExtra_vendor] as $mod) {

$tile_data['mods']['contrib'][$templateExtra_vendor][$mod]['value'] = $smartly_data['tiles'][$tile['id']]['mods']['contrib'][$templateExtra_vendor][$mod]['value'] ? $smartly_data['tiles'][$tile['id']]['mods']['contrib'][$templateExtra_vendor][$mod]['value'] : null;
        }
      }
*/

      $tile_data['mods']['unit'] = $smartly_data['tiles'][$tile['id']]['attribute']['unit'] ? $smartly_data['tiles'][$tile['id']]['attribute']['unit'] : $tile_data['mods']['unit'];
      $tile_data['mods']['numeric'] = $smartly_data['tiles'][$tile['id']]['attribute']['numeric'] ? $smartly_data['tiles'][$tile['id']]['attribute']['numeric'] : $tile_data['mods']['numeric'];
    }

// TODO: to make scalable, this should probably be based on a case statement, based on template type.

    // refresh states for tile template type

    if (is_array($statelookup[$tile['template']])) {
      foreach ($statelookup[$tile['template']] as $statename => $stateclass) {

        // retrieve existing icon value for template state if it exists

        $tile_data['states'][$statename]['code'] = $smartly_data['tiles'][$tile['id']]['states'][$statename]['code'] ? $smartly_data['tiles'][$tile['id']]['states'][$statename]['code'] : null;
        $tile_data['states'][$statename]['class'] = $stateclass;

//          $smartly_css['icon'][$tile['id']][$states] = $tile_data['states'][$states];

      }
    }

    $tiles[$tile['id']] = $tile_data;

    // update inputJSON with the correct number of columns and rows to accomodate tiles.
    $inputJSON['rows'] = $calibrate_rows - 1;
    $inputJSON['cols'] = $calibrate_cols - 1;

  } else {

    if ($tile['device'] == "smartly_zoomy") {
      unset($inputJSON['tiles'][$pos]);
    }

    // remove old smartly data from Template JSON, we've already used it and will replace with updated data

// let's overwrite if exists..
//    unset($inputJSON['tiles'][$pos]);

/*
    $tile_data = array();
    $tile_data['id'] = $tile->id;
    $tile_data['pos'] = $pos;
    $tile_data['data'] =  $tile->templateExtra;
    $tiles['smartly'] = $tile_data;
*/
  }
}


// @TODO why on earth am I splitting smartly_data into separate variables instead of just writing null corrections to inputSDADA
// if updated smartlydata was passed, use it instead of the generated smartly data.

// at this point, $tiles contains the regen'd smartly_data, which should have kept
// any existing smartly_data, but will have added and deleted tiles as necessary to
// sync with inputJSON tiles[].

if ($smartly_data['tiles'] && $smartly_touched == true) { $tiles = $smartly_data['tiles']; } // LEGACY

if ($smartly_data['settings']) { 
  $smartly_data['settings']['calibration']['source'] = $device_cals_path;
  $smartly_data['settings']['calibration']['colwidth'] = $inputJSON['colWidth'];
  $smartly_data['settings']['calibration']['gridgap'] = $inputJSON['gridGap'];
  $smartly_data['settings']['calibration']['colcount'] = $inputJSON['cols'];
  $smartly_data['settings']['iconSize'] = $inputJSON['iconSize'];
  $smartly_data['settings']['fontSize'] = $inputJSON['fontSize'];
  $smartly_data['settings']['commit'] = $smartly_head;
  $smartly_settings = $smartly_data['settings'];
} else {
  $smartly_settings = array('commit' => $smartly_head . "two"); //'calibration' => array('devices' => null, 'devices_2col' => null),
}

$dashboard_mods['mods']['cal_devices'] = $smartly_data['dashboard']['mods']['cal_devices'] ? $smartly_data['dashboard']['mods']['cal_devices'] : null;
$dashboard_mods['mods']['cal_devices_2col'] = $smartly_data['dashboard']['mods']['cal_devices_2col'] ? $smartly_data['dashboard']['mods']['cal_devices_2col'] : null;
$dashboard_mods['mods']['header']['value'] = $smartly_data['dashboard']['mods']['header']['value'] ? $smartly_data['dashboard']['mods']['header']['value'] : 'default';
$dashboard_mods['mods']['hide_scrollbars']['value'] = $smartly_data['dashboard']['mods']['hide_scrollbars']['value'] ? $smartly_data['dashboard']['mods']['hide_scrollbars']['value'] : null;


//var_dump($smartly_settings);
/*a

a
$smartly_tile = array(
  "template" => "smartly",
  "id" => 0,
  "device" => "smartly",
  "templateExtra" => json_encode($tiles)
);

// add smartly tile to position 0 of tiles[]

array_unshift($inputJSON['tiles'], $smartly_tile);
$inputJSON['tiles'] = array_values($inputJSON['tiles']); // Hubitat Dashboard doesn't like indexed array for tiles.
*/

// @TODO why on earth am I splitting smartly_data into separate variables instead of just writing null corrections to inputSDADA

// update smartly tile with new new smartly data
$inputJSON['tiles'][0]['templateExtra'] = json_encode(array("tiles" => $tiles, "settings" => $smartly_settings, "dashboard" => $dashboard_mods));

// set up css replacements so they are accessible globally.
$css_title_replacements = array();
$css_label = array();
$css_icon_replacements = array();

// parse existing customCSS, replacing base_css if instructed to
$smartly_css_parsed = smartly_parse_css($inputJSON['customCSS'], $smartly_css_delimiters, $repo_base_css, $repo_skin_css, $update_options);

// build CSS based on smartly data, base and user
$inputJSON['customCSS'] = smartly_build_css($tiles, $smartly_css_delimiters, $smartly_css_parsed['base'], $smartly_css_parsed['skin'], $smartly_css_parsed['user'], $smartly_settings);

// build and attach a zoomy helper tile to the tiles array
if ($smartly_data['dashboard']['mods']['zoomy'] === true) {
  $zoomy_tile = smartly_zoomy((max(array_keys($tiles))+1), $inputJSON['colWidth'], $inputJSON['gridGap'], $inputJSON['cols']);
  array_push($inputJSON['tiles'], $zoomy_tile);
  $inputJSON['tiles'] = array_values($inputJSON['tiles']); // Hubitat Dashboard doesn't like indexed array for tiles.
}

// build the return array of json for smartly-helper form
$return_json = array("outputJSON" => json_encode($inputJSON), "smartlyDATA" => array('tiles' => $tiles, 'settings' => $smartly_settings, "dashboard" => $dashboard_mods));
echo json_encode($return_json);



/**
 * smartly_parse_css() splits customCSS:[] from input JSON into base,
 * auto, and user css.  If instructed to, it will also update base_css to
 * repo master.
 *
 * @param $input_css
 *   A string containing the customCSS:[] from the input JSON.
 * @param $delimiters
 *   An array of CSS delimiters for base, auto and user defined in setup.
 * @param $base_css
 *   A string containing the base_css from repo master, if 'Update CSS' is selected in options.
 * @param $update
 *   An array containing the form submitted update options.
 * @return
 *  An array containing base and user css.  auto is generated at runtime so no need to output.
 */

function smartly_parse_css($input_css = null, $delimiters = null, $base_css = null, $skin_css = null, $update = null) {
  $css = array();
  $delimiters_escaped = array();

  // prep delimiters for use in preg_replace
  foreach ($delimiters as $name => $value) {
    $delimiters_escaped[$name] = preg_quote($value,'/');
  }

  if (strpos($input_css, $delimiters['base']) !== false) { // delimiter exists
    $preg_delimiter = implode("|", array_values($delimiters_escaped));
    $input_css_result = preg_split("/$preg_delimiter/", $input_css, -1, PREG_SPLIT_NO_EMPTY);

    $css['base'] = $input_css_result[0]; // is always first

    if (strpos($input_css, $delimiters['skin']) !== false) { // this has been updated recently, has skin functionality
//        $css['skin'] = $input_css_result[1];
      $css['user'] = "\r\n" . trim($input_css_result[3]) . "\r\n";
    } else {
//    $css['auto'] = $input_css_result[1];
      $css['skin'] = $skin_css; // . "/* added */"; //'';
      $css['user'] = "\r\n" . trim($input_css_result[2]) . "\r\n";
    }

    if (in_array('css', $update)) {
      $css['base'] = $base_css;
      $css['skin'] = $skin_css;
    }

  } else {
    $css['base'] = $base_css;
    $css['skin'] = $skin_css;

    if(trim($input_css)) {

     $css['user'] = "

/* It looks like you didn't delete your existing css before updating for the first time.. that's ok.  Here is is, but we commented it out.

$input_css  

Add your custom CSS in the space below.. */


";
    } else {
      $css['user'] = "\r\n\r\n";  
    }
  }
  return $css;
}


/**
 * smartly_build_css() creates the necessary CSS to provide additional
 * title, label and icon replacement functionality for the dashbaord.
 *
 * @param $smartly_tiles
 *   An array containing each smartly formatted tile with smartly data.
 * @param $delimiters
 *   An array of CSS delimiters for base, auto and user defined in setup.
 * @param $base_css
 *   A string containting the basic formatting CSS from smartly github repo.
 * @param $user_css
 *   A string containing any CSS the user added below the UserCSS delimiter to be preserved.
 * @return
 *  A string containing the entire customCSS for addition to Layout JSON.
 */

function smartly_build_css($smartly_tiles = null, $delimiters = null, $base_css = null, $skin_css = null, $user_css = null, $settings = array()) {

  $smartly_data = $GLOBALS['smartly_data'];
  $mods_repo = $GLOBALS['mods_repo'];
  $mods_enabled = $GLOBALS['mods_enabled'];
  $inputJSON = $GLOBALS['inputJSON'];
  $fontsize_calc = strval($settings['fontSize'] * 1.5) . "px";
  $fontsize_calc_lg = strval($settings['fontSize'] * 1.75) . "px";
  $lb = "\r\n\r\n"; // line break for future use

  foreach ($smartly_data['dashboard']['mods'] as $mod => $mod_data) {
    if (!(is_null($mod_data['value'])) && $mods_repo['dashboard'][$mod]) {

      switch ($mods_repo['dashboard'][$mod]['type']) {
        case 'select':

          $token_replacements = array(
            '[value]' => $mod_data['value'],
            '[grid_gap]' => $inputJSON['gridGap'],
            '[grid_gap_header]' => $inputJSON['gridGap'] + 60
          );

          $css = $mods_repo['dashboard'][$mod]['value'][$mod_data['value']]['css'] ? $mods_repo['dashboard'][$mod]['value'][$mod_data['value']]['css'] : $mods_repo['dashboard'][$mod]['value']['default']['css'];

          break;

        case 'checkbox':

          $token_replacements = array(
              '[grid_gap]' => $inputJSON['gridGap'],
              '[grid_gap_header]' => $inputJSON['gridGap'] + 60
          );

          //print_r($mod);
          //print_r($mod_data['value']);
          if ($mod_data['value'] > 0) {
            $css = $mods_repo['dashboard'][$mod]['default']['css'];
          }
          break;

        default:

          $token_replacements = array(
            '[value]' => $mod_data['value'],
            '[grid_gap]' => $inputJSON['gridGap'],
            '[grid_gap_header]' => $inputJSON['gridGap'] + 60
          ); 

          $css = $mods_repo['dashboard'][$mod]['css'][$mod_data['value']] ? $mods_repo['dashboard'][$mod]['css'][$mod_data['value']] : $mods_repo['dashboard'][$mod]['css']['default'];

      }

      // check if the mod has tiletype specific css and if not, use default css.  do token replacements as needed.
      $smartly_css['mods'][$mod][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

    } 
  }

  foreach ($smartly_tiles as $smart_id => $smart_data) {

    foreach ($mods_enabled['tiletype'] as $mod => $tiletype) {
      if (in_array($smart_data['template'], $tiletype) && $smart_data['mods'][$mod]['value'] && $smart_data['mods'][$mod]['value'] !== 'unchecked' && $smart_data['mods'][$mod]['value'] !== 'default') {


        switch ($mods_repo['tiletype'][$mod]['type']) {

          case 'select':

            $token_replacements = array(
                '[tile_id]' => $smart_id,
                '[value]' => $smart_data['mods'][$mod]['value'],
                '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
                '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
                '[padding_calc]' => strval($settings['fontSize'] / 14) . "em",
                '[padding_adjust]' => strval(9 - $smart_data['mods'][$mod]['value'])
            );

            if ($mods_repo['tiletype'][$mod]['css']['value']['default']) { // value based lookup
              $css = $mods_repo['tiletype'][$mod]['css']['value'][$smart_data['template']][$smart_data['mods'][$mod]['value']] ? $mods_repo['tiletype'][$mod]['css']['value'][$smart_data['template']][$smart_data['mods'][$mod]['value']] : $mods_repo['tiletype'][$mod]['css']['value']['default'][$smart_data['mods'][$mod]['value']];
            } else { // template based lookup
              $css = $mods_repo['tiletype'][$mod]['css'][$smart_data['template']] ? $mods_repo['tiletype'][$mod]['css'][$smart_data['template']] : $mods_repo['tiletype'][$mod]['css']['default'];
            }

            // check if the mod has tiletype specific css and if not, use default css.  do token replacements as needed.
            $smartly_css['mods'][$mod][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

            break;

          default:

            $token_replacements = array(
                '[tile_id]' => $smart_id,
                '[value]' => $smart_data['mods'][$mod]['value'],
                '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
                '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
                '[padding_calc]' => strval($settings['fontSize'] / 14) . "em",
                '[padding_adjust]' => strval(9 - $smart_data['mods'][$mod]['value'])
            );

            $css = $mods_repo['tiletype'][$mod]['css'][$smart_data['template']] ? $mods_repo['tiletype'][$mod]['css'][$smart_data['template']] : $mods_repo['tiletype'][$mod]['css']['default'];

            // check if the mod has tiletype specific css and if not, use default css.  do token replacements as needed.
            $smartly_css['mods'][$mod][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

        }

        // iterate through modifiers that have values and add their css
        foreach ($smart_data['mods'][$mod]['modifier'] as $mod_modifier => $modifier_data) {
          if ($smart_data['mods'][$mod]['modifier'][$mod_modifier]['value'] && $smart_data['mods'][$mod]['modifier'][$mod_modifier]['value'] !== 'unchecked' && $smart_data['mods'][$mod]['modifier'][$mod_modifier]['value'] !== 'default') {

            $token_replacements = array(
                '[tile_id]' => $smart_id,
                '[value]' => $smart_data['mods'][$mod]['modifier'][$mod_modifier]['value'],
                '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
                '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
                '[padding_calc]' => strval($settings['fontSize'] / 14) . "em"
            );

            $css = $mods_repo['tiletype'][$mod]['modifier'][$mod_modifier]['css'][$smart_data['template']] ? $mods_repo['tiletype'][$mod]['modifier'][$mod_modifier]['css'][$smart_data['template']] : $mods_repo['tiletype'][$mod]['modifier'][$mod_modifier]['css']['default'];

            $smartly_css['mods'][$mod . "__" . $mod_modifier][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

          }
        }
      }
    }

    // @TODO: All of this shit needs to be a re-usable function, dear god.

    // if attribute tile, it could potentially be a 3rd party tile
    if ($smart_data['template'] == 'attribute') {
      if ($smart_data['templateExtra']) {

        if (array_key_exists($smart_data['templateExtra'],$mods_repo['contrib'])) {
          // we have an explicit match of contrib CSS

          $mod = $smart_data['templateExtra'];
          $mod_value = $smart_data['contrib'][$mod]['value'];

          $token_replacements = array(
              '[tile_id]' => $smart_id,
              '[value]' => $mod_value,
              '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
              '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
              '[padding_calc]' => strval($settings['fontSize'] / 14) . "em"
          );

          // assume base mod for contrib is an 'enable/disable' checkbox
          if ($mod_value == '1' && $mods_repo['contrib'][$mod]['type'] == 'checkbox') {
            $css = $mods_repo['contrib'][$mod]['css'][$mod_value] ? $mods_repo['contrib'][$mod]['css'][$mod_value] : $mods_repo['contrib'][$mod]['css']['default'];

            // only add css if base modifier checkbox is checked (contrib mod on/off)
            $smartly_css['contrib'][$mod][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

            // iterate through modifiers that have values and add their css
            // only if base mod is enabled or populated
            foreach ($smart_data['contrib'][$mod]['modifier'] as $mod_modifier => $modifier_data) {
              if ($smart_data['contrib'][$mod]['modifier'][$mod_modifier]['value']) {

                $mod_modifier_value = $smart_data['contrib'][$mod]['modifier'][$mod_modifier]['value'];
                $mod_modifier_type = $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['type'];

                $token_replacements = array(
                    '[tile_id]' => $smart_id,
                    '[value]' => $mod_modifier_value,
                    '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
                    '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
                    '[padding_calc]' => strval($settings['fontSize'] / 14) . "em"
                );

                switch ($mod_modifier_type) {
                  case 'checkbox':
                    //print_r($mod_modifier_value);
                    if ($mod_modifier_value > 0) {
                      $css = $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css'];
                    }
                    break;

                  case 'select':
                    $css = $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css'][$mod_modifier_value] ? $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css'][$mod_modifier_value] : $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css']['default'];

                    break;

                  default:
                    $css = $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css'][$mod_modifier_value] ? $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css'][$mod_modifier_value] : $mods_repo['contrib'][$mod]['modifier'][$mod_modifier]['css']['default'];

                    break;
                }

                $smartly_css['mods'][$mod . "__" . $mod_modifier][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

              }
            }
          }

        } elseif (strpos($smart_data['templateExtra'], '-') === TRUE) {

          // check for fuzzy 3rd party patch
          $sub_match_array = explode("-", $smart_data['templateExtra']);
          $sub_match = $sub_match_array[0];

          $mod_value = $smart_data['contrib'][$sub_match]['value'];

          // may have has explicit match 3rd party patch
          if (array_key_exists($sub_match, $mods_repo['contrib'])) {

            $token_replacements = array(
              '[tile_id]' => $smart_id,
              '[value]' => $mod_value,
              '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
              '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
              '[padding_calc]' => strval($settings['fontSize'] / 14) . "em"
            );

            $css = $mods_repo['contrib'][$sub_match]['css'][$smart_data['contrib'][$sub_match]['value']] ? $mods_repo['contrib'][$sub_match]['css'][$smart_data['contrib'][$sub_match]['value']] : $mods_repo['contrib'][$sub_match]['css']['default'];

            $smartly_css['contrib'][$sub_match][] = str_replace(array_keys($token_replacements), $token_replacements, $css);
            $smartly_css['contrib'][$smart_data['templateExtra']][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

            // iterate through modifiers that have values and add their css
            if ($mod_value == '1' && $mods_repo['contrib'][$sub_match]['type'] == 'checkbox') {
              // only add modifier values if base modifier checkbox is checked (contrib mod on/off)
              foreach ($smart_data['contrib'][$sub_match]['modifier'] as $mod_modifier => $modifier_data) {
                if ($smart_data['contrib'][$sub_match]['modifier'][$mod_modifier]['value']) {

                  $mod_modifier_value = $smart_data['contrib'][$sub_match]['modifier'][$mod_modifier]['value'];
                  $mod_modifier_type = $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['type'];

                  $token_replacements = array(
                      '[tile_id]' => $smart_id,
                      '[value]' => $mod_modifier_value,
                      '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
                      '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
                      '[padding_calc]' => strval($settings['fontSize'] / 14) . "em"
                  );

                  switch ($mod_modifier_type) {
                    case 'checkbox':
                      //print_r($mod_modifier_value);
                      if ($mod_modifier_value > 0) {
                        $css = $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css'];
                      }
                      break;

                    case 'select':
                      $css = $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css'][$mod_modifier_value] ? $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css'][$mod_modifier_value] : $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css']['default'];

                      break;

                    default:
                      $css = $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css'][$mod_modifier_value] ? $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css'][$mod_modifier_value] : $mods_repo['contrib'][$sub_match]['modifier'][$mod_modifier]['css']['default'];

                      break;
                  }

                  $smartly_css['contrib'][$sub_match . "__" . $mod_modifier][] = str_replace(array_keys($token_replacements), $token_replacements, $css);

                }
              }
            }
          }
        }
      }
    }

    // check if tile has icon functionality (by checking if it has states)
    if ($smart_data['states']) {

      foreach ($smart_data['states'] as $state_name => $state_data) { //$state_code) {
        $icon_code = $state_data['code'];
        $icon_class = str_replace("_", ".", $state_data['class']);
        $icon_class_stock = $state_data['class'];
        $state_name = str_replace("_", ".", $state_name);

        $token_replacements = array(
          '[tile_id]' => $smart_id,
          '[value]' => $icon_code,
          '[fontsize_calc]' => strval($settings['fontSize'] * 1.5) . "px",
          '[fontsize_calc_lg]' => strval($settings['fontSize'] * 1.75) . "px",
          '[state]' => $state_name !== 'default' ? "." . $state_name : '',
          '[class_stock]' => $icon_class_stock,
          '[class]' => $icon_class
        );

        // icon is selected

        if (strlen($state_data['code']) > 0) {

          // if specific css exists for this tiletype, use it, otherwise use the default css.
          $processed_css =  $mods_repo['tiletype']['icon']['css'][$smart_data['template']] ? $mods_repo['tiletype']['icon']['css'][$smart_data['template']] : $mods_repo['tiletype']['icon']['css']['default'];

          // look for and replace 'fixup' tokens with their specific fixup css
          preg_match_all('/\[fixup-(.*)\]/',  $processed_css, $fixup_matches, PREG_SET_ORDER);

          // add to the token replacement array
          foreach ($fixup_matches as $index => $match) {
            $token_replacements[$match[0]] = $mods_repo['tiletype'][$match[1]]['css']['fixup']['icon'];
          }

          // check if the mod has tiletype specific css and if not, use default css.  do token replacements as needed.
          $smartly_css['mods']['icon'][] = str_replace(array_keys($token_replacements), $token_replacements, $processed_css);
        }
      }
    }
  }


  if ($smartly_data['dashboard']['mods']['cal_devices'] || $settings['dashboard']['mods']['cal_devices_2col']) {

    $cal_devices_json = file_get_contents($settings['calibration']['source']); //json_dev
    $cal_devices_json = json_decode($cal_devices_json, true);

    if ($smartly_data['dashboard']['mods']['cal_devices']) {

     foreach ($smartly_data['dashboard']['mods']['cal_devices'] as $index => $device) {

       $key = array_search($device, array_column($cal_devices_json, 'value'));
       $height = $cal_devices_json[$key]['height'];
       $width = $cal_devices_json[$key]['width'];

       $bestmatch_p = smartly_calibrate(.8, $width, $settings['calibration']['colwidth'], $settings['calibration']['gridgap'], $settings['calibration']['colcount']);

       $smartly_css['calibration'][] = "@media screen and (orientation: portrait) and (max-width:" . ($width + 1) . "px) and (min-width:" . ($width - 1) . "px){.dashboard{zoom:" . $bestmatch_p['zoom'] . "; -moz-transform:scale(" . $bestmatch_p['zoom'] . ");}}" . $lb;

       $bestmatch_h = smartly_calibrate(.8, $height, $settings['calibration']['colwidth'], $settings['calibration']['gridgap'], $settings['calibration']['colcount']);

       $smartly_css['calibration'][] = "@media screen and (orientation: landscape) and (max-width:" . ($height + 1) . "px) and (min-width:" . ($height - 1) . "px){.dashboard{zoom:" . $bestmatch_h['zoom'] . "; -moz-transform:scale(" . $bestmatch_h['zoom'] . ");}}" . $lb;

      }
    }

    if ($smartly_data['dashboard']['mods']['cal_devices_2col']) {

      foreach ($smartly_data['dashboard']['mods']['cal_devices_2col'] as $index => $device) {

        $key = array_search($device, array_column($cal_devices_json, 'value'));
        $width = $cal_devices_json[$key]['width'];

        $bestmatch = smartly_calibrate(.8, $width, $settings['calibration']['colwidth'], $settings['calibration']['gridgap'], $settings['calibration']['colcount']);

        $smartly_css['calibration'][] = "@media screen and (orientation: portrait) and (max-width:" . ($width + 1) . "px) and (min-width:" . ($width - 1) . "px){.dashboard{zoom:1; -moz-transform:scale(1);} .dashboard .wrapper { grid-template-columns: repeat(" . $settings['calibration']['colcount'] . ", calc(50% - " . ($settings['calibration']['gridgap'] - 1) . "px))!important;}}" . $lb;

      }
    }

  }  

  // combine and optimize CSS

  $optimize = new \CssOptimizer\Css\Optimizer;

  // iterate through individual mods css, optimize 
  foreach (array_reverse($smartly_css['mods']) as $mod_name => $mod_css) {
    foreach ($mod_css as $css) {
      $smartly_mods_css[] = $css;
    }
  }

  // iterate through individual mods css, optimize 
  foreach ($smartly_css['contrib'] as $mod_name => $mod_css) {
    foreach ($mod_css as $css) {
      $smartly_mods_css[] = $css;
    }
  }


  $optimized_css['mods'] = $optimize->optimizeCss(implode($lb, $smartly_mods_css));
  $optimized_css['calibration'] = $optimize->optimizeCss(implode($lb, $smartly_css['calibration']));

  $smartly_css_flat = [
    $delimiters['base'],
    trim($base_css),
    $delimiters['skin'],
    trim($skin_css),
    $delimiters['auto'],
    $optimized_css['mods'],
    $optimized_css['calibration'],
    $delimiters['user'],
    $user_css
  ];

  $smartly_css_flat = implode($lb, array_filter($smartly_css_flat));

  return $smartly_css_flat;

}


/**
 *
 * smartly_calibrate()
 *
 *
 *
 */
function smartly_calibrate($minzoom = null, $screenwidth = null, $colwidth = null, $gap = null, $colcount = null) {
// TODO: make dynamically generated on the fly until a bestmatch is found, possibly a for loop
  $columns = [
    'one' => ($gap * 2) + $colwidth,
    'two' => ($gap * 3) + ($colwidth * 2),
    'three' => ($gap * 4) + ($colwidth * 3),
    'four' => ($gap * 5) + ($colwidth * 4),
    'five' => ($gap * 6) + ($colwidth * 5),
    'six' => ($gap * 7) + ($colwidth * 6),
    'seven' => ($gap * 8) + ($colwidth * 7),
    'eight' => ($gap * 9) + ($colwidth * 8),
    'nine' => ($gap * 10) + ($colwidth * 9),
    'ten' => ($gap * 11) + ($colwidth * 10),
    'eleven' => ($gap * 12) + ($colwidth * 11),
    'twelve' => ($gap * 13) + ($colwidth * 12)
  ];

  $bestmatch = [
    'name' => null,
    'width' => null,
    'zoom' => null,
    'abszoom' => 999
  ];


  foreach ($columns as $column => $width) {
    $zoom = ($screenwidth) / $width;
    $abszoom = abs($zoom - 1) + 1;

    if ($zoom > $minzoom && ($abszoom < $bestmatch['abszoom'])) {

      // specific overrides for well-known screen widths
      if ($screenwidth == 1280) {
        $bestmatch = [
        'name' => 'eight',
        'width' => $columns['eight'],
        'zoom' => ($screenwidth) / $columns['eight'],
        'abszoom' => abs((($screenwidth) / $columns['eight']) - 1) + 1
        ];
        break;
      }  elseif ($screenwidth == 1920) {
        $bestmatch = [
        'name' => 'nine',
        'width' => $columns['nine'],
        'zoom' => ($screenwidth) / $columns['nine'],
        'abszoom' => abs((($screenwidth) / $columns['nine']) - 1) + 1
        ];
        break;

      }  elseif ($screenwidth > 359 && $screenwidth < 421) {
        $bestmatch = [
        'name' => 'three',
        'width' => $columns['three'],
        'zoom' => ($screenwidth) / $columns['three'],
        'abszoom' => abs((($screenwidth) / $columns['three']) - 1) + 1
        ];
        break;

      } else {
        // save the current best match, only if it's better than the last
        $bestmatch = [
        'name' => $column,
        'width' => $width,
        'zoom' => round($zoom,3),
        'abszoom' => $abszoom
        ];
      }
  //      print "bestmatch [$bestmatch] so far is: $column at $width with zoom of $zoom [$abszoom]<br><br>";

    } else {

  //      print "zoom too small: $column at $width with zoom of $zoom [$abszoom]<br><br>";
    }
  }

  return $bestmatch;

}



/**
 * smartly_zoomy() adds a tile that assists the user in choosing
 * a precise css zoom to cleanly align columns to the edges of the screen.
 *
 * @param $next_id
 *   An integer representing the next available tile_id.
 * @param $colwidth
 *   An integer representing the column width from the Layout JSON.
 * @param $gap
 *   An integer representing the grid gap size from the Layout JSON.
 * @return
 *   An array containing a fully formatted tile to be added to inputJSON['tiles'].
 */

function smartly_zoomy($next_id = null, $colwidth = null, $gap = null, $colcount = null) {
    
//    $gap = 15;
//    $colwidth = 135;
//  $screenwidth = 390;

  $minzoom = .80;
  $lb = "\r\n";
  $output_html = "";

  // TODO: make dynamically generated on the fly until a bestmatch is found, possibly a for loop
  $columns = [
    'one' => ($gap * 2) + $colwidth,
    'two' => ($gap * 3) + ($colwidth * 2),
    'three' => ($gap * 4) + ($colwidth * 3),
    'four' => ($gap * 5) + ($colwidth * 4),
    'five' => ($gap * 6) + ($colwidth * 5),
    'six' => ($gap * 7) + ($colwidth * 6),
    'seven' => ($gap * 8) + ($colwidth * 7),
    'eight' => ($gap * 9) + ($colwidth * 8),
    'nine' => ($gap * 10) + ($colwidth * 9),
    'ten' => ($gap * 11) + ($colwidth * 10),
    'eleven' => ($gap * 12) + ($colwidth * 11),
    'twelve' => ($gap * 13) + ($colwidth * 12)
  ];

  $output = [
    'css' => null,
    'html' => null,
    'attached' => null // allow attaching anything else after generated css
  ];

  for ($screenwidth = 300; $screenwidth <= 1920; $screenwidth+=5) {

    $bestmatch = [
    'name' => null,
    'width' => null,
    'zoom' => null,
    'abszoom' => 999
    ];

    // for each screenwidth step range, calculate required zooom
    // for each column count, and determine best zoom
    foreach ($columns as $column => $width) {
      $zoom = ($screenwidth + 4) / $width;
      $abszoom = abs($zoom - 1) + 1;

      if ($zoom > $minzoom && ($abszoom < $bestmatch['abszoom'])) {

        // specific overrides for well-known screen widths
        if ($screenwidth == 1280) {
          $bestmatch = [
          'name' => 'seven',
          'width' => $columns['seven'],
          'zoom' => round(($screenwidth + 4) / $columns['seven'],3),
          'abszoom' => abs((($screenwidth + 4) / $columns['seven']) - 1) + 1
          ];
          break;
        }  elseif ($screenwidth == 1920) {
          $bestmatch = [
          'name' => 'nine',
          'width' => $columns['nine'],
          'zoom' => round(($screenwidth + 4) / $columns['nine'],3),
          'abszoom' => abs((($screenwidth + 4) / $columns['nine']) - 1) + 1
          ];
          break;

        }  elseif ($screenwidth > 359 && $screenwidth < 421) {
          $bestmatch = [
          'name' => 'three',
          'width' => $columns['three'],
          'zoom' => round(($screenwidth) / $columns['three'],3),
          'abszoom' => abs((($screenwidth) / $columns['three']) - 1) + 1
          ];
          break;

        } else {
          // save the current best match, only if it's better than the last
          $bestmatch = [
          'name' => $column,
          'width' => $width,
          'zoom' => round($zoom,3),
          'abszoom' => $abszoom
          ];
        }
  //      print "bestmatch [$bestmatch] so far is: $column at $width with zoom of $zoom [$abszoom]<br><br>";

      } else {

  //      print "zoom too small: $column at $width with zoom of $zoom [$abszoom]<br><br>";

      }
    }

    if ($bestmatch['name'] == 'two') {

      $output['attach']['css'] = "@media screen and (max-width:" . ($screenwidth + 4) . "px) {.sw-" . $screenwidth . "{top: -" . $screenwidth ."em;}.zoomval_container{margin-top:-" . $screenwidth . "em;}.dashboard .wrapper { grid-template-columns: repeat(" . $colcount . ", calc(50% - " . ($gap - 1) . "px))!important;}}" . $lb;

//"@media screen and (max-width:" . ($screenwidth + 4) . "px) {.dashboard .wrapper { grid-template-columns: repeat(2, calc(50% - " . $gap . "px));}}" . $lb;

      $output['attach']['html'] = "<i style='top:" . $screenwidth . "em' class='sw-" . $screenwidth . "'>@media screen and (max-width: " . ($screenwidth + 4) . "px) {.dashboard .wrapper { grid-template-columns: repeat(" . $colcount . ", calc(50% - " . ($gap - 1) . "px))!important;}}</i>" . $lb;

    } else {

      $output['css'][] = "@media screen and (max-width:" . ($screenwidth + 4) . "px) and (min-width:" . $screenwidth . "px){.sw-" . $screenwidth . "{top: -" . $screenwidth ."em;}.zoomval_container{margin-top:-" . $screenwidth . "em;}.dashboard{zoom:" . $bestmatch['zoom'] . "; -moz-transform:scale(" . $bestmatch['zoom'] . ");}}" . $lb;

      $output['html'][] = "<i style='top:" . $screenwidth . "em' class='sw-" . $screenwidth . "'>@media screen and (max-width: " . ($screenwidth + 4) . "px) and (min-width:" . $screenwidth . "px){.dashboard{zoom:" .  $bestmatch['zoom'] . "; -moz-transform:scale(" . $bestmatch['zoom'] . ");}}</i>" . $lb;

    }
  }    // foreach width  

  $output['css'][] = $output['attach']['css'];
  $output['css'][] = "@media screen and (min-width: 1925px){.sw-default{display: block;}.zoomval_container{margin-top:0;}}" . $lb;
  $output['html'][] = $output['attach']['html'];
  $output['html'][] = "<i class='sw-default'>Screen width is larger or smaller than zoomy can calculate for.</i>"; //@media screen and (width: [screen width]px) { .dashboard { zoom: [under 1 for smaller, over 1 for larger];}}</i>";

  $parser = \WyriHaximus\HtmlCompress\Factory::construct();

  $output_html .= "<style>

  smartly-zoom {
    background-color: red!important;
    border: 2px solid #fff;
    color: #fff;
    padding: .5em;
    margin: 0
  }

  .zoomval_wrapper {
    height: 5em;
    overflow: hidden; 
    /* position: relative; */
    border: 1px solid red;
    width: 100%;
    word-break: break-word;
pointer-events: all;
    font-size: .9em;
  /*  transform: scale(.75);*/
  }

  .zoomval_container {
    position: relative;
    overflow: visible;
    height: 100%;
    width: 100%;
pointer-events: all;
    transition: all 1s ease-out !important;
  }

  .zoomval_container i {
    position: absolute;
    height: 1em;
    font-size: 100%;
    line-height: 1em;
    display: table;

  -webkit-user-select: all;  /* Chrome 49+ */
  -moz-user-select: all;     /* Firefox 43+ */
  -ms-user-select: all;      /* No support yet */
  user-select: all;          /* Likely future */   


-webkit-touch-callout: default;
  }

  * {
     -webkit-transition: none !important;
    transition: none !important;
  }
  ";

  $output_html .= "#tile-$next_id { background-color: red !important; border: 2px solid white; color: white; padding: 10px;} i { display: none; }\r\n";

  $output_html .= implode("", $output['css']);
  $output_html .="</style>";

  $output_html .= "<div class='zoomval_wrapper' style=''><div class='zoomval_container' style=''>";
  $output_html .= implode("", $output['html']);
  $output_html .= "</div></div>";

  $compressed_html = $parser->compress($output_html);

  $zoomy_tile = array(
    "template" => "texttile",
    "id" => $next_id,
    "device" => "smartly_zoomy",
    "templateExtra" => $compressed_html,
    "row" => 7,
    "rowSpan" => 2,
    "col" => 1,
    "colSpan" => 2
  );

  return $zoomy_tile;

}

function is_json($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Multi-array search
 *
 * @param array $array
 * @param array $search
 * @return array
 */
function multi_array_search($array, $search)
{

  // Create the result array
  $result = array();

  // Iterate over each array element
  foreach ($array as $key => $value)
  {

    // Iterate over each search condition
    foreach ($search as $k => $v)
    {

      // If the array element does not meet the search condition then continue to the next element
      if (!isset($value[$k]) || $value[$k] != $v)
      {
        continue 2;
      }

    }

    // Add the array element's key to the result array
    $result[] = $key;

  }

  // Return the result array
  return $result;

}


function get_current_git_commit( $branch='master' ) {
  if ( $hash = file_get_contents( sprintf( '.git/refs/heads/%s', $branch ) ) ) {
    return trim($hash);
  } else {
    return false;
  }
}

function stripos_array($haystack, $needles){
    foreach($needles as $needle) {
        if(($res = stripos($haystack, $needle)) !== false) {
            return $res;
        }
    }
    return false;
}

?>
