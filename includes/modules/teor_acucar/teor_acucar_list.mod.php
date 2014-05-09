<?php 

class Teor_acucar_list
{
	public $view_file = "teor_acucar/teor_acucar_list";

	function __construct()
	{
		include base_path("includes/modules/teor_acucar/teor_acucar.mod.php");
	
		if (isset($_GET["order"])) {
			$this->order();
			exit();
		}


		if (isset($_GET["act"]) && $_GET["act"] == "eliminar") {
			//passar todos os filhos para pais
			$query = "delete from regioes where id = '".$_GET["id"]."'";
			mysql_query($query) or die_sql( $query );
			$this->success_messages[] = "Região Eliminada.";
			header("index.php?mod=regioes_list");

		}



	}
	function order(){
		$this->load_header = FALSE;
		$this->load_menu = FALSE;
		$this->load_footer = FALSE;

		$i = 1;
		foreach ($_GET["default"] as $key => $value) {
			$query = "update teor_acucar set sort_order = '".$i."' where id = '".$key."'";
			mysql_query($query) or die_sql( $query );
			$i++;
		}

		die();
	}


}


 ?>