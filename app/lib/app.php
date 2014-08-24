<?php
/**
 * Application
 */

require(__DIR__."/../../vendor/autoload.php");
require(__DIR__."/cache.php");
require(__DIR__."/actions.php");
require(__DIR__."/validator.php");

class AppOrigin {

	public $loginKey = "";

	protected $routes = array(
		    "/" => "IndexHandler",
		    "/:alpha" => "IndexHandler",
		    "/index/:alpha/:alpha" => "IndexHandler"
		);

	protected $routesAdmin = array(
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
		    header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  
		});

		$routes = $this->routes;
		$routesAdmin = $this->routesAdmin;
		
		if( defined('ADMIN') ) { 
			$routes = array_merge($routes, $routesAdmin);
		}

		Toro::serve($routes);
	}

	public function startCache() {

		ToroHook::add("before_handler", function($vars) { 

			$appCache = new Cache(); 
			
			$cacheName = md5($_SERVER['REQUEST_URI']);
			$cache = __DIR__.'/../storage/cache/'.$cacheName.'.cache.html';

		    if($appCache->check_cache($cache) == true) {
				readfile($cache);	 
			}
			else { 
				define("CACHE_FLAG", true); 
				$appCache->start();

			}
		});

		ToroHook::add("after_handler", function() { 

			if( defined('CACHE_FLAG') ) { 

				$appCache = new Cache(); 
				$cacheName = md5($_SERVER['REQUEST_URI']);
				$cache = __DIR__.'/../storage/cache/'.$cacheName.'.cache.html';

				$cachecontent = ob_get_contents();

				$appCache->end($cache,$cachecontent);
			}

		});

	}

	public function startPrivateAdmin() {

		ToroHook::add("before_request", function($vars) { 

			if( isset($_GET['loginkey']) ) {
				
				// Test login page
				$loginkeyTest = new App();
				$loginkeyTest->testLoginUri("admin/login");
				
			} else {
				$login = new App();
				if( isset($_SESSION['role']) and isset($_SESSION['key'])) {
					if ($_SESSION['role'] = 'ADMIN' and md5($_SESSION['key']) == $login->loginKey ) {

					} else {
						$login->error401();
					}
				} else {
					$login->error401();
				}
		}
			

		});

		ToroHook::add("before_handler", function($vars) { });

		ToroHook::add("after_handler", function() { 

			$app = new App();
			print $app->getFlash();

		});

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
