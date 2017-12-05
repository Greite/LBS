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
        $resp = $resp->withJson(array('type' => 'created', 'code' => 201, 'message' => 'Successfully created'));
        return $resp;
    }

    public function getSandwichs(Request $req, Response $resp, $args){

        $type = $req->getQueryParam('type', NULL);
        $page = $req->getQueryParam('page', 1);
        $size = $req->getQueryParam('size',10);

        $requete = Sandwich::select(['nom','description','type_pain']);
        if(!is_null($type)){
            $requete = $requete->where('type_pain','like',''.$type.'');
        }

        $requete = $requete->get();
        
        $count = $requete->count();
        $countStr = strval($count);
        $date = date('d/m/y');
        
        $tab = [
            "type"=>"collection",
            "meta"=>[
                "count"=>$countStr,
                "date"=>$date
            ],
            "sandwichs"=>$requete
        ];

        $resp = $resp->withHeader('Content-Type', "application/json;charset=utf-8");
        $resp->getBody()->write(json_encode($tab));
        return $resp;

            /*
            $last  = intdiv($count,$size)+1;
            if($page > $page){
                $page = $last;
            }

            $rows = $q->skip(($page-1)*$size)->take($size)->get()->toArray();
            $sandwiches=[];

        }catch(ModelNotFoundException $e){

        }/*/

    }
}