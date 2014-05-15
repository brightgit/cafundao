<?php 
/**
* 
*/
class Access_control_list
{
	
	public $view_file = "access_control/access_control_list";

	function __construct()
	{
		require_once( "includes/modules/access_control/access_control.mod.php" );
		switch ($_GET["act"]) {
			case 'get_logins':
				$this->get_logins();
				break;
			case 'login_info':
				$this->login_info( $_GET["id"] );
				break;
		}
	}


	function login_info( $login_id ) {

		$this->view_file = "access_control/access_control_info";

		$this->load_header = false;
		$this->load_menu = false;
		$this->load_footer = false;

	}


	function get_logins(){
		//echo '<pre>';
		//var_dump($_GET);
		//echo '</pre>';

		//Começar a buildar a query

		$query = "select access_login.*, users.email, users.name from access_login 
			left join users on users.id = access_login.user_id
			where 1";

		//$query .= " and is_active = '1";
		$res  = mysql_query($query);

	    if (!empty($_GET["sSearch"])) {
	    	$query .= " and (";
	    	$query .= " access_login.ip_address LIKE '%".$_GET["sSearch"]."%'";
	    	$query .= " or users.name LIKE '%".$_GET["sSearch"]."%'";
	    	$query .= " or users.email LIKE '%".$_GET["sSearch"]."%'";
	    	$query .= " )";
	    }

		//ORDER BY
	    if($_GET['sSortDir_0']=='asc'){
	        $dir = 'ASC';
	    }else{
	        $dir = 'DESC';
	    }
	    if($_GET['iSortCol_0']==0){
	        $query .= " ORDER BY `access_login`.`id` ".$dir;
	    }elseif($_GET['iSortCol_0']==1){
	        $query .= " ORDER BY `access_login`.`ip_address` ".$dir;
	    }elseif($_GET['iSortCol_0']==2){
	        $query .= " ORDER BY `users`.`email` ".$dir;
	    }elseif($_GET['iSortCol_0']==3){
	        $query .= " ORDER BY `access_login`.`date_in` ".$dir;
	    }elseif($_GET['iSortCol_0']==4){
	        $query .= " ORDER BY `access_login`.`date_out` ".$dir;
	    }else{
	        $query .= " ORDER BY `access_login`.`id` ".$dir;
	    }


	    $query_sem_limits = $query;

	    $query .= " LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];

	    $res = mysql_query($query);

	    //echo '<hr />';
	    //echo $query;
	    //echo '<hr />';


		$send = false;	//No caso de não haver não dá alerta de erro.
		$i = 0;
		while ( $row = mysql_fetch_array($res) ) {
			$send[$i][0] = $row["id"];
			$send[$i][1] = '<a href="Javascript:void(0);" onclick="return load_login_info('.$row["id"].')">'.$row["ip_address"].'</a>';
			$send[$i][2] = $row["email"];
			$send[$i][3] = date("Y-m-d H:i:s", strtotime( $row["date_in"] ) );
			$send[$i][4] = (isset($row["date_out"]))? date("Y-m-d H:i:s", strtotime( $row["date_out"] ) ):'Logout não detectado';
			//$send[$i][5] = '<a href="index.php?mod=tags_list&act=delete_tag&id='.$row["id"].'" class="btn btn-danger btn-xs">Apagar</a>';
			//$send[$i][4] = $row[""];
			$i++;
		}


		$ret = new stdClass();
		$ret->aaData = $send;
		$ret->sEcho = $_GET['sEcho']+1;
		//echo $query_sem_limits;
	    $ret->iTotalRecords = mysql_num_rows(mysql_query($query_sem_limits));
	    $ret->iTotalDisplayRecords = mysql_num_rows(mysql_query($query_sem_limits));


	    $this->output = $ret;
	    $this->render_json();




		exit();
	}


	function render_json(){
		echo json_encode($this->output);
		exit;
	}




}
 ?>