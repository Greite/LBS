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

$app->get('/categories[/]','\lbs\control\LbsController:getCategories');

$app->get('/categories/{id}[/]','\lbs\control\LbsController:getCategoriesId');

$app->post('/addcategorie[/]', '\lbs\control\LbsController:addCategorie');

$app->get('/sandwichs[/]', '\lbs\control\LbsController:getSandwichs');

$app->put('/updatecategorie/{id}[/]', '\lbs\control\LbsController:updateCategorie');

$app->get('/sandwichs/{id}[/]','\lbs\control\LbsController:getSandwichsId');

$app->get('/categories/{id}/sandwichs[/]','\lbs\control\LbsController:getSandsOfCat');

$app->get('/sandwichs/{id}/categories[/]','\lbs\control\LbsController:getCatsOfSand')->setName('sandwich2cat');

$app->get('/sandwichs/{id}/tailles[/]','\lbs\control\LbsController:getTaillesOfSand')->setName('sandwich2taille');

$app->post('/addcommande[/]','\lbs\control\LbsController:addCommande');

$app->get('/commande/{id}[/]','\lbs\control\LbsController:getCommande');

$app->get('/carte/{id}/auth','\lbs\control\LbsController:authentificationCarte');

$app->post('/addCarte[/]','\lbs\control\LbsController:addCarte');

$app->run();