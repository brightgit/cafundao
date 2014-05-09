<?php 

class Encomendas_list
{
	public $view_file = "encomendas/encomendas_list";
	function __construct()
	{
		$this->ver();
	}

	function ver(){
		include base_path("includes/modules/encomendas/encomendas.mod.php");
	}

}


 ?>