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

/** Debug Bar **/
use DebugBar\StandardDebugBar;
$debugbar = new StandardDebugBar();
$debugbar->addCollector(new DebugBar\Bridge\SlimCollector($app));

$debugbarRenderer = $debugbar->getJavascriptRenderer();
$view->appendData(['debugbarRenderer' => $debugbarRenderer]);
/** End Debug Bar **/

$site = App\App::getConfig('site');
$view->appendData(['site' => $site]);

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



$router = new App\Router();



$folders = App\App::getFolders();
//$test = $router->getUrl('news');
$view->appendData(['navigation' => $folders]);
$view->appendData(['router' => $router]);


foreach ($folders as $folder) {
	$app->get("/{$router->getUrl($folder)}", 'App\Controller:index')->name($folder);
	$app->get("/{$router->getUrl($folder)}/:article", 'App\Controller:show')->name($folder);
}

//$app->get('/news/:article', 'App\NewsController:show')->name('news.show');

$app->run();
