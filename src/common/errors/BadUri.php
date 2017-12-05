<?php
/**
* 
*/
namespace lbs\common\errors;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BadURI
{
	public static function error(Request $rq, Response $rs){
		$rs = $rs->withStatus(400)->withHeader('Content-type', 'application/json');
		$rs->getBody()->write(json_encode(array('type' => 'error', 'error' => 400, 'message' => 'Bad Request')));
		return $rs;
	}
}