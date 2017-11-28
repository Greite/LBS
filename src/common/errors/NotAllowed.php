<?php
/**
* 
*/
namespace lbs\common\errors;

class NotAllowed
{
	public static function error($rq, $rs, $methods){
		$rs = $rs->withStatus(405)->withHeader('Allow', implode(', ', $methods))->withHeader('Content-type', 'text/html');
		$rs->getBody()->write('Method must be one of: ' . implode(', ', $methods));
		return $rs;
	}
}