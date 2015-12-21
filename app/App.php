<?php

namespace App;

use Slim\Slim;
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
                $yamlDatas = Yaml::parse(file_get_contents(self::getBasePath() . '/app/config/'.$config.'.yaml'));
                foreach($yamlDatas as $key => &$data) {
                    if($key == 'navigation') {
                foreach($data as $key => &$items) {
                    if($key == 'items') {
                        foreach($items as &$item) {
                            if(isset($item['route'])) {
                                if(isset($item['parameter'])) {
                                    foreach($item as $key => &$value) {
                                        $parameters = [];
                                        if($key == 'parameter') {
                                            foreach($value as $k => &$v) {
                                                $parameters[$k] = $v;
                                            }
                                            $item['path'] = Slim::getInstance()->urlFor($item['route'], $parameters);
                                        }


                                    }
                                } else $item['path'] = Slim::getInstance()->urlFor($item['route']);
                            }
                        }
                    }
                }
            }
        }
        var_dump($yamlDatas['navigation']);
		return $yamlDatas;
	}
}