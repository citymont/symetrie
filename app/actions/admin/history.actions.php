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
			if(isset($id)) { $dossier = $dossier.$id; }
			$files = scandir($dossier, 1);

			$output ="<ul>";
			$i = 0;

			foreach ($files as $value) {
			
				$time = explode('.',$value);
				if($value === '.' || $value === '..' || $value === 'choose.json') {continue;} 
				$output .= "<li data-val='".$value."'>".date('d/m/Y h:m:s',$time[0])."</li>";
				
				$i++;
				if($i == 25) break;
			
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

		if($apiMethod == "slice") {

			$dossier = __DIR__."/../../data/slices/";
			$fileName = (isset($_GET['file'])) ? $v->validator($_GET['file'],'slice') : null ;

			if($fileName) {
				
				$fileIndex = $dossier.$fileName;
			}

			try {
				if (!file_exists($fileIndex)) {
					// Create empty file 
					file_put_contents($fileIndex, "{}");
				} else {
					print file_get_contents($fileIndex);
				}
			} catch (Exception $e) {
				
			}
			
			
		}

		if($apiMethod == "slicecomplete") { // with HTML

			$appActions = new Actions(); 

			$dossier = __DIR__."/../../data/slices/";
			$fileName = (isset($_GET['file'])) ? $v->validator($_GET['file'],'slice') : null ;

			if($fileName) {
		
				$data = $dossier.$fileName;
				
				try {
					if (!file_exists($data)) {
						throw new Exception('No data');
					} else {
						$source = file_get_contents($data);
					}
					
				} catch (Exception $e) {
					 echo 'Erreur : ' . $e->getMessage();
				}

			}

			$twig = $appActions->Twigloader();
			$appActions->renderSliceView($twig, $source, $_GET['template']);
			
		}

      
    }

    function post() {
    	
    	$v = new AdminValidator();

    	$model = (isset($_POST['model'])) ? $v->validator($_POST['model'],'model') : null ;
		$id = (isset($_POST['id'])) ? $v->validator($_POST['id'],'model_id',$model) : null ;
		$publish = (isset($_POST['publish'])) ? $v->validator($_POST['publish'],'publish') : null ;
		$apiMethod = (isset($_POST['method'])) ? $v->validator($_POST['method'],'method') : null;

		$dossier = __DIR__."/../../data/".$model."/";

		$data =$_POST['data'];

		if($publish == "false") {

			if(!$id) { 
				$file = $dossier.time().'.json';
			} else {
				$file = $dossier.$id.'/'.time().'.json';
			}

			file_put_contents($file, $data);
			chmod($file, 0755);

		}

		if($publish == "true") {

			if(!$id) {
				$fileIndex = $dossier.'choose'.'.json';
			} else {
				$fileIndex = $dossier.$id.'/'.'choose'.'.json';
			}

			file_put_contents($fileIndex, $data);
			chmod($fileIndex, 0755);
			
			// Clean all cache
			$cache = new Cache();
			$cache->clearCacheAll();
		}

		if($apiMethod == "slice") {

			$dossier = __DIR__."/../../data/slices/";
			$fileName = (isset($_POST['file'])) ? $v->validator($_POST['file'],'slice') : null ;

			if($fileName) {
				
				$fileIndex = $dossier.$fileName;
			}

			file_put_contents($fileIndex, $data);
			chmod($fileIndex, 0755);
			
			// Clean all cache
			$cache = new Cache();
			$cache->clearCacheAll();
		}
    }

}


