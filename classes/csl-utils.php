<?php

$plugin_dir = plugins_url('', dirname(__FILE__) );

function csl_flag_url($country) {
	$flag = str_replace(".","", $country) . '.png';
	$flag = str_replace(" ","", $flag);	
	$plugin_dir = plugins_url('', dirname(__FILE__) ) .'/images/flags/';
	$img = '<img class="flag50" src="'. $plugin_dir . $flag . '" />';
	return $img;			
}

function csl_get_plugins_dir() { 
    return $plugin_dir = plugins_url('', dirname(__FILE__) );    
}
?>