<?php
/**
 * Class Actions
 * -------------
 * fonctions liées au rendu des actions
 */

class Actions {

	function TwigAutoloader() {

		Twig_Autoloader::register();
	}

	function Twigloader()
	{
		$this->TwigAutoloader();

		$loader = new Twig_Loader_Filesystem(array(__DIR__.'/../../app/views/')); // or dirname(__FILE__)
		
		$app = new App(); 
		$twigC = array('debug' => false);
		if ($app->cacheTwig === true) {
			$twigC['cache'] = __DIR__.'/../../app/storage/views';
		}

		$twig = new Twig_Environment($loader, $twigC);
		$twig->addExtension(new Twig_Extension_Debug());
		$twig->addGlobal('conf',new TwigConf());
		$twig->addGlobal('data',new TwigData());

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

		$loaderFiles = new Twig_Loader_Filesystem(array(__DIR__.'/../../app/views/')); // or dirname(__FILE__)
		
		$loader = new Twig_Loader_Array(array(
				'baseAdmin.html.twig' => file_get_contents(__DIR__."/../../app/views/baseAdmin.html.twig"),
				'model.html' => $model,
		));

		$loader = new Twig_Loader_Chain(array($loader, $loaderFiles));
		$twig = new Twig_Environment($loader);

		// add globals variables
		$twig->addGlobal('conf',new TwigConf());
		$twig->addGlobal('data',new TwigData());
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

	/**
	 * renderViewStatic
	 * @param  obj $engine    template engine
	 * @param  string $modelName model name
	 * @return html            render teamplate + data
	 */
	public function renderViewStatic($engine, $modelName) {

		try {
			$app = new App();
			echo $engine->render($modelName.'.html.twig', array("flash" => $app->getFlash()));

		} catch (Exception $e) {
			 echo 'Erreur : ' . $e->getMessage();
		}
	}

	/**
	 * renderViewExtended
	 * @param  obj $engine    template engine
	 * @param  string $modelName model name
	 * @param  string $docId     doc name
	 * @param  string $arrayData     array with Data
	 * @return html            render teamplate + data
	 */
	public function renderViewExtended($engine, $modelName, $docId, $arrayData) {

		$file = __DIR__."/../data/".$modelName."".$docId."/choose.json"; // last file

		try {
			if (!file_exists($file)) {
				throw new Exception('No data');
			} else {
				$json = file_get_contents($file);
				// Render
				echo $engine->render($modelName.'.html.twig', array_merge(json_decode($json, true),$arrayData));
			}
			
		} catch (Exception $e) {
			 echo 'Erreur : ' . $e->getMessage();
		}
	}

	
}
