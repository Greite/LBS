<?php 
namespace lbs\control ;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categorie;
use \lbs\model\Sandwich;
use \lbs\model\Commande;
use Ramsey\Uuid\Uuid;

class LbsController{

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

    public function getSandwichs(Request $req,Response $resp, $args){

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
            $q = $q->where('img','LIKE',''.$img.'%');
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
        $tabFinal = [
            "type"=>"collection",
            "meta"=>[
                "count"=>$total,
                $date=date('d/m/y')
            ],
            "sandwichs" => $sandwichs
            ];
        $resp->getBody()->write(json_encode($tabFinal));
        return $resp;
    }

    public function getSandwichsId(Request $req, Response $resp, $args) {
        try{
            $sand = Sandwich::findorFail($args['id']);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /sandwichs/'.$args['id']));
            return $resp;
        }
        $sandwich = Sandwich::findorFail($args['id']);
        $sand['categories'] = $sandwich->categories()->select(['id', 'nom'])->get()->toArray();
        $sand['tailles'] = $sandwich->tailleSandwichs()->select(['id', 'nom', 'tarif.prix'])->get()->toArray();

        $tabsandid=[
            "type"=>"ressource",
            "meta"=>[$date=date('d/m/y')],
            "sandwich"=>$sand,
            "links"=> [
                "categories" => [
                    "href" => "/sandwichs/".$args['id']."/categories"
                    //"href" => $this->c->get('router')->pathFor('sandwich2cat', $args['id'])
                    ],
                "tailles" => [
                    "href" => "/sandwichs/".$args['id']."/tailles"
                    //"href" => $this->c->get('router')->pathFor('sandwich2taille', $args['id'])
                ]
            ]
        ];

        $resp = $resp->withJson($tabsandid);
        return $resp;
    }

    public function getSandsOfCat(Request $req, Response $resp, $args) {
        try{
            $sands = Categorie::findorFail($args['id'])->sandwichs;
            $t = count($sands);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /categories/'.$args['id']));
            return $resp;
        }

        $tabsandcat=[
            "type"=>"collection",
            "meta"=>[$date=date('d/m/y'),"count"=>$t],
            "categories"=>$sands
        ];
        $resp = $resp->withJson($tabsandcat);
        return $resp;
    }

    public function getCatsOfSand(Request $req, Response $resp, $args) {
        try{
            $cats = Sandwich::findorFail($args['id'])->categories;
            $t= count($cats);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /sandwichs/'.$args['id']));
            return $resp;
        }

        $tabcatsand=[
            "type"=>"collection",
            "meta"=>[$date=date('d/m/y'),"count"=>$t],
            "categories"=>$cats
        ];
        $resp = $resp->withJson($tabcatsand);
        return $resp;
    }

    public function getTaillesOfSand(Request $req, Response $resp, $args) {
        try{
            $tailles = Sandwich::findorFail($args['id'])->tailleSandwichs;
            $t= count($tailles);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /sandwichs/'.$args['id']));
            return $resp;
        }

        $tabtailles=[
            "type"=>"collection",
            "meta"=>[$date=date('d/m/y'),"count"=>$t],
            "tailles"=>$tailles
        ];
        $resp = $resp->withJson($tabtailles);
        return $resp;
    }
    
    public function getCommande(Request $req, Response $resp, $args) {
        try{
            $comm = Commande::findorFail($args['id']);
        } catch (ModelNotFoundException $e) {
            $resp = $resp->withStatus(404);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 404, 'message' => 'Ressource non disponible : /commande/'.$args['id']));
            return $resp;
        }
        $tabcomid=[
            "type"=>"ressource",
            "meta"=>[$date=date('d/m/y')],
            "categories"=>$comm
        ];
        $resp = $resp->withJson($tabcomid);
        return $resp;
    }

    public function addCommande(Request $req, Response $resp, $args){
        $parsedBody = $req->getParsedBody();
        $com = new Commande;
        $uuid4 = Uuid::uuid4();
        $com->id = $uuid4;
        $com->nom_client = filter_var($parsedBody['nom_client'], FILTER_SANITIZE_SPECIAL_CHARS);
        $com->prenom_client = filter_var($parsedBody['prenom_client'], FILTER_SANITIZE_SPECIAL_CHARS);
        $com->mail_client = filter_var($parsedBody['mail_client'], FILTER_SANITIZE_SPECIAL_CHARS);
        $com->date_livraison = filter_var($parsedBody['date_livraison'], FILTER_SANITIZE_SPECIAL_CHARS);
        $com->etat = 0;
        $token = random_bytes(32);
        $token = bin2hex($token);
        $com->token = $token;
        $com->save();
        $resp = $resp->withStatus(201);
        $date = explode(" ", $com->date_livraison);
        $livraison = array('date' =>$date[0], 'heure' => $date[1]);
        $commande = array('nom_client' => $com->nom_client, 'mail_client' => $com->mail_client, 'livraison' => $livraison, 'id' => $uuid4, 'token' => $token);
        $resp = $resp->withJson(array('commande' => $commande));

        return $resp;
    }
}
