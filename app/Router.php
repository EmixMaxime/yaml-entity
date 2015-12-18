<?php

namespace App;
use Symfony\Component\Yaml\Yaml;


class Router {

	public static function getUrl($folder) {
		// On va lire le fichier "config.yaml" dans le dossier $folder
		// On le parse et on retourne l'url précisée, sinon l'url c'est son nom ($folder)
		$path = App::getEntityPath() . $folder . DIRECTORY_SEPARATOR . 'config.yaml';
		if(file_exists($path)) {
			 $yaml = Yaml::parse(file_get_contents($path));
			 if($yaml['url']) return $yaml['url'];
		}
		return $folder;
	}	
}