<?php

/*
	Enregistre l'historique d'edition d'une page
	POST : save current file
	GET : get history files

 */

if($_SERVER['REQUEST_METHOD'] === "POST") {

	$model =$_POST['model'];
	$id =$_POST['id']; 
	$dossier = __DIR__."/../../app/data/".$model."/";
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

} else {
	$apiMethod =$_GET['method'];

	$model =$_GET['model'];
	$id =$_GET['id']; 
	$dossier = __DIR__."/../../app/data/".$model."/";

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
