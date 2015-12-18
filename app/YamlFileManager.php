<?php
namespace App;

use cebe\markdown\GithubMarkdown;
use VKBansal\FrontMatter\Parser;

class YamlFileManager {

	private $entity; // example news in /content/news
	public $files;

	/**
	* $entity = le dossier de travail
	*/
	
	public function __construct($entity) {
		$this->entity = $entity;
		$this->files = $this->getFilesBeta();
	}

	public function getEntityPath() {
		return App::getEntityPath() . $this->entity;
	}

	public function has($name) {
		foreach($this->files as $file) {
			if($file['name'] == $name) {
				return true;
			}
		}
		return false;
	}

	private function getFilesBeta() {
		$filesinfo = [];
		$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getEntityPath()));
		$files = new \RegexIterator($files, '#^.+\.yaml$#i', \RecursiveRegexIterator::GET_MATCH);
		foreach($files as $file) {
			$file = $file[0]; $fileinfo = [];
			$pathinfo = pathinfo($file, PATHINFO_FILENAME); $pathinfo = explode('.', $pathinfo);

			$fileinfo['name'] = $pathinfo[0];

			if(isset($pathinfo[1])) {
				$fileinfo['parser'] = $pathinfo[1];
				$fileinfo['fullname'] = $pathinfo[0] . '.' . $pathinfo[1];
			} else {
				$fileinfo['parser'] = NULL;
			 	$fileinfo['fullname'] = $fileinfo['name'];
		 }

			$fileinfo['path'] =  $this->getEntityPath() . DIRECTORY_SEPARATOR . $fileinfo['fullname'] . '.yaml';

			$name = $fileinfo['name'];
			if(isset($filesinfo[$name])) {
				throw new \Exception("Deux fichiers ont le meme nom");
			}
			$filesinfo[$fileinfo['name']] = $fileinfo;
		}
		return $filesinfo;
	}

	public function parse($filename) {
		foreach($this->files as $file) {
			if($file['name'] == $filename) {
				$doc = Parser::parse(file_get_contents($file['path']))->setConfig('url', ConfigManager::getUrl($this->entity).'/'.$file['name']);
				if($file['parser']) {
					$html = self::parseMarkdown($doc->getContent());
				} else {
					$html = $doc->getContent();
				}
				$doc->setConfig('content', $html);
				return $doc;
			}
		}
	}

	public function parseMarkdown($content) {
		$parser = new GithubMarkdown();
		$parser->enableNewlines = true;
		return $parser->parse($content); // Parser markdown
	}
}
