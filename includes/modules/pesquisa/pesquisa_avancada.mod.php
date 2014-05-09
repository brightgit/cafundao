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
		//echo '<pre>';
		//var_dump($_GET);
		//echo '</pre>';

		//Começar a buildar a query
		$query = "select documents.*, documents_categories.name as folder from documents
			left join documents_categories on documents.category = documents_categories.id
			 where 1";

		//$query .= " and is_active = '1";
		//$res  = mysql_query($query);

	    if (!empty($_GET["nome_ficheiro"])) {
	    	$query .= " and documents.title LIKE '%".$_GET["nome_ficheiro"]."%'";
	    }
	    if (!empty($_GET["date_start"])) {
	    	$query .= " and documents.date_in > '".$_GET["date_start"].':00'."'";
	    }
	    if (!empty($_GET["date_end"])) {
	    	$query .= " and documents.date_in < '".$_GET["date_end"].':00'."'";
	    }


		//ORDER BY
	    if($_GET['sSortDir_0']=='asc'){
	        $dir = 'ASC';
	    }else{
	        $dir = 'DESC';
	    }
	    if($_GET['iSortCol_0']==0){
	        $query .= " ORDER BY `documents`.`id` ".$dir;
	    }elseif($_GET['iSortCol_0']==1){
	        $query .= " ORDER BY `documents`.`title` ".$dir;
	    }elseif($_GET['iSortCol_0']==2){
	        $query .= " ORDER BY `folder` ".$dir;
	    }elseif($_GET['iSortCol_0']==3){
	        $query .= " ORDER BY `documents`.`date_in` ".$dir;
	        //$order = " ORDER BY `v`.`name` ".$dir;
	    }elseif($_GET['iSortCol_0']==4){
	        //$order = " ORDER BY `re`.`apelido` ".$dir;
	    }else{
	        //$order = " ORDER BY `p`.`idPedidos` DESC";
	        $query .= " ORDER BY `documents`.`date_in` ".$dir;
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
			$send[$i][0] = $row["id"];
			$send[$i][1] = $row["title"];
			$send[$i][2] = $row["folder"];
			$send[$i][3] = $row["date_in"];
			$send[$i][4] = '<a href="get_file.php?id='.$row["id"].'" class="btn btn-success btn-xs">Download</a> <a href="index.php?mod=files_view&id='.$row["id"].'" class="btn btn-primary btn-xs">Editar</a> <a href="index.php?mod=files_view&act=delete_file&id='.$row["id"].'" class="btn btn-danger btn-xs">Apagar</a>';
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