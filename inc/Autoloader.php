<?php namespace rest\inc;
/**
 * 
 * @author janner
 *
 */
class Autoloader {
	public static function register() {
		spl_autoload_register(function ($class) {
			$nameSpace = 'rest';
			if (false !== strpos($class, $nameSpace)) {
				$class = mb_substr($class, mb_strlen($nameSpace));
				$file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
				$file = dirname(dirname(__FILE__)) . $file;
				if (file_exists($file)) {
					require $file;
					return true;
				} else {
					return false;
				}
			}
		});
	}
}