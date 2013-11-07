<?php
/**
 * Application
 */

class App {

	/**
	 * Fonction de parsage de l'URI courante
	 * @param  string $extra ajouter un / pour accéder au dossier
	 * @return array        explosion de chaine
	 */
	public static function parseUrl($extra = null) {

		$serv = $_SERVER['REQUEST_URI']; 
		$a = (defined('ADMIN')) ? explode("admin.php/", $serv) : explode("index.php/", $serv);
		$b = explode("/", $a[1]);
		$model = $b[0];

		if(isset($b[1])) {
			$id = $b[1];
		} else { 
			$id=""; 
		}

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

		/*ToroHook::add("before_request", function() { echo microtime();});
		ToroHook::add("after_request", function() {echo microtime();});*/

		Toro::serve(array(
		    "/index" => "IndexHandler",
		    "/index/:alpha" => "IndexHandler",
		    "/index/:alpha/:alpha" => "IndexHandler",
		    "/admin/history" => "AdminHistoryHandler"
		));
	}
}