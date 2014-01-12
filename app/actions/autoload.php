<?php

foreach(glob(__DIR__."/*") as $file) {
	if(!is_dir($file)){
	    $dirI = pathinfo($file);
	    require_once(__DIR__."/".$dirI['basename']);
	}
}

if( defined('ADMIN') ) { 

	foreach(glob(__DIR__."/admin/*") as $file) {
		if(!is_dir($file)){
		    $dirI = pathinfo($file);
		    require_once(__DIR__."/admin/".$dirI['basename']);
		}
	}

}