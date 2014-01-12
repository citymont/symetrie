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

    	$v = new AdminValidator();

    	$apiMethod = (isset($_GET['method'])) ? $v->validator($_GET['method'],'method') : null;
		$model = (isset($_GET['model'])) ? $v->validator($_GET['model'],'model') : null;
		$id = (isset($_GET['id'])) ? $v->validator($_GET['id'],'model_id',$model) : null;

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

			$fileName = (isset($_GET['file'])) ? $v->validator($_GET['file'],'filename') : null ;

			if($fileName) {
				if(!$id) {
				$fileIndex = $dossier.$fileName;
			} else {
				$fileIndex = $dossier.$id.'/'.$fileName;
			}

			print file_get_contents($fileIndex);
			}
			
		}

      
    }

    function post() {
    	
    	$v = new AdminValidator();

    	$model = (isset($_POST['model'])) ? $v->validator($_POST['model'],'model') : null ;
		$id = (isset($_POST['id'])) ? $v->validator($_POST['id'],'model_id',$model) : null ;
		$publish = (isset($_POST['publish'])) ? $v->validator($_POST['publish'],'publish') : null ;

		$dossier = __DIR__."/../../data/".$model."/";

		$data =$_POST['data'];

		if($publish == "false") {

			if(!$id) { 
				$file = $dossier.time().'.json';
			} else {
				$file = $dossier.$id.'/'.time().'.json';
			}

			file_put_contents($file, $data);
		}

		if($publish == "true") {

			if(!$id) {
				$fileIndex = $dossier.'choose'.'.json';
			} else {
				$fileIndex = $dossier.$id.'/'.'choose'.'.json';
			}

			file_put_contents($fileIndex, $data);
		}
    }

}


