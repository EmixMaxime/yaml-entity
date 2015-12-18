<?php
namespace App;

use VKBansal\FrontMatter\Document;
use VKBansal\FrontMatter\Parser;
use Slim\Slim;

class EntityManager {

	private $path;
	private $app;
	private $entity;

	private $files;

	public function __construct($entity) {
		$this->entity = $entity;
		$this->app = Slim::getInstance();
		$this->yfm = new YamlFileManager($entity);
	}
	
	public function find($name) {
		if($this->yfm->has($name)) {
			$doc = $this->yfm->parse($name);
			return $doc->getConfig();
		}
		return false;
	}

	public function findAll() {
		$docs = [];
		$news = [];
		foreach ($this->yfm->files as $file) {
			if($file['name'] != 'config') $docs[] = $this->yfm->parse($file['name']); // On ne parse pas le fichier config.yaml !!
		}
		foreach ($docs as $doc) {
			$news[] = $doc->getConfig();
		}
		return $news;
	}

	public function findBy($property, $value) {
		if($property == 'name') {
			return $this->find($value);
		}
		$results = [];

		foreach ($this->yfm->files as $file) {
			$doc = $this->yfm->parse($file['name']);
			if($doc->getConfig($property) == $value) {
				$results[] = $doc->getConfig();
			}
		}
		return $results;
	}
}
