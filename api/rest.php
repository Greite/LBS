<?php
require '../src/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
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

/**
 * @api {get} /categories[/]  Obtenir la liste des catégories de sandwich
 * @apiGroup Categories
 * @apiName GetCategories
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type catégorie :
 * permet d'accéder à la représentation de la ressource categories .
 * Retourne une représentation json de la ressource, incluant son nom et
 * sa description.
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici collection
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
 * @apisuccess (Succès : 200) {Number} meta.count nombre d'objets dans l'objet global
 * @apisuccess (Succès : 200) {Object} categorie la ressource categories retournée
 * @apiSuccess (Succès : 200) {Number} categorie.id Identifiant de la catégorie
 * @apiSuccess (Succès : 200) {String} categorie.nom Nom de la catégorie
 * @apiSuccess (Succès : 200) {String} categorie.description Description de la catégorie
 *
 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK
 *
 * {
 * "type": "collection",
 * "meta": {
 *   "0": "03/02/18",
 *   "count": 6
 * },
 * "categories": [
 *   {
 *     "id": 1,
 *     "nom": "bio",
 *     "description": "sandwichs ingrédients bio et locaux"
 *   },
 *   {
 *     "id": 2,
 *     "nom": "végétarien",
 *     "description": "sandwichs végétariens - peuvent contenir des produits laitiers"
 *   },
 *   {
 *     "id": 3,
 *     "nom": "traditionnel",
 *     "description": "sandwichs traditionnels : jambon, pâté, poulet etc .."
 *   },
 *   {
 *     "id": 4,
 *     "nom": "chaud",
 *     "description": "sandwichs chauds : américain, burger, "
 *   },
 *   {
 *     "id": 5,
 *     "nom": "veggie",
 *     "description": "100% Veggie"
 *   },
 *   {
 *     "id": 16,
 *     "nom": "world",
 *     "description": "Tacos, nems, burritos, nos sandwichs du monde entier"
 *   }
 *  ]
 *}
 */

$app->get('/categories[/]','\lbs\control\LbsController:getCategories');


/**
 * @api {get} /categories/{id}[/] Obtenir la description d'une catégorie
 * @apiGroup Categories
 * @apiName GetCategoriesById
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type catégorie :
 * permet d'accéder à la représentation d'une ressource categorie par un id de catégorie.
 * Retourne une représentation json de la ressource, incluant son nom et
 * sa description.
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici ressource
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
 * @apisuccess (Succès : 200) {Number} meta.count nombre d'objets dans l'objet global
 * @apisuccess (Succès : 200) {Object} categories la ressource categories retournée
 * @apiSuccess (Succès : 200) {Number} categories.id Identifiant de la catégorie
 * @apiSuccess (Succès : 200) {String} categories.nom Nom de la catégorie
 * @apiSuccess (Succès : 200) {String} categories.description Description de la catégorie
 *
 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK
 *
 *	{
 *	  "type": "ressource",
 *	  "meta": [
 *	    "03/02/18"
 *	  ],
 *	  "categories": {
 *	    "id": 4,
 *	    "nom": "chaud",
 *	    "description": "sandwichs chauds : américain, burger, "
 *	  }
 *	}
 *
 * @apiError (Erreur : 404) CategorieNotFound Categorie inexistante
 *
 * @apiErrorExample {json} exemple de réponse en cas d'erreur
 * HTTP/1.1 404 Not Found
 *{
 * "type": "error",
 * "error": 404,
 * "message": "Ressource non disponible : /categorie/45"
 *}
 */

$app->get('/categories/{id}[/]','\lbs\control\LbsController:getCategoriesId');


/**
 * @api {get} /sandwichs[/]  Obtenir la liste des sandwichs du catalogue
 * @apiGroup Sandwichs
 * @apiName GetSandwichs
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type sandwichs :
 * permet d'accéder à la représentation de la ressource sandwichs .
 * Retourne une représentation json de la ressource.
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici collection
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
 * @apisuccess (Succès : 200) {Number} meta.count nombre d'objet dans l'objet global
 * @apisuccess (Succès : 200) {Object} sandwichs la ressource sandwichs retournée
 * @apisuccess (Succès : 200) {Object} sandwichs.sandwich la ressource sandwich retournée
 * @apiSuccess (Succès : 200) {Number} sandwichs.sandwich.id Identifiant de la catégorie
 * @apiSuccess (Succès : 200) {String} sandwichs.sandwich.nom Nom de la catégorie
 * @apiSuccess (Succès : 200) {String} sandwichs.sandwich.type_pain type de pain du sandwich
 * @apiSuccess (Succès : 200) {Object} links liens vers les ressources associées au sandwich
 * @apisuccess (Succès : 200) {Link}   links.href lien vers le sandwich
 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK
 *
 * {
 * "type": "collection",
 * "meta": {
 *   "0": "03/02/18",
 *   "count": 113
 * },
 * "sandwichs": [
 *   {
 *     "sandwich": {
 *       "id": 4,
 *       "nom": "bucheron",
 *       "type_pain": "arbre"
 *    },
 *     "links": {
 *       "self": {
 *         "href": "sandwichs/4"
 *       }
 *     }
 *   },
 *   {
 *     "sandwich": {
 *       "id": 5,
 *       "nom": "jambon-beurre",
 *       "type_pain": "baguette"
 *    },
 *    "links": {
 *      "self": {
 *        "href": "sandwichs/5"
 *      }
 *    }
 *  },
 *  {
 *    "sandwich": {
 *      "id": 6,
 *      "nom": "fajitas poulet",
 *      "type_pain": "tortillas"
 *    },
 *    "links": {
 *      "self": {
 *        "href": "sandwichs/6"
 *      }
 *    }
 *  }
 * ]
 *}
 */


$app->get('/sandwichs[/]', '\lbs\control\LbsController:getSandwichs');

/**
 * @api {get} /sandwichs/{id}[/]  Obtenir la description d'un sandwich
 * @apiGroup Sandwichs
 * @apiName GetSandwichsById
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type sandwich :
 * permet d'accéder à la représentation de la ressource sandwich par un id .
 * Retourne une représentation json de la ressource
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici ressource
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
global
 * @apisuccess (Succès : 200) {Object} sandwich la ressource sandwich retournée
 * @apiSuccess (Succès : 200) {Number} sandwich.id Identifiant du sandwich
 * @apiSuccess (Succès : 200) {String} sandwich.nom Nom du sandwich
 * @apiSuccess (Succès : 200) {String} sandwich.description Description du sandwich
 * @apiSuccess (Succès : 200) {String} sandwich.type_pain Type de pain
 * @apiSuccess (Succès : 200) {String} sandwich.img Route vers l'image du sandwich
 * @apiSuccess (Succès : 200) {Object} sandwich.categories Les categories du sandwich
 * @apiSuccess (Succès : 200) {Number} sandwich.categories.id Identifiant de la categorie
 * @apiSuccess (Succès : 200) {String} sandwich.categories.nom Nom de la categorie
 * @apiSuccess (Succès : 200) {Object} sandwich.tailles Les tailles du sandwich
 * @apiSuccess (Succès : 200) {Number} sandwich.tailles.id Identifiant de la taille
 * @apiSuccess (Succès : 200) {String} sandwich.tailles.nom  Nom de la taille
 * @apiSuccess (Succès : 200) {String} sandwich.tailles.prix Prix de la taille
 * @apiSuccess (Succès : 200) {Object} links Liens vers les ressources associées au sandwich
 * @apisuccess (Succès : 200) {Object} links.categories Lien vers les ressources liée aux categories du sandwich
 * @apisuccess (Succès : 200) {Link}   links.categories.href Lien vers les categories du sandwich
 * @apisuccess (Succès : 200) {Object} links.tailles Lien vers les ressources liée à la taille du sandwich
 * @apisuccess (Succès : 200) {link} links.tailles.href Lien vers la taille du sandwich
 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK
 *{
 * "type": "ressource",
 * "meta": [
 *   "03/02/18"
 * ],
 * "sandwich": {
 *   "id": 4,
 *  "nom": "le bucheron",
 *   "description": "un sandwich de bucheron : frites, fromage, saucisse, steack, lard grillé, mayo",
 *   "type_pain": "baguette campagne",
 *   "img": null,
 *   "categories": [
 *     {
 *       "id": 3,
 *       "nom": "traditionnel"
 *     },
 *     {
 *       "id": 4,
 *       "nom": "chaud"
 *     }
 *   ],
 *   "tailles": [
 *     {
 *       "id": 1,
 *       "nom": "petite faim",
 *       "prix": "6.00"
 *     },
 *     {
 *       "id": 2,
 *       "nom": "complet",
 *       "prix": "6.50"
 *     },
 *     {
 *       "id": 3,
 *       "nom": "grosse faim",
 *       "prix": "7.00"
 *     },
 *     {
 *       "id": 4,
 *       "nom": "ogre",
 *       "prix": "8.00"
 *     }
 *   ]
 * },
 * "links": {
 *   "categories": {
 *     "href": "/sandwichs/4/categories"
 *   },
 *   "tailles": {
 *     "href": "/sandwichs/4/tailles"
 *   }
 * }
 *}

 * @apiError (Réponse : 404) MissingParameter 
 *
 * @apiErrorExample {json} exemple de réponse en cas d'erreur
 *HTTP/1.1 404 Not Found
 {
  "type": "error",
  "error": 404,
  "message": "Ressource non disponible : /sandwichs/405"
}
 */

$app->get('/sandwichs/{id}[/]','\lbs\control\LbsController:getSandwichsId');



/**
 * @api {get} /categories/{id}/sandwichs[/]  Obtenir la liste des sandwichs d'une catégorie
 * @apiGroup Categories
 * @apiName GetSandwichsByCatId
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type categories :
 * permet d'accéder à la représentation de la ressource categorie par un id de categorie.
 * Retourne une représentation json de la ressource
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici ressource
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
global
 * @apisuccess (Succès : 200) {Number} meta.count nombre d'objets dans la ressource
 * @apisuccess (Succès : 200) {Object} categories la ressource categories retournée
 * @apiSuccess (Succès : 200) {Number} categories.id Identifiant du sandwich associé a la catégorie
 * @apiSuccess (Succès : 200) {String} categories.nom Nom du sandwich associé a la catégorie
 * @apiSuccess (Succès : 200) {String} categories.description Description du sandwich associé à la catégorie
 * @apiSuccess (Succès : 200) {String} categories.type_pain Type de pain du sandwich associé à la catégorie
 * @apiSuccess (Succès : 200) {String} categories.img Route vers l'image du sandwich

 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK

 {
  "type": "collection",
  "meta": {
    "0": "03/02/18",
    "count": 2
  },
  "categories": [
    {
      "id": 4,
      "nom": "le bucheron",
      "description": "un sandwich de bucheron : frites, fromage, saucisse, steack, lard grillé, mayo",
      "type_pain": "baguette campagne",
      "img": null
    },
    {
      "id": 6,
      "nom": "fajitas poulet",
      "description": "fajitas au poulet avec ses tortillas de mais, comme à Puebla",
      "type_pain": "tortillas",
      "img": null
    }
  ]
}



 */


$app->get('/categories/{id}/sandwichs[/]','\lbs\control\LbsController:getSandsOfCat');

/**
 * @api {get} /sandwichs/{id}/categories[/]  Obtenir la liste des catégories d'un sandwich
 * @apiGroup Sandwichs
 * @apiName GetCategorieBySandId
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type categories :
 * permet d'accéder à la représentation de la ressource categories par l'id d'un sandwich.
 * Retourne une représentation json de la ressource
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici collection
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
global
 * @apisuccess (Succès : 200) {Number} meta.count nombre d'objets dans la ressource
 * @apisuccess (Succès : 200) {Object} categories la ressource categories retournée
 * @apiSuccess (Succès : 200) {Number} categories.id Identifiant de la categories associée au sandwich
 * @apiSuccess (Succès : 200) {String} categories.nom Nom de la categories associée au sandwich
 * @apiSuccess (Succès : 200) {String} categories.description Description de la categories associée au sandwich
 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK

 {
  "type": "collection",
  "meta": {
    "0": "03/02/18",
    "count": 2
  },
  "categories": [
    {
      "id": 3,
      "nom": "traditionnel",
      "description": "sandwichs traditionnels : jambon, pâté, poulet etc .."
    },
    {
      "id": 4,
      "nom": "chaud",
      "description": "sandwichs chauds : américain, burger, "
    }
  ]
}
 * @apiError (Réponse : 404) MissingParameter 
 *
 * @apiErrorExample {json} exemple de réponse en cas d'erreur
 *HTTP/1.1 404 Not Found
{
  "type": "error",
  "error": 404,
  "message": "Ressource non disponible : /categories/40"
}
 */




$app->get('/sandwichs/{id}/categories[/]','\lbs\control\LbsController:getCatsOfSand')->setName('sandwich2cat');


/**
 * @api {get} /sandwichs/{id}/tailles[/]  Obtenir la liste des taille d'un sandwichs
 * @apiGroup Sandwichs
 * @apiName GetTailleBySandId
 * @apiVersion 0.1.0
 *

 *
 * @apiDescription Accès à une ressource de type tailles :
 * permet d'accéder à la représentation de la ressource tailles par l'id d'un sandwich.
 * Retourne une représentation json de la ressource
 *
 *
 *
 * @apiSuccess (Succès : 200) {String} type type de la réponse, ici collection
 * @apisuccess (Succès : 200) {Object} meta méta-données sur la réponse
 * @apisuccess (Succès : 200) {Tring}  meta.date date de production de la réponse
global
 * @apisuccess (Succès : 200) {Number} meta.count nombre d'objets dans la ressource
 * @apisuccess (Succès : 200) {Object} tailles la ressource tailles retournée
 * @apiSuccess (Succès : 200) {Number} tailles.id Identifiant de la tailles associée au sandwich
 * @apiSuccess (Succès : 200) {String} tailles.nom Nom de la tailles associée au sandwich
 * @apiSuccess (Succès : 200) {String} tailles.description Description de la tailles associée au sandwich
 * @apiSuccessExample {json} exemple de réponse en cas de succès
 *     HTTP/1.1 200 OK

 {
  "type": "collection",
  "meta": {
    "0": "03/02/18",
    "count": 4
  },
  "tailles": [
    {
      "id": 1,
      "nom": "petite faim",
      "description": "le sandwich rapide pour les petites faims, même si elles sont sérieuses"
    },
    {
      "id": 2,
      "nom": "complet",
      "description": "le sandwich taille optimale pour un casse-croûte à toute heure"
    },
    {
      "id": 3,
      "nom": "grosse faim",
      "description": "à partager, ou pour les affamés"
    },
    {
      "id": 4,
      "nom": "ogre",
      "description": "pour les faims d'ogres, et encore ...."
    }
  ]
}
 * @apiError (Réponse : 404) MissingParameter 
 *
 * @apiErrorExample {json} exemple de réponse en cas d'erreur
 *HTTP/1.1 404 Not Found
 *	{
 *  	"type": "error",
 *  	"error": 404,
 *  	"message": "Ressource non disponible : /sandwichs/160"
 *  }
 */


$app->get('/sandwichs/{id}/tailles[/]','\lbs\control\LbsController:getTaillesOfSand')->setName('sandwich2taille');


/**
 * @api {post} /addcommande[/]  Créer une commande
 * @apiGroup Commande
 * @apiName CreateCommande
 * @apiVersion 0.1.0
 *
 *
 * @apiDescription Création d'une ressource de type commande.
 * La Commande est ajoutée dans la base, son identifiant est créé.
 * Le nom, prenom, mail du client ainsi que la date de livraison doivent être fournis.
 * La réponse retournée indique l'uri de la nouvelle ressource dans le header Location: et contient
 * la représentation de la nouvelle ressource.
 *
 *
 * @apiParam  (request parameters) {String} nom_client Nom du client
 * @apiParam  (request parameters) {String} prenom_client Prénom du client
 * @apiParam  (request parameters) {String} mail_client mail du client
 * @apiParam  (request parameters) {datetime} date_livraison Date de livraison de la commande
 * @apiHeader (request headers) {String} Content-Type:=application/json format utilisé pour les données transmises
 *
 * @apiParamExample {request} exemple de paramètres
 *     {
 *       "nom_client"    : "Jean",
 *       "prenom_client" : "seb",
 *	     "mail_client"   : "js@gmal.com",
 *       "date_livraison": "18-05-05 14:30:00",
 *     }
 *
 * @apiExample Exemple de requête :
 *    POST /addcommande/ HTTP/1.1
 *    Host: api.lbs.local
 *    Content-Type: application/json;charset=utf8
 *
 *    {
 *       "nom_client"    : "Jean",
 *       "prenom_client" : "seb",
 *		 "mail_client"   : "js@gmal.com",
 *       "date_livraison": "18-05-05 14:30:00",
 *		 
 *    }
 *
 * @apiSuccess (Réponse : 201) {json} commande représentation json de la nouvelle catégorie
 *
 * @apiHeader (response headers) {String} Location: uri de la ressource créée
 * @apiHeader (response headers) {String} Content-Type: format de représentation de la ressource réponse
 *
 * @apiSuccessExample {response} exemple de réponse en cas de succès
 *     HTTP/1.1 201 CREATED
 *     Location: http://api.lbs.local/commandes/f48bd708-b226-4a0b-81d1-a7913dd1e98a
 *     Content-Type: application/json;charset=utf8
 *
 * {
 *  "commande":{
 *     "nom_client":"Jean",
 *     "mail_client" : "js@gmal.com",
 *     "livraison":{
 *        "date":"18-05-05",
 *        "heure":"14:30:00"
 *     },
 *     "id":"f48bd708-b226-4a0b-81d1-a7913dd1e98a",
 *     "token":"16b6c20293209a32ac73446324c770c93fb8401109c081e7d6068c23213d2bbd"
 *    }
 *}
 *
 * @apiError (Réponse : 409) MissingParameter paramètre manquant dans la requête
 *
 * @apiErrorExample {json} exemple de réponse en cas d'erreur
 *     HTTP/1.1 409 Conflict
 *	{
 *	  "type": "error",
 *	  "error": 409,
 *	  "message": "Veuillez renseigner le nom du client / Veuillez renseigner la date de livraison / Veuillez renseigner le mail du client  / Mauvais format de mail"
 *	}
 */


$app->post('/addcommande[/]','\lbs\control\LbsController:addCommande');


/**
 * @api {get} /carte/{id}/auth  s'authentifier pour utiliser sa carte
 * @apiGroup Carte
 * @apiName 
 * @apiVersion 0.1.0
 */


$app->get('/carte/{id}/auth','\lbs\control\LbsController:authentificationCarte');



/**
 * @api {post} /addcarte[/]  Créer une carte
 * @apiGroup Carte
 * @apiName CreateCarte
 * @apiVersion 0.1.0
 *
 *
 * @apiDescription Création d'une ressource de type carte.
 * La carte est ajoutée dans la base, son identifiant et sa date d'expiration est créé.
 * Le mail du client et un mot de passe doivent être fournis.
 * La réponse retournée indique l'uri de la nouvelle ressource dans le header Location: et contient
 * la représentation de la nouvelle ressource.
 *
 *
 * @apiParam  (request parameters) {String} mail mail du client
 * @apiParam  (request parameters) {password} password mot de passe du client
 * @apiHeader (request headers) {String} Content-Type:=application/json format utilisé pour les données transmises
 *
 * @apiParamExample {request} exemple de paramètres
 *     {
 *       "mail"    : "js@gmal.com",
 *       "password": "test"
 *     }
 *
 * @apiExample Exemple de requête :
 *    POST /addcarte/ HTTP/1.1
 *    Host: api.lbs.local
 *    Content-Type: application/json;charset=utf8
 *
 *    {
 *       "mail"    : "js@gmal.com",
 *       "password": "test"
 *		 
 *    }
 *
 * @apiSuccess (Réponse : 201) {json} commande représentation json de la nouvelle catégorie
 *
 * @apiHeader (response headers) {String} Content-Type: format de représentation de la ressource réponse
 *
 * @apiSuccessExample {response} exemple de réponse en cas de succès
 *     HTTP/1.1 201 CREATED
 *     Location: http://api.lbs.local/commandes/f48bd708-b226-4a0b-81d1-a7913dd1e98a
 *     Content-Type: application/json;charset=utf8
 *
 {
  "carte": {
    "id": "54d5a01fc0",
    "mail": "js@gmal.com",
    "date_expiration": "2019-02-03"
  }
}
 */



$app->post('/addcarte[/]','\lbs\control\LbsController:addCarte');


/**
 * @api {get} /carte/{id}[/]  afficher la carte par son id
 * @apiGroup Carte
 * @apiName CarteById
 * @apiVersion 0.1.0
 */

$app->get('/carte/{id}[/]','\lbs\control\LbsController:getCarte');


$app->run();