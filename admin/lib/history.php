<?php

/*
	Enregistre l'historique d'edition d'une page

 */

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