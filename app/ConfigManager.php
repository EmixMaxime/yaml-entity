<?php

namespace App;
use Symfony\Component\Yaml\Yaml;


class ConfigManager {

	private static function getPath($folder) {
		$path = App::getEntityPath() . $folder . DIRECTORY_SEPARATOR . 'config.yaml';
		return $path;
	}

	public static function getUrl($folder) {
		// On va lire le fichier "config.yaml" dans le dossier $folder
		// On le parse et on retourne l'url précisée, sinon l'url c'est son nom ($folder)
		$path = self::getPath($folder);
		if(file_exists($path)) {
			 $yaml = Yaml::parse(file_get_contents($path));
			 if($yaml['url']) return $yaml['url'];
		}
		return $folder;
	}

	public static function getTemplate($folder, $file = null) {
		$path = self::getPath($folder);
		$yaml = Yaml::parse(file_get_contents($path));
		if(isset($yaml[$file]['template'])) return $yaml[$file]['template'];
		if(isset($yaml['template'])) return $yaml['template'];
		if(!$file) return 'index';
		return $folder;
	}

	public static function getTemplateDirectory($folder, $file = null) {
		$path = self::getPath($folder);
		$yaml = Yaml::parse(file_get_contents($path));
		if(isset($yaml[$file]['templateDirectory'])) return $yaml[$file]['templateDirectory'];
		if(isset($yaml['templateDirectory'])) return $yaml['templateDirectory'];
		return $folder;
	}
}