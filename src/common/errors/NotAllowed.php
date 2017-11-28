<?php
/**
* 
*/
namespace lbs\common\errors;

class NotAllowed
{
	public static function error($rq, $rs, $methods){
		return ['response']
			->withStatus(405)
			->withHeader('Allow', implode(', ', $methods))
			->withHeader('Content-type', 'text/html')
			->write('Method must be one of: ' . implode(', ', $methods));
	}
}