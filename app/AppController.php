<?php
namespace App;

use Slim\Slim;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AppController extends Controller{

	private $entityManager;
	private $directory;

	public function __construct() {
		parent::__construct();
//		$this->directory = $this->app->router()->getCurrentRoute()->getName();
		$this->directory = 'news';
		$this->entityManager = new EntityManager($this->directory);
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

	public function test($slug) {
		echo $slug;
		die;
	}
}
