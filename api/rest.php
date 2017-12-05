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

$app->get('/categories[/]','\lbs\control\LbsController:categories');

$app->get('/categories/{id}[/]','\lbs\control\LbsController:categoriesId');

$app->post('/addcategorie[/]', '\lbs\control\LbsController:addCategorie');

<<<<<<< HEAD
$app->get('/getsandwichs[/]', '\lbs\control\LbsController:getSandwichs');
=======
$app->put('/updatecategorie/{id}[/]', '\lbs\control\LbsController:updateCategorie');
>>>>>>> 5733ee70ea1663e6f05a2dc13d0ec959dd55760a

$app->run();