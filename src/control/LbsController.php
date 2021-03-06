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
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;

class LbsController{

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
            $requete = $requete->where('img','LIKE',''.$img.'%');
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
        $date = explode(" ", $comm->date_livraison);
        $livraison = array('date' =>$date[0], 'heure' => $date[1]);
        $commande = array('id' => $comm->id, 'nom_client' => $comm->nom_client, 'prenom_client' => $comm->prenom_client, 'mail_client' => $comm->mail_client, 'livraison' => $livraison, 'token' => $comm->token);
        $tabcomid=[
            "type"=>"ressource",
            "meta"=>[$date=date('d/m/y')],
            "categories"=>$commande
        ];
        $resp = $resp->withJson($tabcomid);
        return $resp;
    }

    public function addCommande(Request $req, Response $resp, $args){
        $parsedBody = $req->getParsedBody();
        $com = new Commande;
        $uuid4 = Uuid::uuid4();
        $com->id = $uuid4;

        if(is_null($parsedBody['nom_client']) || is_null($parsedBody['date_livraison']) || is_null($parsedBody['mail_client']) || filter_var($parsedBody['mail_client'], FILTER_VALIDATE_EMAIL) === false){
            $message="";
            if(is_null($parsedBody['nom_client'])){
                $message=$message."Veuillez renseigner le nom du client";
            }
            if(is_null($parsedBody['date_livraison'])){
                if (!is_null($message)){
                    $message=$message." / ";
                }
                $message=$message."Veuillez renseigner la date de livraison";
            }
            if(is_null($parsedBody['mail_client'])){
                if (!is_null($message)){
                    $message=$message." / ";
                }
                $message=$message."Veuillez renseigner le mail du client ";
            }
            if(filter_var($parsedBody['mail_client'], FILTER_VALIDATE_EMAIL) === false){
                if (!is_null($message)){
                    $message=$message." / ";
                }
                $message=$message."Mauvais format de mail";
            }
            $resp = $resp->withStatus(409);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 409, 'message' => $message));
            return $resp;
        }
        else{
            $com->nom_client = filter_var($parsedBody['nom_client'], FILTER_SANITIZE_SPECIAL_CHARS);
            $com->prenom_client = filter_var($parsedBody['prenom_client'], FILTER_SANITIZE_SPECIAL_CHARS);
            $com->mail_client = filter_var($parsedBody['mail_client'], FILTER_SANITIZE_SPECIAL_CHARS);
            $com->date_livraison = filter_var($parsedBody['date_livraison'], FILTER_SANITIZE_SPECIAL_CHARS);
            $com->etat = 0;
            $token = random_bytes(32);
            $token = bin2hex($token);
            $com->token = $token;
            if(!is_null($parsedBody['carte'])){
                try {
                    $secret = "test";
                    $h = $req->getHeader('Authorization')[0];
                    $tokenstring = sscanf($h, "Bearer %s")[0];
                    $tok = JWT::decode($tokenstring, $secret, ['HS512']);
                    if($tok->uid != $parsedBody['carte']){
                        $resp = $resp->withStatus(401);
                        $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Le token ne correspond pas"));
                        return $resp;
                    }else{
                        $carte = Carte::select('mail','date_expiration','montant')->where("id_carte","=",$args['id'])->first();
                        $com->carte = $carte->id_carte;
                    }

                }catch(ExpiredException $e) {
                    $resp = $resp->withStatus(401);
                    $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "La carte a expirée"));
                    return $resp;
                }catch(SignatureInvalidException $e) {
                    $resp = $resp->withStatus(401);
                    $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Mauvaise signature"));
                    return $resp;
                }catch(BeforeValidException $e) {
                    $resp = $resp->withStatus(401);
                    $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Les information ne correspondent pas"));
                    return $resp;
                }catch(\UnexpectedValueException $e) {
                    $resp = $resp->withStatus(401);
                    $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Les informations ne correspondent pas"));
                    return $resp;
                }
            }
            $com->save();
            $resp = $resp->withStatus(201);
            $date = explode(" ", $com->date_livraison);
            $livraison = array('date' =>$date[0], 'heure' => $date[1]);
            $commande = array('nom_client' => $com->nom_client, 'mail_client' => $com->mail_client, 'livraison' => $livraison, 'id' => $uuid4, 'token' => $token);
            $resp = $resp->withHeader("Location" , "/commandes/".$uuid4);
            $resp = $resp->withJson(array('commande' => $commande));

            return $resp;
        }
    }

    public function addCarte(Request $req, Response $resp, $args){
        $parsedBody = $req->getParsedBody();
        $carte = new Carte;
        $token = random_bytes(5);
        $token = bin2hex($token);
        $carte->id_carte = $token;
        $carte->mail = filter_var($parsedBody['mail'], FILTER_VALIDATE_EMAIL);
        $carte->password = password_hash(filter_var($parsedBody['password'], FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT);
        $carte->date_expiration = date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));;
        $carte->montant = 0;
        $carte->save();
        $resp = $resp->withStatus(201);
        $date = explode(" ", $carte->date_expiration);
        $card = array('id' => $token, 'mail' => $carte->mail, 'date_expiration' => $date[0]);
        $resp = $resp->withJson(array('carte' => $card));

        return $resp;

    }

    public function authentificationCarte(Request $req, Response $resp, $args){

        if($req->hasHeader('Authorization') === false ) {
            $resp = $resp->withStatus(401);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Header Authorization manquant"));
            return $resp;
        }
        else{
            $head = $req->getHeader('Authorization');
            $t = substr($head[0],5);
            $c = base64_decode($t);
            $couple = explode(':', $c);
            $carte = Carte::where("id_carte","=",$args['id'])->first();

            if(($couple[0] == $carte->mail) && (password_verify($couple[1],$carte->password))){

                $secret = "test";

                $token =JWT::encode( ['iss'=>'http://api.lbs.local:10080/carte/'.$carte->id_carte.'/auth',
                                   'aud'=>'http://api.lbs.local:10800/',
                                    'iat'=>time(),
                                     'exp'=>time()+3600,
                                     'uid'=>(string) $carte->id_carte],
                                    $secret,'HS512');

                $resp = $resp = $resp->withJson($token);
                return $resp;
            }
            else{
                $resp = $resp->withStatus(401);
                $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Les informations ne correspondent pas"));
                return $resp;
            }
        }

    }

    public function getCarte(Request $req, Response $resp, $args){

        try {
            $secret = "test";
            $h = $req->getHeader('Authorization')[0];
            $tokenstring = sscanf($h, "Bearer %s")[0];
            $token = JWT::decode($tokenstring, $secret, ['HS512']);

            if($token->uid != $args['id']){
                $resp = $resp->withStatus(401);
                $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Le token ne correspond pas"));
                return $resp;
            }else{
                $carte = Carte::select('mail','date_expiration','montant')->where("id_carte","=",$args['id'])->first();
                $resp = $resp->withJson($carte);
                return $resp;
            }

        }catch(ExpiredException $e) {
            $resp = $resp->withStatus(401);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "La carte a expirée"));
            return $resp;
        }catch(SignatureInvalidException $e) {
            $resp = $resp->withStatus(401);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Mauvaise signature"));
            return $resp;
        }catch(BeforeValidException $e) {
            $resp = $resp->withStatus(401);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Les information ne correspondent pas"));
            return $resp;
        }catch(\UnexpectedValueException $e) {
            $resp = $resp->withStatus(401);
            $resp = $resp->withJson(array('type' => 'error', 'error' => 401, 'message' => "Les informations ne correspondent pas"));
            return $resp;
        }
    }
}