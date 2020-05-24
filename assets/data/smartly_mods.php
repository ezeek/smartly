<?php

// TODO: pull from a separate 'mods' repo 

$mods_enabled = array();

$mods_enabled['dashboard']['header'] = true;
$mods_enabled['dashboard']['colorcoding']['temperature'] = true;
$mods_enabled['dashboard']['colorcoding']['humidity'] = true;
$mods_enabled['dashboard']['colorcoding']['battery'] = true;

$mods_enabled['tiletype']['zoomable'] = ['clock', 'clock-analog', 'clock-date', 'thermostat'];
$mods_enabled['tiletype']['unit'] = ['attribute'];
$mods_enabled['tiletype']['numeric'] = ['attribute'];
$mods_enabled['tiletype']['buttonize'] = ['button', 'dashboard', 'momentary', 'presence'];

$mods_enabled['tiletype']['title'] = [
  'acceleration',
  'attribute',
  'bulb',
  'bulb-color',
  'buttons',
  'carbon-monoxide',
//'clock',
//'clock-analog',
//'clock-date',
  'contact',
//'dashboard',
  'dimmer',
  'door',
  'door-control',
  'fan',
  'garage',
  'garage-control',
  'illuminance',
  'energy',
  'temperature',
  'humidity',
//'image',
  'level-step',
  'lock',
  'momentary',
  'motion',
  'multi',
  'music-player',
  'outlet',
  'presence',
  'relay',
  'shades',
  'shock',
  'smoke',
  'switches',
//'thermostat',
  'valve',
//'video',
  'volume',
  'water',
  'window',
  'scene'
];

$mods_enabled['tiletype']['nudge'] = [
  'acceleration',
  'attribute',
  'bulb',
  'bulb-color',
  'buttons',
  'carbon-monoxide',
//'clock',
//'clock-analog',
//'clock-date',
  'contact',
//'dashboard',
  'dimmer',
  'door',
  'door-control',
  'fan',
  'garage',
  'garage-control',
  'illuminance',
  'energy',
  'temperature',
  'humidity',
  'image',
  'level-step',
  'lock',
  'momentary',
  'motion',
  'multi',
//  'music-player',
  'outlet',
  'presence',
  'relay',
  'shades',
  'shock',
  'smoke',
  'switches',
//'thermostat',
  'valve',
  'video',
//  'volume',
  'water',
  'window',
  'scene'
];

$mods_enabled['tiletype']['label'] = [
  'image',
  'video',
  'thermostat'
];



$mods_layout = [
  'tiles' => [



  ]
];


// MODS REPO, to be built in JSON, possibly using foler structure to hold CSS

$mods_repo = array();

$mods_repo['dashboard']['mods']['colorcoding']= '';


// TITLE REPLACEMENT

$mods_repo['tiletype']['title']['label'] = "Title replacement";
$mods_repo['tiletype']['title']['type'] = 'textbox';
$mods_repo['tiletype']['title']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-title {
        visibility: hidden;
        white-space: nowrap;
}

#tile-[tile_id] .tile-title:after {
        content: "[value]";
        visibility: visible;
        position: absolute;
        left: 0;
        padding: .5em .5em 3px .5em;
        width: 100%;
        top: 0;
}

EOF;

$mods_repo['tiletype']['title']['css']['dashboard'] = <<<EOF
#tile-[tile_id] .tile-primary {
        font-size: 0 !important;
        color: transparent;
}

#tile-[tile_id] .tile-primary:after {
        color: black;
        content: "[value]";
        margin-left: 5px;
        font-size: [fontsize_calc];
}

#tile-[tile_id] .tile-primary:before {
        color: black;
        font-size: [fontsize_calc];
}

EOF;


// LABEL ADDITION

$mods_repo['tiletype']['label']['label'] = "Add/Replace Label";
$mods_repo['tiletype']['label']['type'] = 'textbox';
$mods_repo['tiletype']['label']['type'] = 'For image and video tiles, this will add a highly visible label. For others this will replace the existing label.';
$mods_repo['tiletype']['label']['css']['default'] = <<<EOF
#tile-[tile_id] .inset-auto:after {
    content: "[value]";
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
$mods_repo['tiletype']['label']['css']['thermostat'] = <<<EOF
#tile-[tile_id]>.absolute.bottom-0 {
    visibility: hidden;
}

#tile-[tile_id]>.absolute.bottom-0:after {
    visibility: visible;
    content: "[value]";
    position: absolute;
    left: 0;
    white-space: nowrap;
    width: 100%;
    text-align: center;
}

EOF;



// NUDGE

$mods_repo['tiletype']['nudge']['label'] = "Nudge";
$mods_repo['tiletype']['nudge']['type'] = 'checkbox';
$mods_repo['tiletype']['nudge']['text']['default'] = 'Nudge the icon slightly to give it more space..';
$mods_repo['tiletype']['nudge']['text']['attribute'] = 'Nudge the icon away from the value slightly to give it more space..';

$mods_repo['tiletype']['nudge']['css']['fixup']['attribute']['icon'] = 'margin-right: 5px;';
$mods_repo['tiletype']['nudge']['css']['fixup']['dashboard']['icon'] = 'margin-right: 5px;';
$mods_repo['tiletype']['nudge']['css']['fixup']['temperature']['icon'] = 'margin-right: 0px;';
$mods_repo['tiletype']['nudge']['css']['fixup']['humidity']['icon'] = 'margin-right: 0px;';
$mods_repo['tiletype']['nudge']['css']['fixup']['energy']['icon'] = 'margin-right: 0px;';
$mods_repo['tiletype']['nudge']['css']['fixup']['illuminance']['icon'] = 'margin-right: 0px;';
$mods_repo['tiletype']['nudge']['css']['fixup']['default']['title'] = 'white-space: unset;';

$mods_repo['tiletype']['nudge']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary i.material-icons {
    padding-top: 5px;
    margin-bottom: 0px;
}

#tile-[tile_id] .tile-title {
        white-space: unset;
}
EOF;


$mods_repo['tiletype']['nudge']['css']['illuminance'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
        margin-right: 0px;
}
EOF;

$mods_repo['tiletype']['nudge']['css']['attribute'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
        margin-right: 5px;
}
EOF;




$mods_repo['tiletype']['icon']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary. i.material-icons {
    visibility: hidden;
}

#tile-[tile_id] .tile-primary.[state] i.material-icons:after {
    content: "\\$[value]";
    font-family: "Material Design Icons" !important;
    visibility: hidden;
}

#tile-[tile_id] .tile-primary.[state] i.material-icons:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: visible;
    position: absolute;
    left:0;
    right:0;
}

EOF;


$mods_repo['tiletype']['icon']['css']['bulb-color'] = <<<EOF
#tile-[tile_id] .tile-primary i.material-icons.[class] {
    visibility: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons.[class]:after {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons.[class]:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: visible;
    position: absolute;
    left:0;
    right:0;
}

EOF;

$mods_repo['tiletype']['icon']['css']['buttons'] = <<<EOF
#tile-[tile_id] .tile-primary i.material-icons {
    visibility: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons:after {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: visible;
    position: absolute;
    left:0;
    right:0;
}

EOF;

$mods_repo['tiletype']['icon']['css']['temperature'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    opacity: .7;
    display: inline-block;
    [fixup-nudge]
}
EOF;

$mods_repo['tiletype']['icon']['css']['humidity'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    opacity: .7;
    display: inline-block;
    [fixup-nudge]
}
EOF;

$mods_repo['tiletype']['icon']['css']['energy'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    opacity: .7;
    display: inline-block;
    [fixup-nudge]
}
EOF;

$mods_repo['tiletype']['icon']['css']['illuminance'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    opacity: .7;
    display: inline-block;
    [fixup-nudge]
}
EOF;

$mods_repo['tiletype']['icon']['css']['dashboard'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    opacity: .7;
    display: inline-block;
    [fixup-nudge]
}
EOF;

$mods_repo['tiletype']['icon']['css']['attribute'] = <<<EOF
#tile-[tile_id] .tile-primary:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    opacity: .7;
    display: inline-block;
    [fixup-nudge]
}
EOF;



$mods_repo['3rdparty']['bpt']['css'] = <<<EOF
#tile-[tile_id] .tile-contents {
   height: calc(90%);
}

#tile-[tile_id] .tile-primary {
   padding-bottom: 0;
}
EOF;
$mods_repo['3rdparty']['tm']['css'] = <<<EOF
#tile-[tile_id] {
   border: 0px none;
}

#tile-[tile_id] .tile-contents {
   height: calc(100%);
}

#tile-[tile_id] .tile-contents,
#tile-[tile_id] .tile-primary,
#tile-[tile_id] .tile-primary>div {
   margin: 0;
   padding: 0;
}

#tile-[tile_id] .tile-title {
   position: absolute;
   z-index: 9;
   padding: .25em;
   width: 100%;
   text-align: center;
   opacity: .5;
   font-weight: normal;
   font-size: .9em;
}

EOF;
$mods_repo['3rdparty']['Graph']['css'] = <<<EOF
#tile-[tile_id] {
   border: 0px none;
}

#tile-[tile_id] .tile-contents {
   height: calc(100%);
}

#tile-[tile_id] .tile-contents,
#tile-[tile_id] .tile-primary,
#tile-[tile_id] .tile-primary>div {
   margin: 0;
   padding: 0;
}

#tile-[tile_id] .tile-title {
   position: absolute;
   z-index: 9;
   padding: .25em;
   width: 100%;
   text-align: center;
   opacity: .5;
   font-weight: normal;
   font-size: .9em;
}

EOF;
$mods_repo['3rdparty']['myFrame']['css'] = <<<EOF
#tile-[tile_id] {
   border: 0px none;
}

#tile-[tile_id] .tile-contents {
   height: calc(100%);
}

#tile-[tile_id] .tile-contents,
#tile-[tile_id] .tile-primary,
#tile-[tile_id] .tile-primary>div {
   margin: 0;
   padding: 0;
}

#tile-[tile_id] .tile-title {
   position: absolute;
   z-index: 9;
   padding: .25em;
   width: 100%;
   text-align: center;
   opacity: .5;
   font-weight: normal;
   font-size: .9em;
}

EOF;









$mods_repo['tiletype']['unit']['label'] = "Add Custom Unit text";
$mods_repo['tiletype']['unit']['text']['default'] = "For attribute tiles, this will add your custom unit label immediately after the tile value.";
$mods_repo['tiletype']['unit']['type'] = 'textbox';
$mods_repo['tiletype']['unit']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary:after {
    content: " [value]";
    font-size: 50%;
}
EOF;




$mods_repo['tiletype']['numeric']['label'] = "Increase font size";
$mods_repo['tiletype']['numeric']['text']['default'] = "This is normally used to increase attribute tile font size to match temperature, humidity and other numeric-based tile types.";
$mods_repo['tiletype']['numeric']['type'] = 'checkbox';
$mods_repo['tiletype']['numeric']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary {
    font-size: [fontsize_calc_lg !important;
}

EOF;



$mods_repo['tiletype']['title_color']['label'] = "Title Color";
$mods_repo['tiletype']['title_color']['type'] = 'select';
$mods_repo['tiletype']['title_color']['options'] = ['white', 'black'];
$mods_repo['tiletype']['title_color']['text']['default'] = "For some tiles, you'll want to change the title color to make it more visible.";
$mods_repo['tiletype']['title_color']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-title {
  color: [value];
}
EOF;

$mods_repo['tiletype']['zoomable']['label'] = "Largify";
$mods_repo['tiletype']['zoomable']['type'] = 'select';
$mods_repo['tiletype']['zoomable']['options'] = [1, 1.5, 2, 2.5, 3, 3.5, 4];
$mods_repo['tiletype']['zoomable']['text']['default'] = 'Make everything within the tile x times larger.';
$mods_repo['tiletype']['zoomable']['text']['thermostat'] = 'Make everything within the thermostat tile x times larger.';
$mods_repo['tiletype']['zoomable']['text']['attribute'] = 'Make the content of this tile x times larger while keeping the title the same size.';
$mods_repo['tiletype']['zoomable']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-contents {
    zoom: [value];
}

EOF;
$mods_repo['tiletype']['zoomable']['css']['thermostat'] = <<<EOF
#tile-[tile_id]>.absolute.bottom-0 {
    zoom: [value];
}

#tile-[tile_id]>.flex.items-stretch {
    zoom: [value];
    padding-top: [padding-calc];
}

EOF;
$mods_repo['tiletype']['zoomable']['css']['clock'] = <<<EOF
#tile-[tile_id] .tile-contents {
    zoom: [value];
    line-height: 1em;
    height: calc(100%);
}

EOF;
$mods_repo['tiletype']['zoomable']['css']['attribute'] = <<<EOF
#tile-[tile_id] .tile-primary {
    zoom: [value];
    line-height: 0;
}

EOF;


$mods_repo['tiletype']['buttonize']['label'] = "Buttonize!";
$mods_repo['tiletype']['buttonize']['type'] = 'checkbox';
$mods_repo['tiletype']['buttonize']['text']['default'] = 'Change the layout of this tile to be more button-like.';
$mods_repo['tiletype']['buttonize']['text']['dashboard'] = 'Change the layout of this Dashboard Link to be a stacked large icon and small text.';
$mods_repo['tiletype']['buttonize']['css']['default'] = <<<EOF
#tile-[tile_id] .material-icons::before,
#tile-[tile_id] .material-icons::after {
  font-size: 250%;
}
EOF;
$mods_repo['tiletype']['buttonize']['css']['dashboard'] = <<<EOF
#tile-[tile_id] {
    height: 100%;
}

#tile-[tile_id] .tile-primary {
    /* visibility: hidden; */
}

#tile-[tile_id] .tile-primary:before {
    display: block;
    line-height: 1.2em;
    font-size: 40px;
    visibility: visible;
}

#tile-[tile_id] .tile-primary:after {
    font-size: 18px;
    margin-left: 0px;
    line-height: 1.2em;
    /* display: none; */
}

EOF;


?>
