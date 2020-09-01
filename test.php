<?php

$processed_css  = <<<EOF
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
    [fixup-something]
    visibility: visible;
}

#tile-[tile_id] .tile-primary:after {
    font-size: 18px;
    margin-left: 0px;
    line-height: 1.2em;
    /* display: none; */
    [fixup-nudge]
}

EOF;


preg_match_all('/\[fixup-(.*)\]/',  $processed_css, $fixup_matches, PREG_SET_ORDER);


foreach ($fixup_matches as $index => $match) {
  $replacement[$match[0]] = $match[1];
}


print_r($replacement);

?>
