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



$router = new App\ConfigManager();



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
