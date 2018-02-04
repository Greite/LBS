<?php 
namespace lbs\control ;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categorie;
use \lbs\model\Sandwich;
use \lbs\model\Commande;
use \lbs\model\Carte;
use \lbs\model\User;


class GestController{

    private $c = null;

    public function __construct($container)
    {
        $this->c = $container;
    }
    
    public function getSandsbyCats(Request $req, Response $resp, $args) {
            
        $cats = Categorie::with('sandwichs')->get();

        return $this->c['view']->render($resp,'ListeSandwichs.twig', [
        'cats' => $cats
    ]);
    }

    public function deleteSandwich(Request $req, Response $resp, $args) {

        $sand = Sandwich::find($args['id']);
        $sand->delete();
        return $resp->withRedirect('/sandwichs');
    }

    public function getAddSandwich(Request $req, Response $resp, $args){
        try{
            $cats = Categorie::with('sandwichs')->get();
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible'));
            return $resp;
        }
        return $this->c['view']->render($resp,'AjouterSandwich.twig', ['cats' => $cats]);
    }

    public function addSandwich(Request $req, Response $resp, $args){

                $parsedBody = $req->getParsedBody();
                $sand = new Sandwich;
                $sand->nom = filter_var($parsedBody['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
                $sand->description = filter_var($parsedBody['description'], FILTER_SANITIZE_SPECIAL_CHARS);
                $sand->type_pain = filter_var($parsedBody['type_pain'], FILTER_SANITIZE_SPECIAL_CHARS);
                $sand->save();
                $i=0;
                foreach ($parsedBody as $key => $value){
                    if($i>2) {
                        if ($value == "on"){
                            $sand->categories()->attach([$key]);
                        }
                    }
                    $i++;
                }

                return $resp->withRedirect('/sandwichs');
    }

    public function getPutSandwich(Request $req, Response $resp, $args){
        return $this->c['view']->render($resp,'ModifierSandwich.twig', [
            'id' => $args['id']
        ]);
    }

    public function putSandwich(Request $req, Response $resp, $args){
        $parsedBody = $req->getParsedBody();   
        $sand = Sandwich::find($args['id']);
        
        if (!isset($parsedBody['nom']) || !isset($parsedBody['description'])) {
            return \lbs\common\errors\BadUri::error($req, $resp);
        }else{
            $sand->nom = filter_var($parsedBody['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $sand->description = filter_var($parsedBody['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $sand->type_pain = filter_var($parsedBody['type_pain'], FILTER_SANITIZE_SPECIAL_CHARS);
            $sand->save();
            return $resp->withRedirect('/sandwichs');
        }
    }

    public function getConnexion(Request $req, Response $resp, $args){
        return $this->c['view']->render($resp,'Connexion.twig');
    }

    public function login(Request $req, Response $resp, $args){
        $log = User::select()->first();

        if(empty($_POST['identifiant']) || empty($_POST['password']) || $_POST['password'] != $log->password || $_POST['identifiant'] != $log->login){
            $mess = "Les champs ne sont pas remplis ou ne correspondent pas";
            return $this->c['view']->render($resp, 'Connexion.twig',['mess'=>$mess]);
        }
        else {
            return $this->c['view']->render($resp,'Login.twig');
        }
    }

    public function getCategories(Request $req, Response $resp, $args){
        $tablal = Categorie::all();
        $t = count($tablal);
        $resp = $resp->withHeader('Content-Type', "application/json;charset=utf-8");
        $tabcat = [
            "type"=>'collection',
            "meta"=>[$date=date('d/m/y'),"count"=>$t],
            "categories"=>$tablal
        ];
        $resp = $resp->withJson($tabcat);
        return $resp;
    }
    
    public function getCategoriesId(Request $req, Response $resp, $args) {
        try{
            $cats = Categorie::findorFail($args['id']);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /categorie/'.$args['id']));
            return $resp;
        }
        $tabcatid=[
            "type"=>"ressource",
            "meta"=>[$date=date('d/m/y')],
            "categories"=>$cats
        ];

        $resp = $resp->withJson($tabcatid);
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
}