<?php
require '../src/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categorie as Categorie;

$settings = require_once '../src/conf/api_settings.php';
$errors = require_once '../src/conf/api_errors.php';
$dependencies = require_once '../src/conf/api_deps.php';

$config = parse_ini_file("../src/conf/lbs.db.conf.ini");

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$configuration = array_merge($settings, $errors, $dependencies);
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->get('/categories[/]', function (Request $req, Response $resp, $args){
	$tablal = Categorie::all();
	$resp = $resp->withHeader('Content-Type', "application/json;charset=utf-8");
	$resp->getBody()->write(json_encode($tablal));
	return $resp;
	}
);

$app->get('/categories/{id}[/]', function (Request $req, Response $resp, $args) {
	try {
		$cats = Categorie::where("id", "=", $args['id'])->firstOrFail();
	} catch (Exception $e) {
		$resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /categorie/'.$args['id']));
		return $resp;
	}
	$resp = $resp->withJson($cats);
	return $resp;
		
	}
);

$app->run();