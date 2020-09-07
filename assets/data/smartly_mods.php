<?php

// TODO: pull from a separate 'mods' repo 

$mods_repo = array();

$mods_repo['layout'] = [
  'tiles' => [
    'basics' => [
      'remove_title',
      'title',
      'label',
      'unit',
      'numeric'
    ],
    'icons' => [
      'nudge',
      'icon'
    ],
    'mods' => [
      'zoomable',
      'buttonize',
      'text_alignment'
    ],
    'contrib' => [
      'tm',
      'Graph',
      'bpt',
      'bpt-history',
      'bpt-statusTile1'
    ],
    'color' => [
      'color_bg',
      'color_fg',
      'border_size',
      'border'
    ]
  ],
  'dashboard' => [
    'mods' => [
      'header',
      'hide_scrollbars'
    ],
    'calibration' => [
      'cal_devices',
      'cal_devices_2col',
      'zoomy'
    ]
/*
,
    'color' => [
      'color_temperature',
      'color_humidity'
    ]
*/
  ]
];



$mods_enabled = array();


// all dashboard mods always enabled
/*
$mods_enabled['dashboard']['header'] = true;
$mods_enabled['dashboard']['colorcoding']['temperature'] = true;
$mods_enabled['dashboard']['colorcoding']['humidity'] = true;
$mods_enabled['dashboard']['colorcoding']['battery'] = true;
*/


$mods_enabled['tiletype']['unit'] = ['attribute'];
$mods_enabled['tiletype']['numeric'] = ['attribute'];
$mods_enabled['tiletype']['text_alignment'] = ['texttile'];
$mods_enabled['tiletype']['buttonize'] = ['button', 'dashboard', 'momentary', 'presence', 'water'];


$mods_enabled['tiletype']['title'] = [
  'acceleration',
  'attribute',
  'battery',
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

$mods_enabled['tiletype']['remove_title'] = [
  'acceleration',
  'attribute',
  'battery',
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
  'window'
//'scene'
];

$mods_enabled['tiletype']['icon'] = [
  'acceleration',
  'attribute',
  'battery',
  'bulb',
  'bulb-color',
  'buttons',
  'carbon-monoxide',
//'clock',
//'clock-analog',
//'clock-date',
  'contact',
  'dashboard',
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
  'window'
//'scene'
];

$mods_enabled['tiletype']['zoomable'] = [
  'acceleration',
  'attribute',
  'battery',
  'bulb',
  'bulb-color',
  'buttons',
  'carbon-monoxide',
  'clock',
  'clock-analog',
  'clock-date',
  'contact',
  'dashboard',
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
  'music-player',
  'outlet',
  'presence',
  'relay',
  'shades',
  'shock',
  'smoke',
  'switches',
  'texttile',
  'thermostat',
  'valve',
  'video',
  'volume',
  'water',
  'window',
  'scene'
];

$mods_enabled['tiletype']['label'] = [
  'dashboard',
  'image',
  'video',
  'thermostat'
];

$mods_enabled['tiletype']['color_fg'] = [
  'acceleration',
  'attribute',
//  'battery',
//  'bulb',
//  'bulb-color',
  'buttons',
//  'carbon-monoxide',
  'clock',
  'clock-analog',
  'clock-date',
//  'contact',
  'dashboard',
//  'dimmer',
//  'door',
//  'door-control',
//  'fan',
//  'garage',
//  'garage-control',
  'illuminance',
  'energy',
  'temperature',
  'humidity',
//'image',
  'level-step',
//  'lock',
  'momentary',
//  'motion',
//  'multi',
  'music-player',
//  'outlet',
//  'presence',
  'relay',
//  'shades',
//  'shock',
//  'smoke',
//  'switches',
//  'thermostat',
  'texttile',
  'valve',
  'video',
  'volume',
//  'water',
//  'window',
  'scene'
];

$mods_enabled['tiletype']['color_bg'] = [
//  'acceleration',
  'attribute',
//  'battery',
//  'bulb',
//  'bulb-color',
  'buttons',
//  'carbon-monoxide',
  'clock',
  'clock-analog',
  'clock-date',
//  'contact',
  'dashboard',
//  'dimmer',
//  'door',
//  'door-control',
//  'fan',
//  'garage',
//  'garage-control',
  'illuminance',
  'energy',
  'temperature',
  'humidity',
  'image',
  'level-step',
//  'lock',
  'momentary',
//  'motion',
//  'multi',
  'music-player',
//  'outlet',
//  'presence',
  'relay',
//  'shades',
//  'shock',
//  'smoke',
//  'switches',
//  'thermostat',
  'texttile',
//  'valve',
  'video',
  'volume',
//  'water',
//  'window',
  'scene'
];


$mods_enabled['tiletype']['border'] = [
  'acceleration',
  'attribute',
  'battery',
  'bulb',
  'bulb-color',
  'buttons',
  'carbon-monoxide',
  'clock',
  'clock-analog',
  'clock-date',
  'contact',
  'dashboard',
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
  'music-player',
  'outlet',
  'presence',
  'relay',
  'shades',
  'shock',
  'smoke',
  'switches',
  'thermostat',
  'texttile',
  'valve',
  'video',
  'volume',
  'water',
  'window',
  'scene'
];


// MODS REPO, to be built in JSON, possibly using foler structure to hold CSS


$mods_repo['dashboard']['cal_devices']['label'] = "<b>CALIBRATE MY DASHBOARD</b> for the following devices:<br><small>FOR STOCK DEVICE DISPLAY DPI SETTINGS ONLY</small>";
$mods_repo['dashboard']['cal_devices']['text'] = "some additional help text";
$mods_repo['dashboard']['cal_devices']['type'] = 'tagsinput';

$mods_repo['dashboard']['cal_devices_2col']['label'] = "<b>FORCE DISPLAY 2 COLUMNS WIDE</b> <i>(when held vertically)</i><br><small>FOR STOCK DEVICE DISPLAY DPI SETTINGS ONLY</small>";
$mods_repo['dashboard']['cal_devices_2col']['text'] = "some additional help text";
$mods_repo['dashboard']['cal_devices_2col']['type'] = 'tagsinput';

$mods_repo['dashboard']['color_temperature']['label'] = "Enable value-based temperature tile color";
$mods_repo['dashboard']['color_temperature']['text']['default'] = "Change the color of this tile based on the temperature value.";
$mods_repo['dashboard']['color_temperature']['type'] = 'checkbox';
$mods_repo['dashboard']['color_temperature']['json']['path'] = '/somepath.json';
$mods_repo['dashboard']['color_temperature']['json']['method'] = 'overwrite';

$mods_repo['dashboard']['color_humidity']['label'] = "Enable value-based humidity tile color";
$mods_repo['dashboard']['color_humidity']['text']['default'] = "Change the color of this tile based on the humidity value.";
$mods_repo['dashboard']['color_humidity']['type'] = 'checkbox';
$mods_repo['dashboard']['color_humidity']['json']['path'] = '/somepath.json';
$mods_repo['dashboard']['color_humidity']['json']['method'] = 'overwrite';

$mods_repo['dashboard']['zoomy']['label'] = '<b>Add ZOOMY</b> <small style="color: #da4800;">(This checkbox will uncheck itself after update)</small>';
$mods_repo['dashboard']['zoomy']['text']['default'] = '<small>Temporarily add a calibration helper tile to generate the perfect css for alignment of columns to the edge of the screen on any device, any device display dpi setting.</small>';
$mods_repo['dashboard']['zoomy']['type'] = 'checkbox';

$mods_repo['dashboard']['hide_scrollbars']['label'] = 'Hide Scrollbars';
$mods_repo['dashboard']['hide_scrollbars']['text']['default'] = 'Regardless of whether there is overflow, scrollbars will be enabled but hidden.';
$mods_repo['dashboard']['hide_scrollbars']['type'] = 'checkbox';
$mods_repo['dashboard']['hide_scrollbars']['default']['css'] = <<<EOF
::-webkit-scrollbar {
  width: 0px;
  background: transparent;
}

.wrapper {
  -ms-overflow-style:none;
  scrollbar-width: none;
}
EOF;


$mods_repo['dashboard']['header']['label'] = "Header Theme";
$mods_repo['dashboard']['header']['type'] = 'select';
$mods_repo['dashboard']['header']['options'] = [
    "default" => "Default",
    "light_top" => "Light (top)",
    "light_bottom" => "Light (bottom)",
    "dark_top" => "Dark (top)",
    "dark_bottom" => "Dark (bottom)",
    "hidden" => "Hidden (touch top right)",
    "stealth_top_right" => "Stealth (top right)",
    "stealth_top_right_vertical" => "Stealth (top right - vertical)",
    "stealth_bottom_right" => "Stealth (bottom right)",
    "stealth_bottom_right_vertical" => "Stealth (bottom right - vertical)"
];
$mods_repo['dashboard']['header']['text'] = "Change the size and position and visibility of the dashboard header..";

// using value based tree because dashboard mods will include JSON mods, which can't simply use the value of the form in token replacement

$mods_repo['dashboard']['header']['value']['default']['css'] = <<<EOF
EOF;

$mods_repo['dashboard']['header']['value']['hidden']['css'] = <<<EOF
.dashboard div .header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    opacity: 1;
}

.wrapper {
    height: 100%;
    margin-top: 0;
    padding-top: [grid_gap]px;
}

.dashBack,
.dashName {
    display: none;
}

.dashboard div .header>.flex-auto.justify-end>span {
  display: none;
}

.dashboard div .header>.flex-auto.justify-end>span:last-child {
    position: absolute;
    z-index:99999;
    right: 0;
    top: 0;
    color: transparent;
    display: inline-block;
    height: [grid_gap]px;
    width: [grid_gap]px
}
EOF;
$mods_repo['dashboard']['header']['value']['light_bottom']['css'] = <<<EOF
.dashboard>div:first-child {
  height: 100vh;
  display: flex;
  flex-direction: column-reverse;
}

.wrapper {
  position: absolute;
  padding-top: [grid_gap]px;
  height: calc(100vh + [grid_gap]px);
  top: 0;
}

#grid-header {
  height: 60px;
  position: absolute !important;
  width: 100%;
  z-index: 2;
  color: black !important;
  background-color: rgba(255,255,255,.9);
  bottom: 0;
}

.header {
  z-index: 1;
  background-color: rgba(255,255,255,.9);
  height: 60px;
  position: absolute;
  width: 100%;
  color: black !important;
}

.header>.justify-end {
  padding-top: .5em;
}

.dashboard>div>.header {
    color: black;
}

smartly {
    height: calc(100% + [grid_gap_header]px + [grid_gap]px);
}

#grid-stack-scroller {
    position: absolute !important;
    height: 100vh !important;
    padding-top: [grid_gap]px;
}

#modal-1 div[role=dialog] {
    height: 100vh;
}
EOF;

$mods_repo['dashboard']['header']['value']['dark_bottom']['css'] = <<<EOF
.dashboard>div:first-child {
  height: 100vh;
  display: flex;
  flex-direction: column-reverse;
}

.wrapper {
  position: absolute;
  padding-top: [grid_gap]px;
  height: calc(100vh + [grid_gap]px);
  top: 0;
}

#grid-header {
  height: 60px;
  position: absolute !important;
  width: 100%;
  z-index: 2;
  color: white !important;
  background-color: rgba(0,0,0,.9);
  bottom: 0;
}

.header {
  z-index: 1;
  background-color: rgba(0,0,0,.9);
  height: 60px;
  position: absolute;
  width: 100%;
  color: white !important;
}

.header>.justify-end {
  padding-top: .5em;
}

.dashboard>div>.header {
    color: white;
}

smartly {
    height: calc(100% + [grid_gap_header]px + [grid_gap]px);
}

#grid-stack-scroller {
    position: absolute !important;
    height: 100vh !important;
    padding-top: [grid_gap]px;
}

#modal-1 div[role=dialog] {
    height: 100vh;
}

EOF;

$mods_repo['dashboard']['header']['value']['light_top']['css'] = <<<EOF
.dashboard>div:first-child {
  height: 100vh;
}

#grid-stack-scroller {
  height: 100vh !important;
}

#grid-stack-scroller,
.wrapper {
  position: absolute !important;
  padding-top: [grid_gap_header]px;
  height: calc(100vh + [grid_gap]px) !important;
}

#grid-header {
  height: 60px;
  position: absolute !important;
  width: 100%;
  z-index: 2;
  color: black !important;
  background-color: rgba(255,255,255,.9);
}

.header {
  z-index: 1;
  background-color: rgba(255,255,255,.9);
  height: 60px;
  position: absolute;
  width: 100%;
}

.header>.justify-end {
  padding-top: .5em;
}

.dashboard>div>.header {
    color: black;
}

smartly {
    height: calc(100% + [grid_gap]px + [grid_gap]px);
}
EOF;

$mods_repo['dashboard']['header']['value']['dark_top']['css'] = <<<EOF
.dashboard>div:first-child {
  height: 100vh;
}

#grid-stack-scroller {
  height: 100vh !important;
}

#grid-stack-scroller,
.wrapper {
  position: absolute !important;
  padding-top: [grid_gap_header]px;
  height: calc(100vh + [grid_gap]px) !important;
}

#grid-header {
  height: 60px;
  position: absolute !important;
  width: 100%;
  z-index: 2;
  color: white !important;
  background-color: rgba(0,0,0,.9);
}

.header {
  z-index: 1;
  background-color: rgba(0,0,0,.9);
  height: 60px;
  position: absolute;
  width: 100%;
}

.header>.justify-end {
  padding-top: .5em;
}

.dashboard>div>.header {
    color: white;
}

smartly {
    height: calc(100% + [grid_gap]px + [grid_gap]px);
}

#close-modal-btn {
  color: #28a745;
}
EOF;

$mods_repo['dashboard']['header']['value']['stealth_top_right']['css'] = <<<EOF
.dashboard div .header {
    position: fixed;
    top: 0em;
    right: 1em;
    z-index: 2;
    opacity: .2;
    zoom: .6;
    -moz-transform:scale(.6);
}

.wrapper {
    height: 100%;
    margin-top: 0;
    padding-top: 1em;
}

.dashBack,
.dashName {
    display: none;
}

.dashboard div .header>.flex-auto.justify-end,
.dashboard div .header>.flex-auto.justify-end>.flex {
    display: inline-block;
}
EOF;
$mods_repo['dashboard']['header']['value']['stealth_top_right_vertical']['css'] = <<<EOF
.dashboard div .header {
    position: fixed;
    top: 0em;
    right: 1em;
    z-index: 2;
    opacity: .2;
    zoom: .6;
    -moz-transform:scale(.6);
}

.wrapper {
    height: 100%;
    margin-top: 0;
    padding-top: 1em;
}

.dashBack,
.dashName {
    display: none;
}

.dashboard div .header>.flex-auto.justify-end,
.dashboard div .header>.flex-auto.justify-end>.flex {
    display: block;
}
EOF;
$mods_repo['dashboard']['header']['value']['stealth_bottom_right']['css'] = <<<EOF
.dashboard div .header {
    position: fixed;
    bottom: 0;
    right: 1em;
    z-index: 2;
    opacity: .2;
    zoom: .6;
    -moz-transform:scale(.6);
}

.wrapper {
    height: 100%;
    margin-top: 0;
    padding-top: 1em;
}

.dashBack,
.dashName {
    display: none;
}

.dashboard div .header>.flex-auto.justify-end,
.dashboard div .header>.flex-auto.justify-end>.flex {
    display: inline-block;
}
EOF;
$mods_repo['dashboard']['header']['value']['stealth_bottom_right_vertical']['css'] = <<<EOF
.dashboard div .header {
    position: fixed;
    bottom: 0;
    right: 1em;
    z-index: 2;
    opacity: .2;
    zoom: .6;
    -moz-transform:scale(.6);
}

.wrapper {
    height: 100%;
    margin-top: 0;
    padding-top: 1em;
}

.dashBack,
.dashName {
    display: none;
}

.dashboard div .header>.flex-auto.justify-end,
.dashboard div .header>.flex-auto.justify-end>.flex {
    display: block;
}
EOF;


// TITLE REPLACEMENT

$mods_repo['tiletype']['title']['label'] = "Title replacement";
//$mods_repo['tiletype']['title']['text']['default'] = "";
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



$mods_repo['tiletype']['icon']['label'] = "icon replace/add";
$mods_repo['tiletype']['icon']['type'] = 'fieldset';

$mods_repo['tiletype']['icon']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary i.material-icons {
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
#tile-[tile_id] .tile-primary i.material-icons.[class_stock] {
    visibility: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons.[class_stock]:after {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons.[class_stock]:before {
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

$mods_repo['tiletype']['icon']['css']['music-player'] = <<<EOF
#tile-[tile_id] .tile-primary i.material-icons {
    visibility: hidden;
    width: 1em;
    height: 1em;
    overflow: hidden;
}

#tile-[tile_id] .tile-primary i.material-icons.[class]:before {
    content: "\\[value]";
    font-family: "Material Design Icons" !important;
    visibility: visible;
}

EOF;





// 3rd party CSS patching

// BPT General
$mods_repo['contrib']['bpt']['section-title'] = "BPT General";
$mods_repo['contrib']['bpt']['label'] = "BPT Generic CSS Patching";
$mods_repo['contrib']['bpt']['type'] = 'checkbox';
$mods_repo['contrib']['bpt']['default'] = true;
$mods_repo['contrib']['bpt']['text']['default'] = "Enable some basic CSS patches for BPT tiles to look good in smartly..";
$mods_repo['contrib']['bpt']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-contents {
   height: calc(90%);
}

#tile-[tile_id] .tile-primary {
   padding-bottom: 0;
}
EOF;


// BPT Weather Dot Gov

$mods_repo['contrib']['bpt-currentDataTile']['css'] = <<<EOF
#tile-[tile_id] .tile-title {
    position: absolute;
    bottom: .5em;
    left: .5em;
}

#tile-[tile_id] .tile-primary tbody:nth-child(1) td small {
    position: absolute;
    right: 3.5em;
}

#tile-[tile_id] .tile-primary {
    line-height: 1.4em;
}
EOF;



// BPT Life360
$mods_repo['contrib']['bpt-statusTile1']['section-title'] = "BPT Life360 Status";
$mods_repo['contrib']['bpt-statusTile1']['label'] = "Enable";
$mods_repo['contrib']['bpt-statusTile1']['type'] = 'checkbox';
$mods_repo['contrib']['bpt-statusTile1']['default'] = true;
$mods_repo['contrib']['bpt-statusTile1']['text']['default'] = "Enable Life360 Status CSS Patching";
$mods_repo['contrib']['bpt-statusTile1']['css']['default'] = <<<EOF
#tile-[tile_id] table {
  border-collapse: collapse;
}

#tile-[tile_id] .tile-contents {
  padding: 0;
}

#tile-[tile_id] .tile-title {
position: absolute;
  bottom: .75em;
  right: 1.25em;
  width: auto;
  text-align: right;
  opacity: .5;
}

#tile-[tile_id] tr {
    display: flex;
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(2) {
  position: absolute;
  top: 0;
  right: 0;
  padding: .5em 0 .25em .5em;
  background-color: rgba(255,255,255,.7);
  border-radius: 0 0 0 15px;
  color: rgba(0,0,0,0.7);
  width: unset !important;
  padding-top: 3em;
  font-size: 14px !important;
  margin-right: -1em;
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(1) {
  text-align: left;
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td:nth-child(1) {
  text-align: left;
  width: 135px !important;
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td:nth-child(2) {
  vertical-align: top;
  padding: .25em .5em;
  text-align: left;
  vertical-align: top;
}

#tile-[tile_id] .tile-primary {
  line-height: 1.4em;
  padding: 0;
  vertical-align: top;
}

#tile-[tile_id] .tile-primary>div {
  overflow: hidden !important;
  height: unset !important;
  margin: 0;
}

#tile-[tile_id] .tile-primary img {
  text-align: left;
  margin-top: -1px;
  margin-left: -1px;
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(2):after {
  content: " ";
  background-image: url(https://hubitat.ezeek.us/smartly-base/assets/images/logo_life360_75px.png);
  width: 100px;
  height: 3em;
  display: block;
  position: absolute;
  top: 0;
  margin-top: .5em;
  background-repeat: no-repeat;
  background-position: .25em 0;
}
EOF;

$mods_repo['contrib']['bpt-statusTile1']['modifier']['theme']['label'] = "Theme";
$mods_repo['contrib']['bpt-statusTile1']['modifier']['theme']['type'] = 'select';
$mods_repo['contrib']['bpt-statusTile1']['modifier']['theme']['options'] = [
    "default" => "Default",
    "light" => "Light",
    "dark" => "Dark"
];
$mods_repo['contrib']['bpt-statusTile1']['modifier']['theme']['text']['default'] = 'Select a color theme.';
$mods_repo['contrib']['bpt-statusTile1']['modifier']['theme']['css']['light'] = <<<EOF
#tile-[tile_id] {
    background-color: rgba(255,255,255,.8);
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(2) {
    background-color: white;
}
EOF;

$mods_repo['contrib']['bpt-statusTile1']['modifier']['theme']['css']['dark'] = <<<EOF
#tile-[tile_id] {
    background-color: rgba(0,0,0,.8);
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(2) {
    background-color: white;
}
EOF;

$mods_repo['contrib']['bpt-statusTile1']['modifier']['logo']['label'] = "Remove Logo";
$mods_repo['contrib']['bpt-statusTile1']['modifier']['logo']['type'] = 'checkbox';
//$mods_repo['contrib']['bpt-history']['modifier']['logo']['default'] = true;
$mods_repo['contrib']['bpt-statusTile1']['modifier']['logo']['text']['default'] = 'Check to remove the Life360 logo.';
$mods_repo['contrib']['bpt-statusTile1']['modifier']['logo']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(2):after {
    display: none;
}

#tile-[tile_id] .tile-primary tbody tr:nth-child(1) td p:nth-child(2) {
    padding-right: .25em;
    padding-top: .5em;
}
EOF;


// BPT Tilemaster
$mods_repo['contrib']['tm']['section-title'] = "BPT Tile Master";
$mods_repo['contrib']['tm']['label'] = "BPT Tile Master CSS Patching";
$mods_repo['contrib']['tm']['type'] = 'checkbox';
$mods_repo['contrib']['tm']['default'] = true;
$mods_repo['contrib']['tm']['text']['default'] = "Enable some basic CSS patches for BPT Tile Master tiles to look good in smartly..";
$mods_repo['contrib']['tm']['css'] = <<<EOF
#tile-[tile_id] {
   border: 0px none;
   content: "3RD-TM";
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

// HubiGraphs General
$mods_repo['contrib']['Graph']['section-title'] = "HubiGraph General";
$mods_repo['contrib']['Graph']['label'] = "HubiGraph Generic CSS Patching";
$mods_repo['contrib']['Graph']['type'] = 'checkbox';
$mods_repo['contrib']['Graph']['default'] = true;
$mods_repo['contrib']['Graph']['text']['default'] = "Enable some basic CSS patches for HubiGraph tiles to look good in smartly..";
$mods_repo['contrib']['Graph']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary,
#tile-[tile_id] .tile-contents {
    height: 100%;
}

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
  z-index: 1;
}

EOF;



$mods_repo['contrib']['myFrame']['css'] = <<<EOF
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
    font-size: [fontsize_calc_lg] !important;
}

EOF;

$mods_repo['tiletype']['text_alignment']['label'] = "Text Alignment";
$mods_repo['tiletype']['text_alignment']['type'] = 'select';
$mods_repo['tiletype']['text_alignment']['options'] = [
    'left' => 'Left',
    'center' => 'Center',
    'right' => 'Right'
];
$mods_repo['tiletype']['text_alignment']['text']['default'] = "For text tiles, you might want to change the horizontal alignment.";
$mods_repo['tiletype']['text_alignment']['css']['default'] = <<<EOF
#tile-[tile_id].text-tile .justify-center, 
#tile-[tile_id].text-tile .text-center {
    text-align: [value] !important;
    justify-content: [value];
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

$mods_repo['tiletype']['zoomable']['label'] = "Zoomable";
$mods_repo['tiletype']['zoomable']['type'] = 'select';
$mods_repo['tiletype']['zoomable']['options'] = ["default" => "1x", "1.5" => "1.5x", "2.0" => "2x", "2.5" => "2.5x", "3.0" => "3x", "3.5" => "3.5x", "4.0" => "4x"];
$mods_repo['tiletype']['zoomable']['text']['default'] = 'Make everything within the tile x times larger.';
$mods_repo['tiletype']['zoomable']['text']['thermostat'] = 'Make everything within the thermostat tile x times larger.';
$mods_repo['tiletype']['zoomable']['text']['attribute'] = 'Make the content of this tile x times larger while keeping the title the same size.';
$mods_repo['tiletype']['zoomable']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-contents {
    zoom: [value];
    -moz-transform:scale([value]);
}
EOF;

$mods_repo['tiletype']['zoomable']['css']['music-player'] = <<<EOF
#tile-[tile_id] {
    zoom: [value];
     -moz-transform:scale([value]);
}

#tile-[tile_id] .music-player .material-icons {
    zoom: [value];
     -moz-transform:scale([value]);
}

#tile-[tile_id] .tile-primary>.music-player {
    margin-top: 0;
}

EOF;

$mods_repo['tiletype']['zoomable']['css']['thermostat'] = <<<EOF
#tile-[tile_id]>.absolute.bottom-0 {
    zoom: [value];
     -moz-transform:scale([value]);
}

#tile-[tile_id]>.flex.items-stretch {
    zoom: [value];
     -moz-transform:scale([value]);
    padding-top: [padding_calc];
}

EOF;
$mods_repo['tiletype']['zoomable']['css']['clock'] = <<<EOF
#tile-[tile_id] .tile-contents {
    zoom: [value];
     -moz-transform:scale([value]);
    line-height: 1em;
    height: calc(100%);
}

#tile-[tile_id] .tile-primary {
    padding-bottom: calc([padding_adjust]%);
}

EOF;
$mods_repo['tiletype']['zoomable']['css']['attribute'] = <<<EOF
#tile-[tile_id] .tile-primary {
    zoom: [value];
     -moz-transform:scale([value]);
}

EOF;


$mods_repo['tiletype']['buttonize']['label'] = "Buttonize!";
$mods_repo['tiletype']['buttonize']['type'] = 'checkbox';
$mods_repo['tiletype']['buttonize']['modifier']['icon_only']['label'] = "Icon only";
$mods_repo['tiletype']['buttonize']['modifier']['icon_only']['type'] = 'checkbox';
$mods_repo['tiletype']['buttonize']['modifier']['icon_only']['text']['default'] = 'Hide the text, only show the icon.';
$mods_repo['tiletype']['buttonize']['modifier']['icon_only']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-title, 
#tile-[tile_id] .tile-primary>div {
    visibility: hidden;
}
EOF;

$mods_repo['tiletype']['buttonize']['modifier']['icon_only']['css']['dashboard'] = <<<EOF
#tile-[tile_id] .tile-primary {
    font-size: .25em !important;
    visibility: hidden;
}

#tile-[tile_id] .tile-primary:before {
    font-size: 60px;
}
EOF;

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
    line-height: 1.2em;
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
    display: none; 
}

EOF;

$mods_repo['tiletype']['color_fg']['label'] = "Text Color";
$mods_repo['tiletype']['color_fg']['text']['default'] = 'The tile text and icon color (overrides state colors).  Format: rgba(x,x,x,x).';
$mods_repo['tiletype']['color_fg']['type'] = 'color';
$mods_repo['tiletype']['color_fg']['placeholder'] = 'rgba(x,x,x,x)';
$mods_repo['tiletype']['color_fg']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-primary,
#tile-[tile_id] .tile-contents,
#tile-[tile_id] {
  color: [value] !important;
}
EOF;

$mods_repo['tiletype']['color_bg']['label'] = "Background Color";
$mods_repo['tiletype']['color_bg']['text']['default'] = 'The tile background color (overrides state colors).  Format: rgba(x,x,x,x).';
$mods_repo['tiletype']['color_bg']['type'] = 'color';
$mods_repo['tiletype']['color_bg']['css']['default'] = <<<EOF
#tile-[tile_id] {
  background-color: [value] !important;
}
EOF;

$mods_repo['tiletype']['border']['label'] = "Border Color";
$mods_repo['tiletype']['border']['text']['default'] = 'The tile border color.  FormatL: rgba(x,x,x,x).';
$mods_repo['tiletype']['border']['type'] = 'color';
$mods_repo['tiletype']['border']['css']['default'] = <<<EOF
#tile-[tile_id] {
  border-color: [value] !important;
  border-style: solid !important;
  border-width: 1px;
}
EOF;


$mods_repo['tiletype']['border']['modifier']['border_size']['label'] = "Border Size";
$mods_repo['tiletype']['border']['modifier']['border_size']['text']['default'] = 'The width (in pixels) of the tile border.  Border Color required to see it.';
$mods_repo['tiletype']['border']['modifier']['border_size']['type'] = 'select';
$mods_repo['tiletype']['border']['modifier']['border_size']['options'] = ['1', '2', '3', '4'];
$mods_repo['tiletype']['border']['modifier']['border_size']['css']['default'] = <<<EOF
#tile-[tile_id] {
  border-width: [value]px !important;
}
EOF;

$mods_repo['tiletype']['compactify']['label'] = "Make Compact";
$mods_repo['tiletype']['compactify']['type'] = 'select';
$mods_repo['tiletype']['compactify']['default'] = 'half_height_middle';
$mods_repo['tiletype']['compactify']['options'] = [
    "Half Height - middle" => "half_height_middle",
    "Half Height - bottom" => "half_height_bottom",
    "Half Height - top" => "half_height_top",
    "3/4 width - left" => "three_quarter_width_left",
    "3/4 width - right" => "three_quarter_width_right",
    "3/4 width - middle" => "three_quarter_width_middle"
];
$mods_repo['tiletype']['compactify']['text']['default'] = 'Warning: may not work well with Buttonize mod.';
$mods_repo['tiletype']['compactify']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-contents {
    zoom: [value];
     -moz-transform:scale([value]);
}

EOF;

$mods_repo['tiletype']['remove_title']['label'] = "Remove title";
$mods_repo['tiletype']['remove_title']['text']['default'] = "Remove the title and attempt to center the contents of the tile vertically.";
$mods_repo['tiletype']['remove_title']['type'] = 'checkbox';
$mods_repo['tiletype']['remove_title']['css']['default'] = <<<EOF
#tile-[tile_id] .tile-title {
    display: none;
}  

#tile-[tile_id] .tile-contents {
    height: 100%;
}

#tile-[tile_id] .tile-primary {
    padding-bottom: 0px;
}

EOF;

?>
