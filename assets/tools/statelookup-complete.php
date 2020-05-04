<?php

$statelookup = [
    'acceleration' => [
        'active' => 'he-acceleration_active',
        'inactive' => 'he-acceleration_inactive',
    ],
    'attribute' => [
        'default' => '',    
        'Sending...' => '',
    ],
    'battery' => [
        'default' => ''
    ],
    'bulb' => [
        'off' => 'he-bulb_off', 
	'on' => 'he-bulb_on',
        'Sending...' => '',
    ],
    'bulb-color' => [
        'off' => 'he-bulb_off', 
	'on' => 'he-bulb_on',
        'Sending...' => '',
    ],
    'buttons' => [
        'default' => 'he-tap'
    ],
    'carbon-monoxide' => [
        'detected' => 'he-cloud1',
        'clear' => 'he-brightness-contrast',
    ],    
    'carbon-monoxide' => [
        'detected' => 'he-cloud1',
        'clear' => 'he-brightness-contrast',
    ],
    'clock' => [
        'default' => ''
    ],
    'clock-analog' => [
        'default' => ''
    ],
    'clock-date' => [
        'default' => ''
    ],
    'contact' => [
        'open' => 'he-contact_open', 
        'closed' => 'he-contact_closed',
    ],
    'dashboard' => [
        'default' => ''
    ],
    'date' => [
        'default' => ''
    ],
    'dimmer' => [
        'off' => 'he-dimmer_low', 
	'on' => 'he-dimmer_medium',
	'Sending...' => '',
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
        'Sending...' => '',
    ],
    'energy' => [
        'default' => ''
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
        'Sending...' => '',
    ],
    'generic' => [
        'default' => ''
    ],
    'hsm' => [
        'armedHome' => '', 
	'armedAway' => '',
	'armedNight' => '',
	'disarmed' => '',
	'allDisarmed' => '',
	'armingAway' => '',
    ],
    'humidity' => [
        'default' => ''
    ],
    'illuminance' => [
        'default' => ''
    ],
    'images' => [
        'default' => ''
    ],
    'level-step' => [
        'default' => ''
    ],
    'level-vertical' => [
        'default' => ''
    ],
    'links' => [
        'default' => ''
    ],
    'lock' => [
        'locked' => 'he-lock1', 
	'unlocked' => 'he-unlocked',
	'Sending...' => '',
    ],
    'mode' => [
	    'day' => '',
	    'evening' => '',
	    'night' => '',
	    'away' => '',
    ],
    'momentary' => [
        'default' => 'he-tap'
    ],
    'motion' => [
        'active' => 'he-running', 
        'inactive' => 'he-motion-sensor',
    ],
    'multi' => [
        'open' => 'he-contact_open',
        'closed' => 'he-contact_closed',
    ],
    'music-player' => [
        'default' => ''
    ],
    'outlet' => [
        'on' => 'he-outlet_3', 
	'off' => 'he-outlet_off',
	'Sending...' => '',
    ],
    'power' => [
        'default' => ''
    ],
    'presence' => [
        'present' => 'he-user-check', 
        'not present' => 'he-not_present',
    ],
    'relay' => [
        'on' => 'he-relay_on', 
        'off' => 'he-relay_off',
    ],
    'scene' => [
        'default' => 'he-image1'
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
    'switch' => [
        'on' => 'he-switch_2_flipped', 
	'off' => 'he-switch_2',
	'Sending...' => '',
    ],
    'temperature' => [
        'default' => ''
    ],
    'texttile' => [
        'default' => ''
    ],
    'thermostat' => [
        'heating' => '',
        'pending.cool' => '',
        'pending.heat' => '',
        'vent.economizer' => '',
        'idle' => '',
        'cooling' => '',
        'fan.only' => ''
    ],
    'valve' => [
        'open' => 'he-valve_4', 
	'closed' => 'he-valve',
        'Sending...' => '',
    ],
    'variable-bool' => [
        'default' => ''
    ],
    'variable-decimal' => [
        'default' => ''
    ],
    'variable-number' => [
        'default' => ''
    ],
    'variable-string' => [
        'default' => ''
    ],
    'variable-time' => [
        'default' => ''
    ],
    'video-player' => [
        'default' => ''
    ],
    'volume' => [
        'default' => ''
    ],
    'water' => [
        'wet' => 'he-water', 
        'dry' => 'he-water_dry',
    ],
    'weather' => [
        'default' => ''
    ],
    'window' => [
        'open' => 'he-window_2', 
        'closed' => 'he-window_1',
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

