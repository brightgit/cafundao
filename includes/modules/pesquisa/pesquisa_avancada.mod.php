<?php 
/**
* 
*/
class Pesquisa_avancada
{
	public $view_file = "pesquisa/pesquisa_avancada";
	
	function __construct()
	{
		require_once( "includes/modules/pesquisa/pesquisa.mod.php" );
		switch ($_GET["act"]) {
			case 'search':
				$this->search(  );
				break;
			
			default:
				return;
				break;
		}

	}


	function search(){
		require_once( base_path( "includes/modules/users/users.mod.php" ) );
		$u = new users();

		$this_user = $u->get_user_by_id( $_SESSION["user_bo"] );

		//echo '<pre>';
		//var_dump($_GET);
		//echo '</pre>';

		//Começar a buildar a query
		$query = "select processes.*, clients.nome as nome_cliente, clients.numero_cliente, clients.balcao as balcao_cliente
			from processes
			 left join clients on clients.id = processes.client_id 
			 where 1";

		if ( $this_user["p_analise_risco"] ) {
			$query .= " and processes.analise_risco = 1";
		}



		//$query .= " and is_active = '1";
		//$res  = mysql_query($query);

	    if (!empty($_GET["processo"])) {
	    	$query .= " and processes.ccc_num LIKE '%".$_GET["processo"]."%'";
	    }

	    if (!empty($_GET["id_cliente"])) {
	    	$query .= " and clients.numero_cliente LIKE '%".$_GET["id_cliente"]."%'";
	    }

	    if (!empty($_GET["cliente"])) {
	    	$query .= " and clients.nome LIKE '%".$_GET["cliente"]."%'";
	    }

	    if (!empty($_GET["balcao"])) {
	    	$query .= " and clients.balcao LIKE '%".$_GET["balcao"]."%'";
	    }

	    switch ($_GET["estado"]) {
    		case 'em_processamento':
    			$query .= " and processes.avaliacao = 0 ";
    			break;
    		case 'avaliacao':
    			$query .= " and processes.avaliacao = 1 ";
    			break;
    		case 'aceite':
    			$query .= " and processes.avaliacao = 2 and resultado = 1 ";
    			break;
    		case 'reprovado':
    			$query .= " and processes.avaliacao = 2 and resultado = 0 ";
    			break;
    		
    		default:
    			# code...
    			break;
    	}




	    if (!empty($_GET["date_start"])) {
	    	$query .= " and processes.prazo > '".$_GET["date_start"].':00'."'";
	    }
	    if (!empty($_GET["date_end"])) {
	    	$query .= " and processes.prazo < '".$_GET["date_end"].':00'."'";
	    }


		//ORDER BY
	    if($_GET['sSortDir_0']=='asc'){
	        $dir = 'ASC';
	    }else{
	        $dir = 'DESC';
	    }
	    if($_GET['iSortCol_0']==0){
	        $query .= " ORDER BY `processes`.`ccc_num` ".$dir;
	    }elseif($_GET['iSortCol_0']==1){
	        $query .= " ORDER BY `clients`.`numero_cliente` ".$dir;
	    }elseif($_GET['iSortCol_0']==2){
	        $query .= " ORDER BY `clients`.`nome` ".$dir;
	    }elseif($_GET['iSortCol_0']==3){
	        $query .= " ORDER BY `clients`.`balcao` ".$dir;
	    }elseif($_GET['iSortCol_0']==4){
	        $query .= " ORDER BY `clients`.`estado` ".$dir;
	    }else{
	        $query .= " ORDER BY `processes`.`prazo` ".$dir;
	    }


	    //echo $query;

	    $query_sem_limits = $query;

	    $query .= " LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];

	    $res = mysql_query($query) or die_sql( $query );

	    //echo '<hr />';
	    //echo $query;
	    //echo '<hr />';


		$send = false;	//No caso de não haver não dá alerta de erro.
		$i = 0;
		while ( $row = mysql_fetch_array($res) ) {
			$send[$i][0] = $row["ccc_num"];
			$send[$i][1] = $row["numero_cliente"];
			$send[$i][2] = $row["nome_cliente"];
			switch ($row["avaliacao"]) {
				case 2:
					if ($row["resultado"] == 1) {
						$send[$i][4] = "Aprovado";
					}else{
						$send[$i][4] = "Reprovado";
					}
					break;
				case 1:
					$send[$i][4] = "Em avaliação";
					break;
				
				default:
					$send[$i][4] = "Em espera";
					break;
			}
			$send[$i][3] = $row["balcao_cliente"];
			$send[$i][5] = $row["prazo"];
			//$send[$i][4] = $row[""];
			$i++;
		}


		$ret = new stdClass();
		$ret->aaData = $send;
		$ret->sEcho = $_GET['sEcho']+1;

		//echo $query_sem_limits;

	    $ret->iDisplayLength = mysql_num_rows(($res));
	    $ret->iTotalRecords = mysql_num_rows(mysql_query($query_sem_limits));
	    $ret->iTotalDisplayRecords = mysql_num_rows(mysql_query($query_sem_limits));


	    $this->output = $ret;
	    echo json_encode( $this->output );




		exit();
	}





}
 ?>