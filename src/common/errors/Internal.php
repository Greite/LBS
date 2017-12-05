<?php
/**
* 
*/
namespace lbs\common\errors;

class Internal
{
	public static function error($rq, $rs, $error){
        $rs = $rs->withStatus(500)->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode(array('type' => 'error', 'error' => 500, 'message' => 'Internal Server Error')));
        return $rs;
	}
}