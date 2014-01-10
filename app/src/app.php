<?php
/**
 * Application
 */

require_once(__DIR__."/admin/validator.php");

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
	public function getRouteInfos($extra = null) {

		$routes = $this->routes;

		//from ToroPHP (:23:43)
		
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
        $path_info = '/';
        if (!empty($_SERVER['PATH_INFO'])) {
            $path_info = $_SERVER['PATH_INFO'];
        }
        else if (!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
            $path_info = $_SERVER['ORIG_PATH_INFO'];
        }
        else {
            if (!empty($_SERVER['REQUEST_URI'])) {
                $path_info = (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
            }
        }

		$discovered_handler = null;
        $regex_matches = array();

        if (isset($routes[$path_info])) {
            $discovered_handler = $routes[$path_info];
        }
        else if ($routes) {
            $tokens = array(
                ':string' => '([a-zA-Z]+)',
                ':number' => '([0-9]+)',
                ':alpha'  => '([a-zA-Z0-9-_]+)'
            );
            foreach ($routes as $pattern => $handler_name) {
                $pattern = strtr($pattern, $tokens);
                if (preg_match('#^/?' . $pattern . '/?$#', $path_info, $matches)) {
                    $discovered_handler = $handler_name;
                    $regex_matches = $matches;
                    break;
                }
            }
        }
        $modelEx = explode("Handler",$discovered_handler);
        $model = strtolower($modelEx[0]);

        if(count($regex_matches) >= 2) {
        	$id = $regex_matches[1];
        } else {
        	$id = "";
        }

        $v = new AdminValidator();
        $v->validator($id,'model_id',$model);

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
	/**
	 * Test URI for login
	 * @param  string $loginRoute login route
	 */
	public function testLoginUri($loginRoute = "admin/login") {

		$serv = $_SERVER['REQUEST_URI']; 
		$a =explode("admin.php/", $serv);
		$b =explode("?", $a[1]);
		if($b[0] != $loginRoute) {
			$this->error401();
		}

	}
}
