<?php
require '../src/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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
//$c = $app->getContainer();


$app->post('/addcategorie[/]', '\lbs\control\GestController:addCategorie');

$app->put('/updatecategorie/{id}[/]', '\lbs\control\GestController:updateCategorie');

$app->get('/commande/{id}[/]','\lbs\control\GestController:getCommande');

$app->get('/sandwichs[/]','\lbs\control\GestController:getSandsByCats');

$app->post('/delsandwich/{id}[/]','\lbs\control\GestController:deleteSandwich');

$app->get('/addsandwich[/]','\lbs\control\GestController:getAddSandwich');

$app->post('/postsandwich[/]','\lbs\control\GestController:addSandwich');

$app->get('/updsandwich/{id}[/]','\lbs\control\GestController:getPutSandwich');

$app->post('/putsandwich/{id}[/]','\lbs\control\GestController:putSandwich');

$app->get('/connexion[/]','\lbs\control\GestController:getConnexion');

$app->get('[/]','\lbs\control\GestController:getConnexion');

$app->post('/login[/]','\lbs\control\GestController:login');

$app->run();