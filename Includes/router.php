<?php

// Rôle : router.php = C'est le cerveau de mon app qui fait correspondre les URLs aux actions
// ? Explication : Comment ça marche ? 
//-L'utilisateur tape une URL :  localhost:8200/tata
//-Le routeur regarde dans routes.php
//-Il trouve : get('/tata', 'HomeController@index')
//-Il appelle la méthode index() du HomeController

//  include __DIR__."/service/Message-Flash.php";
// https://phprouter.com/, Download PHP ROUTER, et ajouter les éléments des fichiers dispo sur
// le GitHub
function get($route, $path_to_include)
{
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		route($route, $path_to_include);
	}
}
function post($route, $path_to_include)
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		route($route, $path_to_include);
	}
}
function put($route, $path_to_include)
{
	if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		route($route, $path_to_include);
	}
}
function patch($route, $path_to_include)
{
	if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
		route($route, $path_to_include);
	}
}
function delete($route, $path_to_include)
{
	if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
		route($route, $path_to_include);
	}
}
function any($route, $path_to_include)
{
	route($route, $path_to_include);
}
function route($route, $path_to_include) // Permet de faire le lien entre l'URL et le fichier à inclure
{
	$callback = $path_to_include; // HomeController@index
	$isControllerAction = is_string($callback) && strpos($callback, '@') !== false;

	if (!$isControllerAction && !is_callable($callback)) {
		if (!strpos($path_to_include, '.php')) {
			$path_to_include .= '.php';
		}
	}

	if ($route == "/404") {
		include_once __DIR__ . "/$path_to_include";
		exit();
	}

	$request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
	$request_url = rtrim($request_url, '/');
	$request_url = strtok($request_url, '?');

	$route_parts = explode('/', $route);
	$request_url_parts = explode('/', $request_url);
	array_shift($route_parts);
	array_shift($request_url_parts);

	if ($route_parts[0] == '' && count($request_url_parts) == 0) {
		if (is_callable($callback)) {
			call_user_func_array($callback, []);
			exit();
		}
		if ($isControllerAction) {
			dispatch_controller($callback, []);
			exit();
		}
		include_once __DIR__ . "/$path_to_include";
		exit();
	}

	if (count($route_parts) != count($request_url_parts)) {
		return;
	}

	$parameters = [];
	for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
		$route_part = $route_parts[$__i__];
		if (preg_match("/^[$]/", $route_part)) {
			$route_part = ltrim($route_part, '$');
			array_push($parameters, $request_url_parts[$__i__]);
			$$route_part = $request_url_parts[$__i__];
		} else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
			return;
		}
	}

	if (is_callable($callback)) {
		call_user_func_array($callback, $parameters);
		exit();
	}

	if ($isControllerAction) {
		dispatch_controller($callback, $parameters);
		exit();
	}

	include_once __DIR__ . "/$path_to_include";
	exit();
}

// --- helper : appeler "HomeController@index"
function dispatch_controller(string $action, array $params = []) // Permet d'appeler la méthode index du HomeController
{
	// action = "HomeController@index" -> ['HomeController','index'],
	[$controller, $method] = explode('@', $action);
	$class = $controller;

	// Charger le fichier controller s’il existe
	$file = APP_ROOT . '/../controller/' . $controller . '.php';
	if (is_file($file)) {
		require_once $file;
	}

	// Ajouter namespace si tu en utilises un (ici none)
	if (!class_exists($class)) {
		http_response_code(500);
		echo "Controller '$class' introuvable.";
		exit();
	}

	$instance = new $class(); // -> Une Instande de HomeController

	if (!method_exists($instance, $method)) {
		http_response_code(500);
		echo "Méthode '$method' introuvable sur $class.";
		exit();
	}

	call_user_func_array([$instance, $method], $params);
}


function render(string $template, array $data = [])
// $template est le paramètre de la fonction render, c'est le nom de la vue à charger
// $data est le deuxième paramètre, un tableau associatif contenant les données à passer à la vue -->> tabTitle,additionalCss,services, etc.
// Charge la vue $template (ex: 'home.index' -> view/home/index.php) et lui passe les données $data
// Exemple: $template = 'appointments.service-selection' et devient -> view/appointments/service-selection.php
{
	$views = APP_ROOT . '/../view';
	$layout = $views . '/layout.php';
	$viewFile = $views . '/' . str_replace('.', '/', $template) . '.php'; // Charge la vue demandée
	// Ici le str_replace remplace les . par des / pour créer le chemin du fichier de la vue
	// 'appointments.service-selection' devient 'appointments/service-selection'
	// et ensuite 'appointments/service-selection' devient 'view/appointments/service-selection.php'
	if (!is_file($viewFile)) {
		http_response_code(500);
		echo "Vue introuvable: $viewFile";
		exit();
	}
	// $config = require APP_ROOT . '/config/config.php';
	// $data['tabTitle'] =  $config['app']['name'];
	extract($data, EXTR_SKIP); // Extract les variables ($tabTitle, $services, $additionalCss, etc.)
	ob_start(); // Pour capturer le contenu HTML de la vue dans une variable $content
	include $viewFile;
	$content = ob_get_clean(); // Stocke le contenu HTML dans $content et nettoie le buffer
	if (is_file($layout)) {
		include $layout; // l'inclut ensuite dans le layout
	} else {
		// fallback sans layout
		echo $content;
	}
}

function out($text)
{
	echo htmlspecialchars($text);
}

function set_csrf()
{
	session_start();
	if (!isset($_SESSION["csrf"])) {
		$_SESSION["csrf"] = bin2hex(random_bytes(50));
	}
	echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
}

function is_csrf_valid()
{
	session_start();
	if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
		return false;
	}
	if ($_SESSION['csrf'] != $_POST['csrf']) {
		return false;
	}
	return true;
}
