<?php

namespace App;
use Symfony\Component\Yaml\Yaml;

class App {

	private static function getBasePath() {
		return dirname(dirname(__FILE__));
	}

	public static function getEntityPath() {
		return self::getBasePath() . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR;
	}
	
	public static function getFolders() {
		$folders = array_diff(scandir(self::getEntityPath()), array('..', '.'));
		return $folders;
	}

	public static function getConfig($config) {
		$yaml = Yaml::parse(file_get_contents(self::getBasePath() . '/app/config/'.$config.'.yaml'));
		return $yaml;
	}
}