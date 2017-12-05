<?php 
namespace lbs\control ;

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
        try {
            $cats = Categorie::where("id", "=", $args['id'])->firstOrFail();
        } catch (Exception $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /categorie/'.$args['id']));
            return $resp;
        }
        $resp = $resp->withJson($cats);
        return $resp;
            
        }
}