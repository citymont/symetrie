<?php
/**
 * Application
 */

class App {

	public $loginKey = "aa3261152486caad6c230b4b6d384361";

	private $routes = array(
		    "/" => "IndexHandler",
		    "/:alpha" => "IndexHandler",
		    "/index/:alpha/:alpha" => "IndexHandler"
		);

	private $routesAdmin = array(
			"/admin/history" => "AdminHistoryHandler",
		    "/admin/login" => "AdminLoginHandler",
		    "/admin/logout" => "AdminLogoutHandler");

	/**
	 * Fonction de parsage de l'URI courante
	 * @param  string $extra ajouter un / pour accéder au dossier
	 * @return array        explosion de chaine
	 */
	public function parseUrl($extra = null) {

		$serv = $_SERVER['REQUEST_URI']; 

		if(count(explode("index.php/", $serv)) == 2) {
			$a = (defined('ADMIN')) ? explode("admin.php/", $serv) : explode("index.php/", $serv);
			$b = explode("/", $a[1]);
			$model = $b[0];
		} else { 
			$a = explode("/", $serv);
			$b = explode("/", $a[1]);
			$model = $b[0];
		}

		if(isset($b[1])) {
			$id = $b[1];
		} else { 
			$id=""; 
		}

		$routes = $this->routes;
		if (array_key_exists('/', $routes)) {
		    $model = "index";
		}

		return array('model' => $model, 'id' => $extra.$id);
	}

	/**
	 * Demarrage du router 
	 */
	
	public function startRoutes() {

		ToroHook::add("404", function() {
		    $this->error404();
		});

		$routes = $this->routes;
		$routesAdmin = $this->routesAdmin;
		
		if( defined('ADMIN') ) { 
			$routes = array_merge($routes, $routesAdmin);
		}

		Toro::serve($routes);
	}

	public function error404() {
		header('HTTP/1.0 404 Not Found');
    	echo "Not found"; 
    	exit;  
	}

	public function error401() {
		header('HTTP/1.0 401 Unauthorized');
		echo "Unauthorized"; 
		exit;  
	}

	public function setFlash($text) {

		$_SESSION['flash'] = array();
		$_SESSION['flash']['time'] = time();
		$_SESSION['flash']['text'] = $text;

	}

	public function getFlash(){
		
		if(isset($_SESSION['flash'])) {
			$f = $_SESSION['flash']['text'];
			unset($_SESSION['flash']);
			return $f;
		} else {
			return null;
		}
	
		
	}
}