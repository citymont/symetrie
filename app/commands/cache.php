#!/usr/bin/php
<?php 
/**
 * CLI : $ php app/commands/cache.php 
 * URI : /app/commands/cache.php
 * 
 */

function cache($dir) {
    foreach(glob($dir . '/*') as $file) {
        unlink($file);
    }
}
function views($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file)){
        	views($file);
        	rmdir($file);
        }
        else {
        	unlink($file);
        }
            
    }
    
}

cache(__DIR__.'/../storage/cache/');
views(__DIR__.'/../storage/views/');

print '----------------

';
print 'Cache clear : OK


----------------';
