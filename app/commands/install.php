#!/usr/bin/php
<?php 
/**
 * CLI : $ php app/commands/install.php 
 * URI : /app/commands/install.php
 * 
 */

print '----------------

';
if(!is_dir(__DIR__.'/../storage/')) {
	mkdir(__DIR__.'/../storage/');
}

if(!is_dir(__DIR__.'/../storage/cache/')) {
	mkdir(__DIR__.'/../storage/cache/');

print '
Cache : OK
';
} 
if(!is_dir(__DIR__.'/../storage/views/')) {
	mkdir(__DIR__.'/../storage/views/');
	
print '
Cache Views : OK
';
} else {
print '
Cache folder already ceated
';
}

print '

----------------';
