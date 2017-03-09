#!/usr/bin/php
<?php
/**
 * CLI : $ php app/commands/cache.php
 * URI : /app/commands/cache.php
 *
 */

require(__DIR__.'/../lib/cache.php');

$cache = new Cache;

$cache->clearCacheFile(__DIR__.'/../storage/cache/');
$cache->clearCacheViews(__DIR__.'/../storage/views/');

print '----------------

';
print 'Cache clear : OK


----------------';
