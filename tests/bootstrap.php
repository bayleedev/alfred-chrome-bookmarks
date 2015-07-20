<?php

namespace alfmarks;

require_once __DIR__ . '/../src/alfmarks.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Unit.php';

function glob($pattern) {
	return Mock::glob($pattern);
}

Mock::filter('glob', function($pattern) {
	return array($pattern);
});

class Mock {
	public static $data = array();

	public static function setup() {
		static::$data = array();
	}

	public static function filter($method, $handler) {
		static::$data[$method] = $handler;
	}

	public static function __callStatic($method, $params) {
		return call_user_func_array(static::$data[$method], $params);
	}
}
