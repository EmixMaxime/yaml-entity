<?php
$app->get('/admin', 'App\AdminController:index')->name('admin');

$app->get('/test/:slug/:two', 'App\AppController:test')->name('test');

$app->get('/emix/:slug/:lol', function($slug, $lol) {
   echo $slug;
    echo $lol;
})->name('emix');

//var_dump($app->urlFor('emix', ['slug' => 'coucou', 'lol' => 'ntm']));

//var_dump($app->urlFor('test', ['slug' => 'coucou']));

foreach ($folders as $folder) {
    $app->get("/{$router->getUrl($folder)}", 'App\AppController:index');//->name($folder);
    $app->get("/{$router->getUrl($folder)}/:article", 'App\AppController:show');//->name($folder);
}

