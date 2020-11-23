<?php 
session_start();
class View
{
	//we are going to use this property later
	public $data = array();

		//function for loading views(u can see in access controller how we are going to call it)
	public function load_view($dir_name, $subdir_name, $file_name)
	{
		require('./views/'.$dir_name.'/includes/header.php');
		require('./views/'.$dir_name.'/'.$subdir_name.'/'.$file_name.'.php');
		require('./views/'.$dir_name.'/includes/footer.php');	
	}
}