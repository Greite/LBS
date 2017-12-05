<?php
/**
* 
*/
namespace lbs\common\errors;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class NotAllowed
{
	public static function error(Request $rq, Response $rs, $methods){
		$rs = $rs->withStatus(405)->withHeader('Allow', implode(', ', $methods))->withHeader('Content-type', 'application/json');
		$rs->getBody()->write(json_encode(array('type' => 'error', 'error' => 405, 'message' => 'Methode non disponible')));
		return $rs;
	}
}