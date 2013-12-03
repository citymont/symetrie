<?php

class AdminHistoryHandler {

    function __construct() {

	    if( !defined('ADMIN') ) { 
	    	
	    	header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  

	    }
    }

    function get() {

		$apiMethod =(isset($_GET['method'])) ? $_GET['method'] : die;
		$model =$_GET['model'];
		$id =$_GET['id']; 
		$dossier = __DIR__."/../../data/".$model."/";

		if($apiMethod == "list") {

			$files = scandir($dossier, 1);

			$output ="<ul>";

			foreach ($files as $value) {
				if($value === '.' || $value === '..' || $value === 'choose.json') {continue;} 
				$output .= "<li>".$value."</li>";
			}

			$output .="</ul>";
			print $output;

		}

		if($apiMethod == "one") {

			if(!$id) {
				$fileIndex = $dossier.$_GET['file'];
			} else {
				$fileIndex = $dossier.$id.'/'.$_GET['file'];
			}

			print file_get_contents($fileIndex);
		}

      
    }

    function post() {
    	
    	$model =$_POST['model'];
		$id =$_POST['id']; 
		$dossier = __DIR__."/../../data/".$model."/";
		if(!$id) { 
			$file = $dossier.time().'.json';
		} else {
			$file = $dossier.$id.'/'.time().'.json';
		}

		$data =$_POST['data'];

		file_put_contents($file, $data);
		if(!$id) {
			$fileIndex = $dossier.'choose'.'.json';
		} else {
			$fileIndex = $dossier.$id.'/'.'choose'.'.json';
		}

		file_put_contents($fileIndex, $data);
    }
}


