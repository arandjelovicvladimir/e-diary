<?php 

 ActiveRecord\Config::initialize(function($cfg)
	 {
	     $cfg->set_model_directory('models');
	     $cfg->set_connections(array(
	         'development' => 'mysql://root:@localhost/dbschool'));
	 });



class Active extends ActiveRecord\Model {

	static $table = 'users';


}


?>