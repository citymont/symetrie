<?php
/**
 * Class Actions
 * -------------
 * fonctions liées au rendu des actions
 */

class Actions {

	function TwigAutoloader() {

		require_once __DIR__.'/../../vendor/twig/twig/lib/Twig/Autoloader.php';
		Twig_Autoloader::register();
	}

	function Twigloader()
	{
		$this->TwigAutoloader();

		$loader = new Twig_Loader_Filesystem(array(__DIR__.'/../../app/views/')); // or dirname(__FILE__)
		$twig = new Twig_Environment($loader, array(
			'debug' => false,
		    'cache' => __DIR__.'/../../app/storage/views', // to active the cache for template only
		));
		$twig->addExtension(new Twig_Extension_Debug());
		$twig->addGlobal('conf',new TwigConf());

		return $twig;
	}

	/**
	 * Admin Twig instance
	 * @param string $modelName
	 */
	function Admin($modelName) {

		$this->TwigAutoloader();

		// Model ready for Twig
		$model = '{% extends "baseAdmin.html.twig" %}{% block content %}';
		$model .= file_get_contents(__DIR__."/../../app/model/".$modelName.".editable.html");
		$model .= '{% endblock %}';

		$loader = new Twig_Loader_Array(array(
				'baseAdmin.html.twig' => file_get_contents(__DIR__."/../../app/views/baseAdmin.html.twig"),
				'model.html' => $model,
		));

		$loader = new Twig_Loader_Chain(array($loader));
		$twig = new Twig_Environment($loader);

		// add globals variables
		$twig->addGlobal('conf',new TwigConf());
		$app = new App(); 

		echo $twig->render('model.html', array("admin"=>"no", "uri"=>$app->getRouteInfos(), "flash" => $app->getFlash()));
	}
	/**
	 * renderView
	 * @param  obj $engine    template engine
	 * @param  string $modelName model name
	 * @param  string $docId     doc name
	 * @return html            render teamplate + data
	 */
	public function renderView($engine, $modelName, $docId) {

		$file = __DIR__."/../data/".$modelName."".$docId."/choose.json"; // last file

		try {
			if (!file_exists($file)) {
				throw new Exception('No data');
			} else {
				$json = file_get_contents($file);
				// Render
				echo $engine->render($modelName.'.html.twig', json_decode($json, true));
			}
			
		} catch (Exception $e) {
			 echo 'Erreur : ' . $e->getMessage();
		}
	}

	
}

class TwigConf {

	/*public $vendor = "";
	public $admin_conf_url = "";*/

	function __construct(){

		$a = new App();
		$this->vendor = $a->viewsAssets;
        $this->admin_conf_url = $a->viewsAdminConfUrl; 

	}

}