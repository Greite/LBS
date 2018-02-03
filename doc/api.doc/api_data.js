define({ "api": [
  {
    "type": "get",
    "url": "/carte/{id}[/]",
    "title": "afficher la carte par son id",
    "group": "Carte",
    "name": "CarteById",
    "version": "0.1.0",
    "filename": "./api/rest.php",
    "groupTitle": "Carte"
  },
  {
    "type": "post",
    "url": "/addcarte[/]",
    "title": "Créer une carte",
    "group": "Carte",
    "name": "CreateCarte",
    "version": "0.1.0",
    "description": "<p>Création d'une ressource de type carte. La carte est ajoutée dans la base, son identifiant et sa date d'expiration est créé. Le mail du client et un mot de passe doivent être fournis. La réponse retournée indique l'uri de la nouvelle ressource dans le header Location: et contient la représentation de la nouvelle ressource.</p>",
    "parameter": {
      "fields": {
        "request parameters": [
          {
            "group": "request parameters",
            "type": "String",
            "optional": false,
            "field": "mail",
            "description": "<p>mail du client</p>"
          },
          {
            "group": "request parameters",
            "type": "password",
            "optional": false,
            "field": "password",
            "description": "<p>mot de passe du client</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de paramètres",
          "content": "{\n  \"mail\"    : \"js@gmal.com\",\n  \"password\": \"test\"\n}",
          "type": "request"
        }
      ]
    },
    "header": {
      "fields": {
        "request headers": [
          {
            "group": "request headers",
            "type": "String",
            "optional": false,
            "field": "Content-Type:",
            "defaultValue": "application/json",
            "description": "<p>format utilisé pour les données transmises</p>"
          }
        ],
        "response headers": [
          {
            "group": "response headers",
            "type": "String",
            "optional": false,
            "field": "Content-Type:",
            "description": "<p>format de représentation de la ressource réponse</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Exemple de requête :",
        "content": "POST /addcarte/ HTTP/1.1\nHost: api.lbs.local\nContent-Type: application/json;charset=utf8\n\n{\n   \"mail\"    : \"js@gmal.com\",\n   \"password\": \"test\"\n\n}",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Réponse : 201": [
          {
            "group": "Réponse : 201",
            "type": "json",
            "optional": false,
            "field": "commande",
            "description": "<p>représentation json de la nouvelle catégorie</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 201 CREATED\n    Location: http://api.lbs.local/commandes/f48bd708-b226-4a0b-81d1-a7913dd1e98a\n    Content-Type: application/json;charset=utf8\n\n {\n  \"carte\": {\n    \"id\": \"54d5a01fc0\",\n    \"mail\": \"js@gmal.com\",\n    \"date_expiration\": \"2019-02-03\"\n  }\n}",
          "type": "response"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Carte"
  },
  {
    "type": "get",
    "url": "/carte/{id}/auth",
    "title": "s'authentifier pour utiliser sa carte",
    "group": "Carte",
    "name": "_apiVersion_0_1_0",
    "version": "0.0.0",
    "filename": "./api/rest.php",
    "groupTitle": "Carte"
  },
  {
    "type": "get",
    "url": "/categories[/]",
    "title": "Obtenir la liste des catégories de sandwich",
    "group": "Categories",
    "name": "GetCategories",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type catégorie : permet d'accéder à la représentation de la ressource categories . Retourne une représentation json de la ressource, incluant son nom et sa description.</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici collection</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "meta.count",
            "description": "<p>nombre d'objets dans l'objet global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "categorie",
            "description": "<p>la ressource categories retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "categorie.id",
            "description": "<p>Identifiant de la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categorie.nom",
            "description": "<p>Nom de la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categorie.description",
            "description": "<p>Description de la catégorie</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n\n{\n\"type\": \"collection\",\n\"meta\": {\n  \"0\": \"03/02/18\",\n  \"count\": 6\n},\n\"categories\": [\n  {\n    \"id\": 1,\n    \"nom\": \"bio\",\n    \"description\": \"sandwichs ingrédients bio et locaux\"\n  },\n  {\n    \"id\": 2,\n    \"nom\": \"végétarien\",\n    \"description\": \"sandwichs végétariens - peuvent contenir des produits laitiers\"\n  },\n  {\n    \"id\": 3,\n    \"nom\": \"traditionnel\",\n    \"description\": \"sandwichs traditionnels : jambon, pâté, poulet etc ..\"\n  },\n  {\n    \"id\": 4,\n    \"nom\": \"chaud\",\n    \"description\": \"sandwichs chauds : américain, burger, \"\n  },\n  {\n    \"id\": 5,\n    \"nom\": \"veggie\",\n    \"description\": \"100% Veggie\"\n  },\n  {\n    \"id\": 16,\n    \"nom\": \"world\",\n    \"description\": \"Tacos, nems, burritos, nos sandwichs du monde entier\"\n  }\n ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Categories"
  },
  {
    "type": "get",
    "url": "/categories/{id}[/]",
    "title": "Obtenir la description d'une catégorie",
    "group": "Categories",
    "name": "GetCategoriesById",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type catégorie : permet d'accéder à la représentation d'une ressource categorie par un id de catégorie. Retourne une représentation json de la ressource, incluant son nom et sa description.</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici ressource</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "meta.count",
            "description": "<p>nombre d'objets dans l'objet global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "categories",
            "description": "<p>la ressource categories retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "categories.id",
            "description": "<p>Identifiant de la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.nom",
            "description": "<p>Nom de la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.description",
            "description": "<p>Description de la catégorie</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n\n\t{\n\t  \"type\": \"ressource\",\n\t  \"meta\": [\n\t    \"03/02/18\"\n\t  ],\n\t  \"categories\": {\n\t    \"id\": 4,\n\t    \"nom\": \"chaud\",\n\t    \"description\": \"sandwichs chauds : américain, burger, \"\n\t  }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Erreur : 404": [
          {
            "group": "Erreur : 404",
            "optional": false,
            "field": "CategorieNotFound",
            "description": "<p>Categorie inexistante</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas d'erreur",
          "content": "HTTP/1.1 404 Not Found\n{\n\"type\": \"error\",\n\"error\": 404,\n\"message\": \"Ressource non disponible : /categorie/45\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Categories"
  },
  {
    "type": "get",
    "url": "/categories/{id}/sandwichs[/]",
    "title": "Obtenir la liste des sandwichs d'une catégorie",
    "group": "Categories",
    "name": "GetSandwichsByCatId",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type categories : permet d'accéder à la représentation de la ressource categorie par un id de categorie. Retourne une représentation json de la ressource</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici ressource</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "meta.count",
            "description": "<p>nombre d'objets dans la ressource</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "categories",
            "description": "<p>la ressource categories retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "categories.id",
            "description": "<p>Identifiant du sandwich associé a la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.nom",
            "description": "<p>Nom du sandwich associé a la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.description",
            "description": "<p>Description du sandwich associé à la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.type_pain",
            "description": "<p>Type de pain du sandwich associé à la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.img",
            "description": "<p>Route vers l'image du sandwich</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n\n {\n  \"type\": \"collection\",\n  \"meta\": {\n    \"0\": \"03/02/18\",\n    \"count\": 2\n  },\n  \"categories\": [\n    {\n      \"id\": 4,\n      \"nom\": \"le bucheron\",\n      \"description\": \"un sandwich de bucheron : frites, fromage, saucisse, steack, lard grillé, mayo\",\n      \"type_pain\": \"baguette campagne\",\n      \"img\": null\n    },\n    {\n      \"id\": 6,\n      \"nom\": \"fajitas poulet\",\n      \"description\": \"fajitas au poulet avec ses tortillas de mais, comme à Puebla\",\n      \"type_pain\": \"tortillas\",\n      \"img\": null\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Categories"
  },
  {
    "type": "post",
    "url": "/addcommande[/]",
    "title": "Créer une commande",
    "group": "Commande",
    "name": "CreateCommande",
    "version": "0.1.0",
    "description": "<p>Création d'une ressource de type commande. La Commande est ajoutée dans la base, son identifiant est créé. Le nom, prenom, mail du client ainsi que la date de livraison doivent être fournis. La réponse retournée indique l'uri de la nouvelle ressource dans le header Location: et contient la représentation de la nouvelle ressource.</p>",
    "parameter": {
      "fields": {
        "request parameters": [
          {
            "group": "request parameters",
            "type": "String",
            "optional": false,
            "field": "nom_client",
            "description": "<p>Nom du client</p>"
          },
          {
            "group": "request parameters",
            "type": "String",
            "optional": false,
            "field": "prenom_client",
            "description": "<p>Prénom du client</p>"
          },
          {
            "group": "request parameters",
            "type": "String",
            "optional": false,
            "field": "mail_client",
            "description": "<p>mail du client</p>"
          },
          {
            "group": "request parameters",
            "type": "datetime",
            "optional": false,
            "field": "date_livraison",
            "description": "<p>Date de livraison de la commande</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de paramètres",
          "content": "    {\n      \"nom_client\"    : \"Jean\",\n      \"prenom_client\" : \"seb\",\n\t     \"mail_client\"   : \"js@gmal.com\",\n      \"date_livraison\": \"18-05-05 14:30:00\",\n    }",
          "type": "request"
        }
      ]
    },
    "header": {
      "fields": {
        "request headers": [
          {
            "group": "request headers",
            "type": "String",
            "optional": false,
            "field": "Content-Type:",
            "defaultValue": "application/json",
            "description": "<p>format utilisé pour les données transmises</p>"
          }
        ],
        "response headers": [
          {
            "group": "response headers",
            "type": "String",
            "optional": false,
            "field": "Location:",
            "description": "<p>uri de la ressource créée</p>"
          },
          {
            "group": "response headers",
            "type": "String",
            "optional": false,
            "field": "Content-Type:",
            "description": "<p>format de représentation de la ressource réponse</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Exemple de requête :",
        "content": "   POST /addcommande/ HTTP/1.1\n   Host: api.lbs.local\n   Content-Type: application/json;charset=utf8\n\n   {\n      \"nom_client\"    : \"Jean\",\n      \"prenom_client\" : \"seb\",\n\t\t \"mail_client\"   : \"js@gmal.com\",\n      \"date_livraison\": \"18-05-05 14:30:00\",\n\t\t \n   }",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Réponse : 201": [
          {
            "group": "Réponse : 201",
            "type": "json",
            "optional": false,
            "field": "commande",
            "description": "<p>représentation json de la nouvelle catégorie</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 201 CREATED\n    Location: http://api.lbs.local/commandes/f48bd708-b226-4a0b-81d1-a7913dd1e98a\n    Content-Type: application/json;charset=utf8\n\n{\n \"commande\":{\n    \"nom_client\":\"Jean\",\n    \"mail_client\" : \"js@gmal.com\",\n    \"livraison\":{\n       \"date\":\"18-05-05\",\n       \"heure\":\"14:30:00\"\n    },\n    \"id\":\"f48bd708-b226-4a0b-81d1-a7913dd1e98a\",\n    \"token\":\"16b6c20293209a32ac73446324c770c93fb8401109c081e7d6068c23213d2bbd\"\n   }\n}",
          "type": "response"
        }
      ]
    },
    "error": {
      "fields": {
        "Réponse : 409": [
          {
            "group": "Réponse : 409",
            "optional": false,
            "field": "MissingParameter",
            "description": "<p>paramètre manquant dans la requête</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas d'erreur",
          "content": "    HTTP/1.1 409 Conflict\n\t{\n\t  \"type\": \"error\",\n\t  \"error\": 409,\n\t  \"message\": \"Veuillez renseigner le nom du client / Veuillez renseigner la date de livraison / Veuillez renseigner le mail du client  / Mauvais format de mail\"\n\t}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Commande"
  },
  {
    "type": "get",
    "url": "/sandwichs/{id}/categories[/]",
    "title": "Obtenir la liste des catégories d'un sandwich",
    "group": "Sandwichs",
    "name": "GetCategorieBySandId",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type categories : permet d'accéder à la représentation de la ressource categories par l'id d'un sandwich. Retourne une représentation json de la ressource</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici collection</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "meta.count",
            "description": "<p>nombre d'objets dans la ressource</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "categories",
            "description": "<p>la ressource categories retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "categories.id",
            "description": "<p>Identifiant de la categories associée au sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.nom",
            "description": "<p>Nom de la categories associée au sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "categories.description",
            "description": "<p>Description de la categories associée au sandwich</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n\n {\n  \"type\": \"collection\",\n  \"meta\": {\n    \"0\": \"03/02/18\",\n    \"count\": 2\n  },\n  \"categories\": [\n    {\n      \"id\": 3,\n      \"nom\": \"traditionnel\",\n      \"description\": \"sandwichs traditionnels : jambon, pâté, poulet etc ..\"\n    },\n    {\n      \"id\": 4,\n      \"nom\": \"chaud\",\n      \"description\": \"sandwichs chauds : américain, burger, \"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Réponse : 404": [
          {
            "group": "Réponse : 404",
            "optional": false,
            "field": "MissingParameter",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas d'erreur",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Ressource non disponible : /categories/40\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Sandwichs"
  },
  {
    "type": "get",
    "url": "/sandwichs[/]",
    "title": "Obtenir la liste des sandwichs du catalogue",
    "group": "Sandwichs",
    "name": "GetSandwichs",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type sandwichs : permet d'accéder à la représentation de la ressource sandwichs . Retourne une représentation json de la ressource.</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici collection</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "meta.count",
            "description": "<p>nombre d'objet dans l'objet global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "sandwichs",
            "description": "<p>la ressource sandwichs retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "sandwichs.sandwich",
            "description": "<p>la ressource sandwich retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "sandwichs.sandwich.id",
            "description": "<p>Identifiant de la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwichs.sandwich.nom",
            "description": "<p>Nom de la catégorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwichs.sandwich.type_pain",
            "description": "<p>type de pain du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "links",
            "description": "<p>liens vers les ressources associées au sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Link",
            "optional": false,
            "field": "links.href",
            "description": "<p>lien vers le sandwich</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n\n{\n\"type\": \"collection\",\n\"meta\": {\n  \"0\": \"03/02/18\",\n  \"count\": 113\n},\n\"sandwichs\": [\n  {\n    \"sandwich\": {\n      \"id\": 4,\n      \"nom\": \"bucheron\",\n      \"type_pain\": \"arbre\"\n   },\n    \"links\": {\n      \"self\": {\n        \"href\": \"sandwichs/4\"\n      }\n    }\n  },\n  {\n    \"sandwich\": {\n      \"id\": 5,\n      \"nom\": \"jambon-beurre\",\n      \"type_pain\": \"baguette\"\n   },\n   \"links\": {\n     \"self\": {\n       \"href\": \"sandwichs/5\"\n     }\n   }\n },\n {\n   \"sandwich\": {\n     \"id\": 6,\n     \"nom\": \"fajitas poulet\",\n     \"type_pain\": \"tortillas\"\n   },\n   \"links\": {\n     \"self\": {\n       \"href\": \"sandwichs/6\"\n     }\n   }\n }\n]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Sandwichs"
  },
  {
    "type": "get",
    "url": "/sandwichs/{id}[/]",
    "title": "Obtenir la description d'un sandwich",
    "group": "Sandwichs",
    "name": "GetSandwichsById",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type sandwich : permet d'accéder à la représentation de la ressource sandwich par un id . Retourne une représentation json de la ressource</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici ressource</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "sandwich",
            "description": "<p>la ressource sandwich retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "sandwich.id",
            "description": "<p>Identifiant du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.nom",
            "description": "<p>Nom du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.description",
            "description": "<p>Description du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.type_pain",
            "description": "<p>Type de pain</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.img",
            "description": "<p>Route vers l'image du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "sandwich.categories",
            "description": "<p>Les categories du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "sandwich.categories.id",
            "description": "<p>Identifiant de la categorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.categories.nom",
            "description": "<p>Nom de la categorie</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "sandwich.tailles",
            "description": "<p>Les tailles du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "sandwich.tailles.id",
            "description": "<p>Identifiant de la taille</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.tailles.nom",
            "description": "<p>Nom de la taille</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "sandwich.tailles.prix",
            "description": "<p>Prix de la taille</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "links",
            "description": "<p>Liens vers les ressources associées au sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "links.categories",
            "description": "<p>Lien vers les ressources liée aux categories du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Link",
            "optional": false,
            "field": "links.categories.href",
            "description": "<p>Lien vers les categories du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "links.tailles",
            "description": "<p>Lien vers les ressources liée à la taille du sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "link",
            "optional": false,
            "field": "links.tailles.href",
            "description": "<p>Lien vers la taille du sandwich</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n{\n\"type\": \"ressource\",\n\"meta\": [\n  \"03/02/18\"\n],\n\"sandwich\": {\n  \"id\": 4,\n \"nom\": \"le bucheron\",\n  \"description\": \"un sandwich de bucheron : frites, fromage, saucisse, steack, lard grillé, mayo\",\n  \"type_pain\": \"baguette campagne\",\n  \"img\": null,\n  \"categories\": [\n    {\n      \"id\": 3,\n      \"nom\": \"traditionnel\"\n    },\n    {\n      \"id\": 4,\n      \"nom\": \"chaud\"\n    }\n  ],\n  \"tailles\": [\n    {\n      \"id\": 1,\n      \"nom\": \"petite faim\",\n      \"prix\": \"6.00\"\n    },\n    {\n      \"id\": 2,\n      \"nom\": \"complet\",\n      \"prix\": \"6.50\"\n    },\n    {\n      \"id\": 3,\n      \"nom\": \"grosse faim\",\n      \"prix\": \"7.00\"\n    },\n    {\n      \"id\": 4,\n      \"nom\": \"ogre\",\n      \"prix\": \"8.00\"\n    }\n  ]\n},\n\"links\": {\n  \"categories\": {\n    \"href\": \"/sandwichs/4/categories\"\n  },\n  \"tailles\": {\n    \"href\": \"/sandwichs/4/tailles\"\n  }\n}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Réponse : 404": [
          {
            "group": "Réponse : 404",
            "optional": false,
            "field": "MissingParameter",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas d'erreur",
          "content": "HTTP/1.1 404 Not Found\n {\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Ressource non disponible : /sandwichs/405\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Sandwichs"
  },
  {
    "type": "get",
    "url": "/sandwichs/{id}/tailles[/]",
    "title": "Obtenir la liste des taille d'un sandwichs",
    "group": "Sandwichs",
    "name": "GetTailleBySandId",
    "version": "0.1.0",
    "description": "<p>Accès à une ressource de type tailles : permet d'accéder à la représentation de la ressource tailles par l'id d'un sandwich. Retourne une représentation json de la ressource</p>",
    "success": {
      "fields": {
        "Succès : 200": [
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>type de la réponse, ici collection</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "meta",
            "description": "<p>méta-données sur la réponse</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Tring",
            "optional": false,
            "field": "meta.date",
            "description": "<p>date de production de la réponse global</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "meta.count",
            "description": "<p>nombre d'objets dans la ressource</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Object",
            "optional": false,
            "field": "tailles",
            "description": "<p>la ressource tailles retournée</p>"
          },
          {
            "group": "Succès : 200",
            "type": "Number",
            "optional": false,
            "field": "tailles.id",
            "description": "<p>Identifiant de la tailles associée au sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "tailles.nom",
            "description": "<p>Nom de la tailles associée au sandwich</p>"
          },
          {
            "group": "Succès : 200",
            "type": "String",
            "optional": false,
            "field": "tailles.description",
            "description": "<p>Description de la tailles associée au sandwich</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas de succès",
          "content": "    HTTP/1.1 200 OK\n\n {\n  \"type\": \"collection\",\n  \"meta\": {\n    \"0\": \"03/02/18\",\n    \"count\": 4\n  },\n  \"tailles\": [\n    {\n      \"id\": 1,\n      \"nom\": \"petite faim\",\n      \"description\": \"le sandwich rapide pour les petites faims, même si elles sont sérieuses\"\n    },\n    {\n      \"id\": 2,\n      \"nom\": \"complet\",\n      \"description\": \"le sandwich taille optimale pour un casse-croûte à toute heure\"\n    },\n    {\n      \"id\": 3,\n      \"nom\": \"grosse faim\",\n      \"description\": \"à partager, ou pour les affamés\"\n    },\n    {\n      \"id\": 4,\n      \"nom\": \"ogre\",\n      \"description\": \"pour les faims d'ogres, et encore ....\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Réponse : 404": [
          {
            "group": "Réponse : 404",
            "optional": false,
            "field": "MissingParameter",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "exemple de réponse en cas d'erreur",
          "content": "HTTP/1.1 404 Not Found\n\t{\n \t\"type\": \"error\",\n \t\"error\": 404,\n \t\"message\": \"Ressource non disponible : /sandwichs/160\"\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./api/rest.php",
    "groupTitle": "Sandwichs"
  }
] });
