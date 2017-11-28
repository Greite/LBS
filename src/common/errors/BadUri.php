<?php
/**
* 
*/
namespace lbs\common\errors;

class BadURI
{
	public static function error($rq, $rs){
		return ['response']
			->withStatus(400)
			->withHeader('Content-type', 'text/html')
			->write('BadURI');
	}
}