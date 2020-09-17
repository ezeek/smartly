<?php

$statelookup = [
    'acceleration' => [
        'active' => 'he-acceleration_active',
        'inactive' => 'he-acceleration_inactive',
    ],
    'attribute' => [
	'default' => ''
    ],
    'bulb' => [
        'off' => 'he-bulb_off', 
        'on' => 'he-bulb_on',
    ],
    'bulb-color' => [
        'off' => 'he-bulb_off', 
        'on' => 'he-bulb_on',
    ],
    'buttons' => [
        'default' => 'he-tap',
        'pressed' => 'pressed'
    ],
    'momentary' => [
        'default' => 'he-tap'
    ],
    'level-step' => [
        'default' => 'he-settings1'
    ],
    'carbon-monoxide' => [
        'detected' => 'he-cloud1',
        'clear' => 'he-brightness-contrast',
    ],
    'contact' => [
        'open' => 'he-contact_open', 
        'closed' => 'he-contact_closed',
    ],
    'multi' => [
        'open' => 'he-contact_open', 
        'closed' => 'he-contact_closed',
    ],
    'dashboard' => [
        'default' => ''
    ],
    'dimmer' => [
        'off' => 'he-dimmer_low', 
        'on' => 'he-dimmer_medium',
    ],
    'door' => [
        'open' => 'he-door_open', 
        'closed' => 'he-door_closed',
    ],
    'door-control' => [
        'open' => 'he-door_open', 
        'closed' => 'he-door_closed', 
        'closing' => 'he-door_exit_2', 
        'opening' => 'he-door_enter',
    ],
    'fan' => [
        'off' => 'he-fan_off', 
        'on' => 'he-fan_on', 
        'low' => 'he-fan_low', 
        'medium-low' => 'he-fan_med_low', 
        'medium' => 'he-fan_med', 
        'medium-high' => 'he-fan_med_high', 
        'high' => 'he-fan_high', 
        'auto' => 'he-fan_auto',
    ],
    'garage' => [
        'open' => 'he-garage_open', 
        'closed' => 'he-garage_closed', 
        'opening' => 'he-garage_open', 
        'closing' => 'he-garage_closed',
    ],
    'garage-control' => [
        'open' => 'he-garage_open',
        'closed' => 'he-garage_closed', 
        'opening' => 'he-garage_open', 
        'closing' => 'he-garage_closed',
    ],
    'illuminance' => [
        'default' => ''
    ],
    'energy' => [
        'default' => ''
    ],
    'temperature' => [
        'default' => ''
    ],
    'humidity' => [
        'default' => ''
    ],
    'lock' => [
        'locked' => 'he-lock1', 
        'unlocked' => 'he-unlocked',
    ],
    'motion' => [
        'active' => 'he-running', 
        'inactive' => 'he-motion-sensor',
    ],
    'music-player' => [
        'play' => 'play',
        'pause' => 'pause',
        'stop' => 'stop',
        'next' => 'nextTrack',
        'previous' => 'previousTrack',
        'mute' => 'mute',
        'unmute' => 'unmute'        
    ],
    'outlet' => [
        'on' => 'he-outlet_3', 
        'off' => 'he-outlet_off',
    ],
    'presence' => [
        'present' => 'he-user-check', 
        'not_present' => 'he-not_present',
    ],
    'relay' => [
        'on' => 'he-relay_on', 
        'off' => 'he-relay_off',
    ],
    'shades' => [
        'on' => 'he-shades_open',
        'open' => 'he-shades_open',
        'off' => 'he-shades_closed',
        'closed' => 'he-shades_closed', 
        'opening' => 'he-shades_partially_open',
        'partially_open' => 'he-shades_partially_open',
        'closing'=> 'he-shades_partially_open',
    ],
    'shock' => [
        'clear' => 'he-window', 
        'detected' => 'he-siren',
    ],
    'smoke' => [
        'detected' => 'he-fire1', 
        'clear' => 'he-fire_alarm_clear',
    ],
    'switches' => [
        'on' => 'he-switch_2_flipped', 
        'off' => 'he-switch_2',
    ],
    'valve' => [
        'open' => 'he-valve_4', 
        'closed' => 'he-valve',
    ],
    'volume' => [
        'default' => 'volume_up'
    ],
    'water' => [
        'wet' => 'he-water', 
        'dry' => 'he-water_dry',
    ],
    'window' => [
        'open' => 'he-window_2', 
        'closed' => 'he-window_1',
    ],
    'scene' => [
        'default' => 'he-image1'
    ]
];

/*
    'music-player' => [
        'mute' => 'mute',
        'unmute' => 'unmute',
        'next' => 'nextTrack',
        'previous' => 'prevTrack'
    ],
*/

/*
foreach ($iconlookup as $template => $action) {
  if (is_array($action)) {
    foreach ($action as $mode => $actionname) {
      print "<div class='" . $actionname . "'>" . $actionname . "</div>";
    }
  } else {
    print "<div class='" . $action . "'>" . $action . "</div>";
  }
}
*/
