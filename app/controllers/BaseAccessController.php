<?php 


class BaseAccessController 
{	
	public function index()
	{
		$view = new View();
		$view->load_view('access', 'screen', 'formlog');
	}
	
	public function login()
	{
		$username = $_POST['login_username'];
		$password = $_POST['login_password'];

		$user_model = new Users();

		$user = $user_model->get_pass_by_username($username);
		$is_pass_correct = password_verify($password, $user['password']);

		if ($is_pass_correct) {
			$hash = hash('md5', microtime());
			$user_id = $user['id'];
			$cookie_id = setcookie('id', $user_id, time() + 84000, "/"); 
			$cookie_hash = setcookie('loginhash', $hash, time() + 84000, "/");
			$set_cookie = Users::set_user_cookie($hash, $user_id);
			
			header('Location: ' .URLROOT. '/' .$user['role_name']);	
		} else {
			header('Location: '.URLROOT. '/access?err=Wrong Credentials!');
			
		}
	}

	public static function logout($id, $hash)
	{

	 setcookie ('id', $id, time() - 3600, "/");
	 setcookie ('loginhash', $hash, time() - 3600, "/");
		
	}
	
	
}
