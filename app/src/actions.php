<?php
/**
 * Class Actions
 * -------------
 * fonctions liées au rendu des actions
 */

class Actions {

	/**
	 * Loader du framework Twig
	 */
	
	function Twigloader()
	{
		/* --Start Twig Loader-- */

		require_once __DIR__.'/../../vendor/twig/twig/lib/Twig/Autoloader.php';
		Twig_Autoloader::register();

		// dossier des templates
		$loader = new Twig_Loader_Filesystem(__DIR__.'/../../app/views/'); // or dirname(__FILE__)
		$twig = new Twig_Environment($loader, array(
		    'cache' => __DIR__.'/../../app/storage/views', // to active the cache for template only
		));

		/* --End Twig Loader-- */
		return $twig;
	}

	
}
