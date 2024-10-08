<?php
namespace pathfinder;

function get_total_xp_reward($cr) {
    $XP_TABLE = array(
        '1/8'   => '50',
        '1/6'   => '65',
        '1/4'   => '100',
        '1/3'   => '135',
        '1/2'   => '200',
        '1'     => '400',
        '2'     => '600',
        '3'     => '800',
        '4'     => '1200',
        '5'     => '1600',
        '6'     => '2400',
        '7'     => '3200',
        '8'     => '4800',
        '9'     => '6400',
        '10'    => '9600',
        '11'    => '12800',
        '12'    => '19200',
        '13'    => '25600',
        '14'    => '38400',
        '15'    => '51200',
        '16'    => '76800',
        '17'    => '102400',
        '18'    => '153600',
        '19'    => '204800',
        '20'    => '307200',
        '21'    => '409600',
        '22'    => '614400',
        '23'    => '819200',
        '24'    => '1228800',
        '25'    => '1638400',
        '26'    => '2457600',
        '27'    => '3276800',
        '28'    => '4915200',
        '29'    => '6553600',
        '30'    => '9830400'
    ); 
    return $XP_TABLE[$cr] ?? 'Unknown';
}

?>
