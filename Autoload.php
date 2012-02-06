<?php

class Autoload {
	public static function load($class) {
		require dirname(__FILE__) . '/' . str_replace('_', '/', $class) . '.php';
	}
}

spl_autoload_register('Autoload::load');