<?php 

class Router
{
	private $demand;
	private $allowed_routes = array('admin', 'director', 'professor', 'teacher', 'parent');
	
	public function __construct($demand)
	{
		
		$this->demand = $demand;
		// var_dump($this->demand->parts_of_url);
		
		$controller_url_demand = $this->demand->parts_of_url[3];
		
		if ($controller_url_demand == "" || $controller_url_demand == "access") {
			$controller = $this->pullInController('access');
			
			
			if (!array_key_exists('4', $this->demand->parts_of_url) || $this->demand->parts_of_url[4] == "") {
				$method = 'index';
			} else {
				$method =  $this->demand->parts_of_url[4];	
			}
			
			//call method if its part of class
			if (method_exists($controller, $method)) {
				$controller->$method();	
			} else {
				var_dump('method ' . $method . ' jos uvek nije definisan');
			}
			
			//and logic for other controllers,not access
		} elseif (in_array($controller_url_demand, $this->allowed_routes)) {
			// var_dump($controller_url_demand);
			if($this->check_is_demand_authorized($controller_url_demand)){
				$controller = $this->pullInController($controller_url_demand);
				if(!array_key_exists('4', $this->demand->parts_of_url) || $this->demand->parts_of_url[4] == ""){
					$method = 'index';
				} else {
					$method =  $this->demand->parts_of_url[4];
				}
				
				if (method_exists($controller, $method)) {
					$controller->$method();
				} else {
					// var_dump('method ' . $method . ' jos uvek nije definisan');
					$view = new View();
					$view->load_view('404', 'pages', 'index');
				}
			}
		} else {
			echo "nema ovaj kontroler"; //there is going to be error page
		}
	}
	
	public function pullInController($controller_url_demand)
	{
		$controllerName = 'Base' . ucfirst($controller_url_demand) . 'Controller';
		return new $controllerName($this->demand);
	}
	
	private function check_is_demand_authorized($controller_name)
	{
		// var_dump($_COOKIE);
		$user = Users::get_user_by_id($_COOKIE['id']);
		// var_dump($user);
		if ($user['role_name'] == $controller_name && $user['cookie'] == $_COOKIE['loginhash']){
			// echo 'pristup dozvoljen';
			return true;
		} else {
			echo 'Nemas dozvolu';	
			return false;
		}
	}
	
	
}