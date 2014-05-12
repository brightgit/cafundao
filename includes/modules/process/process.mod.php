<?php

/**
* Process
*/
class Process extends Core_admin
{
	public $id;
	public $client_id;
	public $view_file;
	public $client_data;

	function __construct()
	{
		$act = isset($_GET["act"]) ? $_GET["act"] : "list_all";
		$this->$act(); //chamar o método dependendo da action solicitada
		$this->client_id = (int) $_GET["id"]; //verificar se foi passado um código de cliente

		//se sim, carregar a informação de cliente, caso contrário, no próximo passo, na inserção em db, é necessário criar um novo cliente com os dados enviados em $_POST
		if($this->client_id)
			$this->client_data = $this->load_client_data();
	
	}

	function load_client_data(){
		$sql = "SELECT * FROM clients WHERE id = ".$this->client_id;
		$query = mysql_query($sql);

		if($query)
			return $result = mysql_fetch_object($query);

		return false;
	}

	//view related methods
	function add(){
		if ( !isset($_POST["ccc_num"]) ) {
			$this->view_file = "process/process_add";
			return;
			# code...
		}
		//Adicionar cliente
		if ( 1 ) {
			# code...
		}





	}

	function list_all(){
		$this->view_file = "process/process_list";
	}

}

?>