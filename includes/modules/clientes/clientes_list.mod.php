<?php 

class Clientes_list
{
	public $view_file = "clientes/clientes_list";
	function __construct()
	{
		$this->ver();
	}

	function ver(){
		include base_path("includes/modules/clientes/clientes.mod.php");
	}

}


 ?>