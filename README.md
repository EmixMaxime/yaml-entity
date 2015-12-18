# yaml-entity
The documentation in english will come soon.

# La documentation n'a pas été mise à jour /!\

## Informations
Le développement n'est pas terminé, il me reste encore à créer les exceptions. <br>
Des changements sont prévisibles.

## Description
Vous avez un petit site internet à budget réduit? Vous voulez avoir des données dynamiques mais sans base de données? <br>
Vous êtes alors sur le bon dépôt. <br>
Vous allez pouvoir avoir des données dynamiques sans base de données, en écrivant des fichiers YAML. <br>
L'exemple fournit, propose une gestion d'articles.

#### Dans l'exemple, j'utilise [Slim Framework](http://www.slimframework.com/ "Slim") pour le routing ainsi que [Twig](http://twig.sensiolabs.org/ "Twig") comme moteur de template.

## Installation
```
git clone https://github.com/EmixMaxime/yaml-entity.git
```
Ensuite, pour télécharger les dépendances avec composer :
```
composer install
```

## Documentation
La documentation est rédigée suivant l'exemple fournit. <br>
Les fichiers YAML doivent être dans le dossier **content**. <br>
Dans notre exemple nous avons un dossier "news" qui contiendra tous nos fichiers YAML qui seront nos articles.
Lorsqu'on veut travailler sur nos articles, il nous faut indiquer à notre EntityManager qu'on travaille sur **news** qui est le dossier contenant nos articles. <br>
```php
/**
* @param le dossier contenant les fichiers de travail (/content/news)
*/
$em = new \App\EntityManager('news'); // initialize the entityManager
```
L'entityManager étant initialisé, nous voulons récupérer tous les articles :
```php
$articles = $em->findAll();
```
**Un article correspond alors à un fichier .yaml dans le dossier /content/news**
Si vous souhaitez récupérer qu'un seul article par son nom **de fichier** : <br>
```php
$article = $em->find('example');
// or
$article = $em->findBy('name', 'example');
```
Les méthodes **findAll()** **find($name)** et **findBy($property, $value)** retourne un tableau associatif avec les différentes variables du fichier YAML. <br>
Le contenu est disponible dans la clef **content** de ce tableau, ainsi qu'une url vous est proposé dans la clef **url**.
Exemple :
```php
$em->find('example');
```
retourne : 
```php
[
  'title' => 'This is an another example without markdown syntaxe',
  'author' => 'Emix',
  'another_variable' => "Hello i'am an another variable!",
  'url' => '/news/example',
  'content' => '
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quaerat, vero maxime tempora tenetur fuga reiciendis, impedit ullam distinctio et consectetur! Consequuntur possimus earum, minima numquam perferendis obcaecati dicta ullam.
'
]
```

