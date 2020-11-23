<?php
require('./vendor/autoload.php');

require_once './activerecord/ActiveRecord.php';



require('./db.php');	
require('./constants.php');


$db = new DB();
     
spl_autoload_register(function($class){
	require('./controllers/'.$class.'.php');
});

foreach (glob('./models/*') as $model_name) {
	require($model_name);
}

foreach (glob('./classes/*') as $class_name) {
	require($class_name);
}

$demand = new Demand();
$router = new Router($demand);
