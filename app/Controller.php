<?php
namespace App;

use Slim\Slim;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Controller {

	private $entityManager;
	private $app;
	private $directory;

	public function __construct() {
		$app = Slim::getInstance();
		$this->directory = $app->router()->getCurrentRoute()->getName();
		$this->entityManager = new EntityManager($this->directory);
		$this->app = Slim::getInstance();
	}

	public function index() {
		$datas = $this->entityManager->findAll();

		$template = ConfigManager::getTemplate($this->directory);
		$templateDirectory = ConfigManager::getTemplateDirectory($this->directory);

		return $this->app->render("{$templateDirectory}/{$template}.twig", compact('datas'));
	}
	
	public function show($name) {
		$datas = $this->entityManager->find($name);

		$template = ConfigManager::getTemplate($this->directory, $name);
		$templateDirectory = ConfigManager::getTemplateDirectory($this->directory, $name);

		return $this->app->render("{$templateDirectory}/{$template}.twig", compact('datas'));
	}
}
