<?php
/*
	Uploader
 */
require dirname(__DIR__) . '/../vendor/autoload.php';

$storage = new \Upload\Storage\FileSystem(dirname( __FILE__ ));
$file = new \Upload\File('file', $storage);

// rename the file on upload
$new_filename = uniqid();
$file->setName($file->getName().'_'.$new_filename);

// Validate file upload
$file->addValidations(array(
    // Ensure file is of type "image/png"
    new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpeg')),

    // Ensure file is no larger than 5M (use "B", "K", M", or "G")
    new \Upload\Validation\Size('5M')
));

// Access data about the file that has been uploaded
$data = array(
    'name'       => $file->getNameWithExtension(),
    'extension'  => $file->getExtension(),
    'mime'       => $file->getMimetype(),
    'size'       => $file->getSize(),
    'md5'        => $file->getMd5(),
    'dimensions' => $file->getDimensions()
);

// Try to upload file
try {
    // Success!
    $file->upload();
    print json_encode($data);

} catch (\Exception $e) {
    // Fail!
    $errors = $file->getErrors();
}    
