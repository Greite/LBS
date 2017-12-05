<?php 
namespace lbs\control ;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categorie as Categorie;

class LbsController{

    public function categories(Request $req, Response $resp, $args){
        $tablal = Categorie::all();
        $resp = $resp->withHeader('Content-Type', "application/json;charset=utf-8");
        $resp->getBody()->write(json_encode($tablal->toArray()));
        return $resp;
    }
    
    public function categoriesId(Request $req, Response $resp, $args) {
        try{
            $cats = Categorie::where("id", "=", $args['id'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /categorie/'.$args['id']));
            return $resp;
        }
        $resp = $resp->withJson($cats);
        return $resp;
        }

    public function addCategorie(Request $req, Response $resp, $args){
        $parsedBody = $req->getParsedBody();
        $cat = new Categorie;
        $cat->nom = filter_var($parsedBody['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
        $cat->description = filter_var($parsedBody['description'], FILTER_SANITIZE_SPECIAL_CHARS);
        $cat->save();
        $resp = $resp->withStatus(201);
        $resp = $resp->withHeader('Location', "/categories/".$cat->id);
        $resp = $resp->withJson(array('id' => $cat->id, 'nom' => $cat->nom, 'description' => $cat->description));
        return $resp;
    }
}