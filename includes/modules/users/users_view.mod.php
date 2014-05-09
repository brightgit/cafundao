<?php 
/**
* 
*/
class Users_view
{
	public $view_file = "users/users_view";
	function __construct()
	{
		require_once( "includes/modules/users/users.mod.php" );

		switch ($_GET["act"]) {
			case 'add_user':
				$this->add_user();
				break;
		}

	}

}

 ?>