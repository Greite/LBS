<?php
require '../src/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categorie as Categorie;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

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

$app->post('/addcategorie[/]', '\lbs\control\LbsController:addCategorie');

$app->put('/updatecategorie/{id}[/]', '\lbs\control\LbsController:updateCategorie');

$app->get('/commande/{id}[/]','\lbs\control\LbsController:getCommande');

$app->get('/sandwichs[/]','\lbs\control\LbsController:getSandsByCats');

$app->post('/delsandwich/{id}[/]','\lbs\control\LbsController:deleteSandwich');

$app->get('/addsandwich[/]','\lbs\control\LbsController:getAddSandwich');

$app->post('/postsandwich[/]','\lbs\control\LbsController:addSandwich');

$app->get('/putsandwich/{id}[/]','\lbs\control\LbsController:getPutSandwich');

$app->get('/connexion[/]','\lbs\control\LbsController:getConnexion');

$app->run();