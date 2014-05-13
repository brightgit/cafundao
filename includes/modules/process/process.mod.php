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

	function save(){

		if(!isset($_POST["client_id"])){
			$client_id = self::add_client();
		}
		else{
			$client_id = $_POST["client_id"];
			//$client_id = 15; //remover
		}

		//salvar processo e associar a cliente
		$process["tipo_credito"] = $_POST["process_tipo_credito"];
		$process["ccc_id"] = $_POST["process_ccc_id"];
		$process["data_vencimento"] = $_POST["process_data_vencimento"];
		$process["data_ultimo_movimento"] = $_POST["process_data_ultimo_movimento"];
		$process["periodicidade_pagamento_juros"] = $_POST["process_periodicidade_pagamento_juros"];
		$process["prazo"] = $_POST["process_prazo"];
		$process["montante"] = $_POST["process_montante"];
		$process["montante_extenso"] = $_POST["process_montante_extenso"];
		$process["finalidade"] = $_POST["process_finalidade"];
		$process["responsabilidade_global"] = $_POST["process_responsabilidade_global"];
		$process["conta_deposito_ordem_associado"] = $_POST["process_conta_deposito_ordem_associado"];
		$process["saldo_medio"] = $_POST["process_saldo_medio"];
		$process["informacoes_actividade"] = $_POST["process_informacoes_actividade"];
		$process["informacoes_parecer_balcao"] = $_POST["process_informacoes_parecer_balcao"];
		$process["observacoes"] = $_POST["process_observacoes"];

		//associar o processo a um cliente na tabela processes
		$sql = "INSERT INTO processes SET ";
		$sql .= "`client_id` = '".$client_id."', ";
		$sql .= "`ccc_num` = '".$process["ccc_id"]."', ";
		$sql .= "`criado_por` = '".$_SESSION["user_bo"]."', ";
		$sql .= "`prazo` = '".$process["prazo"]."'";

		$insert_processes = mysql_query($sql); //insere e gera novo id

		//determinar o id do process (id interno, único, uma vez que CCC podem haver vários)
		$sql = "SELECT MAX(id) AS id FROM processes WHERE ccc_num = '".$process["ccc_id"]."'";
		$process_id_result = mysql_fetch_object(mysql_query($sql));
		$process_id = $process_id_result->id;

		//inserir os dados de preenchimento na tabela processes_form
		$sql = "INSERT INTO processes_form SET ";
		$sql .= " `process_id` = '".$process_id."', ";
		$sql .= " `ccc_id` = '".$process["ccc_id"]."', ";
		$sql .= " `tipo_credito` = '".$process["tipo_credito"]."', ";
		$sql .= " `data_vencimento` = '".$process["data_vencimento"]."', ";
		$sql .= " `data_ultimo_movimento` = '".$process["data_ultimo_movimento"]."', ";
		$sql .= " `periodicidade_pagamento_juros` = '".$process["periodicidade_pagamento_juros"]."', ";
		$sql .= " `montante` = '".$process["montante"]."', ";
		$sql .= " `montante_extenso` = '".($process["montante_extenso"])."', ";
		$sql .= " `finalidade` = '".$process["finalidade"]."', ";
		$sql .= " `responsabilidade_global` = '".$process["responsabilidade_global"]."', ";
		$sql .= " `conta_deposito_ordem_associado` = '".$process["conta_deposito_ordem_associado"]."', ";
		$sql .= " `saldo_medio` = '".$process["saldo_medio"]."', ";
		$sql .= " `informacoes_actividade` = '".$process["informacoes_actividade"]."', ";
		$sql .= " `informacoes_parecer_balcao` = '".$process["informacoes_parecer_balcao"]."', ";
		$sql .= " `observacoes` = '".($process["observacoes"])."' ";

		$insert_processes_form = mysql_query($sql);

		redirect("?mod=clientes_list&process_id=".$process_id);

		
	}

	//insere cliente e retorna o id
	function add_client(){

		$client["nome"] = $_POST["process_client_nome"];
		$client["numero_cliente"] = $_POST["process_client_numero_cliente"];
		$client["balcao"] = $_POST["process_client_balcao"];
		$client["telemovel"] = $_POST["process_client_telemovel"];
		$client["nipc"] = $_POST["process_client_nipc"];

		$sql = "INSERT INTO clients SET ";
		$sql .= " `nome` = '".$client["nome"]."', ";
		$sql .= " `numero_cliente` = '".$client["numero_cliente"]."', ";
		$sql .= " `balcao` = '".$client["balcao"]."', ";
		$sql .= " `telemovel` = '".$client["telemovel"]."', ";
		$sql .= " `nipc` = '".$client["nipc"]."'";

		$insert = mysql_query($sql);
		if($insert){
			$sql = "SELECT id FROM clients where numero_cliente = ".$client["numero_cliente"];
			$client_id_result = mysql_fetch_object(mysql_query($sql));
			$client_id = $client_id_result->id;
			return $client_id;
		}

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