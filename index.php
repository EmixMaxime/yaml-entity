<?php
require 'vendor/autoload.php';
$templatesPath = dirname(__FILE__) . '/templates';

$config = [
	'debug' => true,
	'mode' => 'development',
	'view' => new \Slim\Views\Twig(),
	'templates.path' => $templatesPath,
];

$app = new \Slim\Slim($config);
$view = $app->view();

/** Whoops errors **/
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
/* -- */

/** Debug Bar **/
use DebugBar\StandardDebugBar;
$debugbar = new StandardDebugBar();
$debugbar->addCollector(new DebugBar\Bridge\SlimCollector($app));

$loader = new Twig_Loader_Filesystem('.');
$env = new DebugBar\Bridge\Twig\TraceableTwigEnvironment(new Twig_Environment($loader));
$debugbar->addCollector(new DebugBar\Bridge\Twig\TwigCollector($env));

$debugbarRenderer = $debugbar->getJavascriptRenderer();
$view->appendData(['debugbarRenderer' => $debugbarRenderer]);
/* -- */

$view->parserOptions['debug'] = true;
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new \Twig_Extension_Debug()
);

/*$app->get('/', function() use($app) {
	$app->render('index.twig');
});*/

$app->get('/', function() use($app) {
	$em = new \App\EntityManager('news');
	$news = $em->findAll();

	$app->render('index.twig', compact('news'));
})->name('home');

/** Inject variable into twig **/
$folders = App\App::getFolders();
$view->appendData(['navigation' => $folders]);

$router = new App\ConfigManager();
$view->appendData(['router' => $router]);
/**  ---- **/


require "routes.php";

/** Inject variable into twig **/
$site = App\App::getConfig('site');
$view->appendData(['site' => $site]);
/**  ---- **/

//$app->get('/emix/:article', 'App\AppController:test')->name('news.show');

$app->run();