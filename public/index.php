<?php //se define que el lenguaje de programacion es PHP

use Illuminate\Foundation\Application;//se importa la clase Applicaciton, que esta en Illuminate/Foundation, es el núcleo de
//Laravel, Application es una cosa gigante, que compone la base de la aplicacion
use Illuminate\Http\Request;//la clase Request es una representacion en clase, de una solicitud HTTP

define('LARAVEL_START', microtime(true));//define es una declaracion PHP para definir constantes
//'LARAVEL_START' es el nombre de la constante
//microtime: funcion de php que devuelve los segundos con microsegundos
// transcurridose desde el 1 de enero de 1970 hasta la fecha de ejecucion de 
//la app, devuelve un string en el que primero estan los microsegundos y despues los segundos
//el parametro en true devuelve un float con segundos y microsegundos
//En pocas palabras, se define un parametro comparativo, con el cual se puede medir el tiempo preciso de ejecucion del programa
//expresado en segundos con microsegundos


// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance; //si el archivo maintenance existe, se ejecuta. Al parecer solo se ejecuta con php artisan down
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */ //La eriqueta incia que la App es de la clase Application
$app = require_once __DIR__.'/../bootstrap/app.php'; //se usa la instancia de clase Application, se inicia

$app->handleRequest(Request::capture()); //la aplicacion maneja la solicitud HTTP que le llegue, es la parte mas elemental
//la aplicacion, handleReuqest es un metodo de la var app, de tipo Application
