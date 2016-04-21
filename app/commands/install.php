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
	mkdir(__DIR__.'/../storage/',0777, true);
}

if(is_dir(__DIR__.'/../../public/contents/')) {
	chmod(__DIR__.'/../../public/contents/',0755);
	print '
	Content Upload (rewritable) : OK
	';
}

if(!is_dir(__DIR__.'/../storage/cache/')) {
	mkdir(__DIR__.'/../storage/cache/',0777, true);

print '
Cache : OK
';
} 
if(!is_dir(__DIR__.'/../storage/views/')) {
	mkdir(__DIR__.'/../storage/views/',0777, true);
	
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
