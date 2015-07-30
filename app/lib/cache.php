<?php

/**
 * Cache Class
 */
class Cache {
	
	// http://blog.alexis-ragot.com/systeme-de-cache-php
	
	/**
	 * Vérifie si le fichier en cache est valide (lifetime) 
	 * @param  string $cache filename
	 * @return bool       
	 */
	
	function check_cache($cache){
		$a = new App();
		clearstatcache();
		if(($a->cacheExpire!=0) && is_file($cache) && filemtime($cache) >= mktime(date("G"),(int)date("i")-$a->cacheExpire,date("s"),date("m"),date("d"),date("Y"))) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Fin du buffer 
	 * @param  string $cache        filename
	 * @param  string $cachecontent contenu à ajouter dans le fichier
	 */
	
	function end($cache,$cachecontent) {
		
		ob_end_clean();

		file_put_contents($cache,$cachecontent);

		readfile($cache);
	}

	/**
	 * Démarrage du buffer
	 */
	
	function start() {
		ob_start();
	}
	
}