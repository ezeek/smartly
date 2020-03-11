<?php

// init smartly variables
require_once __DIR__ . '/vendor/autoload.php';

include 'assets/data/statelookup.php'; // contains array of each template type and their associated states

$repo_base = array(
  'css_sandbox' => 'https://hubitat.ezeek.us/smartly-base/smartly.css',
  'json_sandbox' => 'https://hubitat.ezeek.us/smartly-base/smartly.json',
  'css_dev' => 'https://raw.githubusercontent.com/ezeek/smartly-base/devel/smartly.css',
  'json_dev' => 'https://raw.githubusercontent.com/ezeek/smartly-base/devel/smartly.json',
  'css' => 'https://raw.githubusercontent.com/ezeek/smartly-base/master/smartly.css',
  'json' => 'https://raw.githubusercontent.com/ezeek/smartly-base/master/smartly.json'
);

if ($_POST['github_user']) {
$repo_skin_user = preg_replace("/[^ \w]+/", "", $_POST['github_user']); //
} else {
$repo_skin_user = 'ezeek'; // will allow user defined github user in future
}


if ($_POST['skin'] && $_POST['skin'] != 'smartly') {
  $repo_skin = array(
    'css' => 'https://raw.githubusercontent.com/' . $repo_skin_user . '/smartly-skins/master/' . $_POST['skin'] . '/' . $_POST['skin'] . '.css',
    'json' => 'https://raw.githubusercontent.com/' . $repo_skin_user . '/smartly-skins/master/' . $_POST['skin'] . '/' . $_POST['skin'] . '.json',
  );
} else {
  $repo_skin = null;
}

$device_cals_path = 'assets/data/device_cals.json';
$update_options = array();
$tiles = array();
$smartly_data = array();
$base_css = "";
$user_css = "";

$smartly_css = array(
  "title" => array(), // id and replacement title
  "label" => array(), // id and label to add
  "icon" => array() // each state, icon content code and icon font
);

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

// retrieve posted inputjson
if (is_json($_POST['inputjson'])) {
  $inputJSON = json_decode($_POST['inputjson'], true);
} else {
  echo "JSON Error";
  exit(0);
}
 
// retrieve posted smartlydata
if (is_json($_POST['smartlydata'])) {
  $inputSDATA = json_decode($_POST['smartlydata'], true);
  if (!($inputSDATA['tiles'])) { // if smartly_data is of pre-global-settings era, update it.
    $inputSDATA = array('tiles' => $inputSDATA, 'settings' => null);
  }
  
} elseif ($inputJSON['tiles'][0]['template'] == "smartly") {
  if (is_json($inputJSON['tiles'][0]['templateExtra'])) {
    $inputSDATA = json_decode($inputJSON['tiles'][0]['templateExtra'], true);
    if (!($inputSDATA['tiles'])) { // if smartly_data is of pre-global-settings era, update it.
      $inputSDATA = array('tiles' => $inputSDATA, 'settings' => null);
    }
  }
} else {
  $inputSDATA = null;
}

// parse selected update options
foreach ($_POST['options'] as $options) {
  $update_options[$options] = true;
}

/*
print "<pre>";
var_dump($_POST);
*/

// retrieve smartly base css and/or json from master if instructed
//if ($update_options['css']) {
  // get updated CSS from repo
  $repo_base_css = file_get_contents($repo_base['css_sandbox']); //css_dev
  $repo_skin_css = file_get_contents($repo_skin['css']);
//} else {
//  $repo_base_css = '';
//  $repo_skin_css = file_get_contents($repo_skin['css']);;
//}

// retrieve smartly customColors[] and other settings if instructed
if ($update_options['color'] || $update_options['settings']) {
  // get updated JSON from repo
  $repo_base_json = file_get_contents($repo_base['json_sandbox']); //json_dev
  $repo_base_json = json_decode($repo_base_json, true);

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
/*
} else {
  $repo_base_json = '';
  $repo_skin_json = '';
*/

// TODO: make available JSON settings checkbox granular, allowing for user to keep their background image, tile sizing, gap size, etc.
// update customColors[] (color templates) from repo if instructed.

/*
  if ($update_options['color']) {
    $inputJSON['customColors'] = $repo_base_json['customColors'];
  }
*/

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
}

// get existing smartly data from inputJSON, so long as it's in pos 0
if (is_json($inputJSON['tiles'][0]['templateExtra'])) {
  $smartly_data = json_decode($inputJSON['tiles'][0]['templateExtra'], true);
} else {
  $smartly_data = null;
}

// set up variables for automatically determining and setting grid row and column count
$calibrate_rows = 0;
$calibrate_cols = 0;

// if first time running, smartly tile won't exist so create it
// with null data to ensure tile array position is mirrored
// between smartly tiles and inputJSON tiles.
if ($inputJSON['tiles'][0]['template'] != "smartly") {

 // first time running

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

// build refreshed smartly data from tiles
foreach ($inputJSON['tiles'] as $pos => $tile) {
  // build smartly data for all tiles, excluding smartly data
  if (($tile['template'] != "smartly") && ($tile['device'] != "smartly_zoomy")) {
    $tile_data = array(); // reset tile_data
    $tile_data['id'] = $tile['id'];
    $tile_data['template'] = $tile['template'];
    $tile_data['pos'] = $pos;
    
/*
    // strip HE native "Custom Icon" data, as it breaks states.
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

    // if image or video, no title replacement is possible because it doesn't exist
    if ($tile['template'] == "images" | $tile['template'] == "video") { 
      // retrieve existing value of label
      $tile_data['label'] = $smartly_data[$tile['id']]['label'] ? $smartly_data[$tile['id']]['label'] : null;
      // add to smartly_css so it can build the css
//      $smartly_css['label'][$tile['id']]['label'] = $tile_data['label'];
    } else {
      // retrieve existing title_replacement
      $tile_data['title'] = $smartly_data[$tile['id']]['title'] ? $smartly_data[$tile['id']]['title'] : null;
      $tile_data['title_wrap'] = $smartly_data[$tile['id']]['title_wrap'] ? $smartly_data[$tile['id']]['title_wrap'] : null;

      // add to smartly_css so it can build the css
//      $smartly_css['title'][$tile['id']]['title'] = $tile_data['title'];
    }

    // refresh states for tile template type
    if (is_array($statelookup[$tile['template']])) {
      foreach ($statelookup[$tile['template']] as $statename => $stateclass) {
        // retrieve existing icon value for template state if it exists
        $tile_data['states'][$statename]['code'] = $smartly_data[$tile['id']]['states'][$statename]['code'] ? $smartly_data[$tile['id']]['states'][$statename]['code'] : null;
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

// @TODO why on earth am I splitting inputSDATA into separate variables instead of just writing null corrections to inputSDADA
// if updated smartlydata was passed, use it instead of the generated smartly data.
if ($inputSDATA['tiles']) { $tiles = $inputSDATA['tiles']; } 
if ($inputSDATA['settings']) { 
  $smartly_settings = $inputSDATA['settings']; 
} else {
  $smartly_settings = array('calibration' => array('devices' => null, 'devices_2col' => null));
}

/*
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

// @TODO why on earth am I splitting inputSDATA into separate variables instead of just writing null corrections to inputSDADA

// update smartly tile with new new smartly data
$inputJSON['tiles'][0]['templateExtra'] = json_encode(array("tiles" => $tiles, "settings" => $smartly_settings));

// set up css replacements so they are accessible globally.
$css_title_replacements = array();
$css_label = array();
$css_icon_replacements = array();

// parse existing customCSS, replacing base_css if instructed to
$smartly_css_parsed = smartly_parse_css($inputJSON['customCSS'], $smartly_css_delimiters, $repo_base_css, $repo_skin_css, $update_options);

// build CSS based on smartly data, base and user
$inputJSON['customCSS'] = smartly_build_css($tiles, $smartly_css_delimiters, $smartly_css_parsed['base'], $smartly_css_parsed['skin'], $smartly_css_parsed['user'], $smartly_settings);

// build and attach a zoomy helper tile to the tiles array
if ($update_options['zoomy']) {
  $zoomy_tile = smartly_zoomy((max(array_keys($tiles))+1), $inputJSON['colWidth'], $inputJSON['gridGap'], $inputJSON['cols']);
  array_push($inputJSON['tiles'], $zoomy_tile);
  $inputJSON['tiles'] = array_values($inputJSON['tiles']); // Hubitat Dashboard doesn't like indexed array for tiles.
}

// build the return array of json for smartly-helper form
$return_json = array("outputJSON" => json_encode($inputJSON), "smartlyDATA" => array('tiles' => $tiles, 'settings' => $smartly_settings));
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
      $css['user'] = $input_css_result[3];
    } else {
//    $css['auto'] = $input_css_result[1];
      $css['skin'] = $skin_css; // . "/* added */"; //'';
      $css['user'] = $input_css_result[2];
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
      $css['user'] = '';  
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

  // parse through all smartly data and build necessary "auto" CSS
  foreach ($smartly_tiles as $smart_id => $smart_data) {

    if ($smart_data['title'] != "") {

      // if title will fit on one line, no need to wrap the space allocated for the new title
      $title_wrap = $smart_data['title_wrap'] == true? "" : "white-space: nowrap;";

      // TODO: allow html instead of escaping all characters
      $title_replacement = addslashes($smart_data['title']);

      // using css optimizer downstream, redundant is fine
      $smartly_css['title'][] = <<<EOF

#tile-$smart_id .tile-title {
	visibility: hidden;
	$title_wrap
}

#tile-$smart_id .tile-title:after {
	content: "$title_replacement";
	visibility: visible;
	position: absolute;
	left: 0;
	padding: .5em .5em 3px .5em;
	width: 100%;
	top: 0;
}

EOF;

    } else {
      // even though we aren't doing a title replacement, 'icon nudge' should still
      // do something to help.
   
      if ($smart_data['title_wrap'] == true) {

        $smartly_css['icon'][] = <<<EOF

#tile-$smart_id .tile-primary i.material-icons {
    margin-top: -10px;
}

EOF;
        
      }
    }

    if ($smart_data['label'] != "") {

      // TODO: allow html instead of escaping all characters
      $label = addslashes($smart_data['label']);

      // using css optimizer downstream, redundant is fine
      $smartly_css['label'][] = <<<EOF

#tile-$smart_id .inset-auto:after {
    content: "$label";
    position: absolute;
    font-size: 1.5em;
    text-shadow: 2px 2px rgba(0,0,0,.7);
    padding: .5em;
    left: 0;
    width: 100%;
    text-align: left;
    line-height: 1em;
    background-color: rgba(0,0,0,.3);
    padding-left: .5em;
}

EOF;

    }

    if ($smart_data['states']) {
      foreach ($smart_data['states'] as $state_name => $state_data) { //$state_code) {
        $icon_code = $state_data['code'];
        $icon_class = $state_data['class'];

        if (strlen($state_data['code']) > 0) {

        $smartly_css['icon'][] = <<<EOF

#tile-$smart_id .tile-primary.$state_name i.material-icons {
    visibility: hidden;
}

#tile-$smart_id .tile-primary.$state_name i.material-icons:after {
    content: "\\$icon_code";
    font-family: "Material Design Icons" !important;
    visibility: hidden;
}

#tile-$smart_id .tile-primary.$state_name i.material-icons:before {
    content: "\\$icon_code";
    font-family: "Material Design Icons" !important;
    visibility: visible;
    position: absolute;
    left:0;
    right:0;
}

EOF;

        }
      }
    }
  }


  if ($settings['calibrations']['devices']) {

  
    $smartly_css['calibrations'][] = <<<EOF

EOF;
  }

  if ($settings['calibrations']['devices_2col']) {

    $smartly_css['calibrations'][] = <<<EOF



EOF;
  }


  // combine and optimize CSS

  $lb = "\r\n\r\n"; // line break for future use

  $optimize = new \CssOptimizer\Css\Optimizer;
  $optimized_css['title'] =  $optimize->optimizeCss(implode($lb, $smartly_css['title']));
  $optimized_css['label'] =  $optimize->optimizeCss(implode($lb, $smartly_css['label']));
  $optimized_css['icon'] =  $optimize->optimizeCss(implode($lb, $smartly_css['icon']));
  $optimized_css['calibrations'] = $optimize->optimizeCss(implode($lb, $smartly_css['calibrations']));

  $smartly_css_flat = array(
    $delimiters['base'],
    trim($base_css),
    $delimiters['skin'],
    trim($skin_css),
    $delimiters['auto'],
    $optimized_css['title'],
    $optimized_css['label'],
    $optimized_css['icon'],
    $delimiters['user'],
    $user_css
  );

  $smartly_css_flat = implode($lb, array_filter($smartly_css_flat));

  return $smartly_css_flat;

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
	
//	$gap = 15;
//	$colwidth = 135;
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
          'zoom' => ($screenwidth + 4) / $columns['seven'],
          'abszoom' => abs((($screenwidth + 4) / $columns['seven']) - 1) + 1
          ];
          break;
        }  elseif ($screenwidth == 1920) {
          $bestmatch = [
          'name' => 'nine',
          'width' => $columns['nine'],
          'zoom' => ($screenwidth + 4) / $columns['nine'],
          'abszoom' => abs((($screenwidth + 4) / $columns['nine']) - 1) + 1
          ];
          break;

        } else {
          // save the current best match, only if it's better than the last
          $bestmatch = [
          'name' => $column,
          'width' => $width,
          'zoom' => $zoom,
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

      $output['css'][] = "@media screen and (max-width:" . ($screenwidth + 4) . "px) and (min-width:" . $screenwidth . "px){.sw-" . $screenwidth . "{top: -" . $screenwidth ."em;}.zoomval_container{margin-top:-" . $screenwidth . "em;}.dashboard{zoom:" . $bestmatch['zoom'] . ";}}" . $lb;

      $output['html'][] = "<i style='top:" . $screenwidth . "em' class='sw-" . $screenwidth . "'>@media screen and (max-width: " . ($screenwidth + 4) . "px) and (min-width:" . $screenwidth . "px){.dashboard{zoom:" .  $bestmatch['zoom'] . ";}}</i>" . $lb;

    }
  }  

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


?>
