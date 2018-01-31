<?php

return [
	'settings' => [ 'displayErrorDetails' => true ,'tmpl_dir' => '../src/views' ],
	'view' => function($c) {
		return new\Slim\Views\Twig(
			$c['settings']['tmpl_dir'],['debug' => true, 'cache' => false ]
		);
	}
];