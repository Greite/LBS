<?php 
namespace lbs\control ;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categorie as Categorie;
use \lbs\model\Sandwich;

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

    public function updateCategorie(Request $req, Response $resp, $args){
        $parsedBody = $req->getParsedBody();
        try{
            $cat = Categorie::findOrFail($args['id']);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /categorie/'.$args['id']));
            return $resp;
        }
        if (!isset($parsedBody['nom']) || !isset($parsedBody['description'])) {
            return \lbs\common\errors\BadUri::error($req, $resp);
        }else{
            $cat->nom = filter_var($parsedBody['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cat->description = filter_var($parsedBody['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cat->save();
            $resp = $resp->withStatus(200);
            $resp = $resp->withJson(array('id' => $cat->id, 'nom' => $cat->nom, 'description' => $cat->description));
            return $resp;
        }

    }

    public function getSandwichs(Request $req,Response $resp,array $args){

        $type = $req->getQueryParam('type',null);
        $img = $req->getQueryParam('img',null);
        $size = $req->getQueryParam('size',10);
        $page = $req->getQueryParam('page',1);
        $skip = $size*($page-1);

        $requete = sandwich::select('id','nom','type_pain');

        if(!is_null($type)){
            $requete=$requete->where('type_pain','LIKE',''.$type.'%');
        }
        if(!is_null($img)){
            $q=$q->where('img','LIKE',''.$img.'%');
        }

        $tailleRequete = $requete->get();
        $total = count($tailleRequete);

        $totalItem = $size + $skip;
        if($totalItem>$total){
            $page=floor(($total/$size));
        }
        if($page<=0){
          $page=1;
        }
        
        $skip = $size*($page-1);
        $requete=$requete->skip($skip)->take($size);
        $listeSandwichs = $requete->get();
        
        $resp=$resp->withHeader('Content-Type','application/json');
        $resp=$resp->withHeader('Count',$total);
        $resp=$resp->withHeader('Size',$size);
        $resp=$resp->withHeader('Page',$page);
        for($i=0;$i<sizeof($listeSandwichs);$i++){
        $sandwichs[$i]["sandwich"]=$listeSandwichs[$i];
            $href["href"]="sandwichs/".$listeSandwichs[$i]['id'];        
            $tab["self"]=$href;
            $sandwichs[$i]["links"]=$tab;
        }
        $resp->getBody()->write(json_encode($sandwichs));
        return $resp;
    }

    public function getSandwichsId(Request $req, Response $resp, $args) {
        try{
            $sand = Sandwich::find($args['id'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /sandwichs/'.$args['id']));
            return $resp;
        }
        $resp = $resp->withJson($sand);
        return $resp;
        }

}
