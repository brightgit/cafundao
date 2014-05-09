<?php 

/**
* Ajax
*/
class Ajax extends Core_admin
{
	public $view_file = "ajax/ajax";
	public $load_header = false;
	public $load_footer = false;
	public $load_menu = false;
	public $output;

	function __construct()
	{
		//carregar settings - necessário para apresentar paths
		$this->load_settings();

		$act = $_GET["act"];
		if (method_exists($this, $act)) {
			$this->$act();
		}
		else
			return "Method ".$act." not defined in ajax.php"; //relembrar para o caso de um "act" não estar definido
	}

	function folder_open(){

		//dependencies
		require_once(base_path("includes/modules/files/files.mod.php"));

		$id = $_GET["id"]; //folder id
		$output = $_GET["output"]; //render in HTML or JSON
		$folder_type = $_GET["folder_type"];

	
		$files = new Files;
		$this->output = $this->data = $files->folder_open($id); //enviar para o output para ser rendered em JSON, ou trabalhado / validado caso necessário
		
		//show
		switch ($output) {
			case 'json':
				$this->render_json();
				break;

			case 'html':
				$this->render_html($folder_type);
				break;
		}

	}

	function render_json(){
		echo json_encode($this->output);
		exit;
	}

	function render_html($folder_type){
		
		switch ($folder_type) {

			//carregar a view de listagem de ficheiros
			case 'files':
				//$view = Core_admin::load_view(base_path("includes/views/files/list_documents.php"), $this->data);
				$this->data->id = $_GET["id"];
				$this->output = Core_admin::load_view(base_path("includes/views/files/list_documents.php"), $this->data);
				break;
			
			//carregar a view de listagem de imagens
			case 'images':
				$this->output = Core_admin::load_view(base_path("includes/views/files/list_images.php"), $this->data);
				break;
		}
		echo $this->output;
		exit();
	}


	function ajax_image_upload(){

		$folder_id = $_GET["folder_id"];

		//mysql_query($query) or die_sql( $query );

		require_once("includes/modules/upload/upload.mod.php");

		$i = 0;
		while (isset($_FILES["file"]["name"][$i])) {
			$upload_lib = new Upload_lib;
			$upload_lib->initialize();
			$upload_lib->set_extension( $_FILES["file"]["name"][$i] );
			$upload_lib->generate_file_name( $_FILES["file"]["name"][$i] );
			$upload = $upload_lib->upload( $_FILES["file"]["tmp_name"][$i] );
			$sql = "INSERT INTO documents (title, category, file, criado_por) VALUES ('".$upload_lib->file->display_name."', ".$folder_id.", '".$upload_lib->file->display_name."', '".$_SESSION["user_bo"]."')";
			mysql_query($sql);
			$i++;
		}

		exit;
	}



	function get_files(){
		//echo '<pre>';
		//var_dump($_GET);
		//echo '</pre>';

		//Começar a buildar a query
		$query = "select * from documents where 1";

		if (isset($_GET["folder_id"])) {
			$query .= " and category = '".$_GET["folder_id"]."'";
		}
		//$query .= " and is_active = '1";
		$res  = mysql_query($query);

	    if (!empty($_GET["sSearch"])) {
	    	$query .= " and documents.title LIKE '%".$_GET["sSearch"]."%'";
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
	        $query .= " ORDER BY `documents`.`date_in` ".$dir;
	    }elseif($_GET['iSortCol_0']==3){
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

	    $res = mysql_query($query);

	    //echo '<hr />';
	    //echo $query;
	    //echo '<hr />';


		$send = false;	//No caso de não haver não dá alerta de erro.
		$i = 0;
		while ( $row = mysql_fetch_array($res) ) {
			$send[$i][0] = $row["id"];
			$send[$i][1] = $row["title"];
			$send[$i][2] = $row["date_in"];
			$send[$i][3] = '<a href="get_file.php?id='.$row["id"].'" class="btn btn-success btn-xs">Download</a> <a href="index.php?mod=files_view&id='.$row["id"].'" class="btn btn-primary btn-xs">Editar</a> <a href="index.php?mod=files_view&act=delete_file&id='.$row["id"].'" class="btn btn-danger btn-xs">Apagar</a>';
			//$send[$i][4] = $row[""];
			$i++;
		}


		$ret = new stdClass();
		$ret->aaData = $send;
		$ret->sEcho = $_GET['sEcho']+1;
		//echo $query_sem_limits;
	    $ret->iTotalRecords = mysql_num_rows(mysql_query($query_sem_limits));
	    $ret->iTotalDisplayRecords = mysql_num_rows($res);


	    $this->output = $ret;
	    $this->render_json();




		exit();
	}

	function get_tags(){
		//echo '<pre>';
		//var_dump($_GET);
		//echo '</pre>';

		//Começar a buildar a query
		$query = "select tags.*, GROUP_CONCAT( documents_categories.name SEPARATOR ',' ) as pastas, users.name as user_name from tags 
			left join users on users.id = tags.criado_por 
			left join tags_link_documents_categories on tags_link_documents_categories.tags_id = tags.id 
			left join documents_categories on tags_link_documents_categories.documents_categories_id = documents_categories.id 
			where 1";

		//$query .= " and is_active = '1";
		$res  = mysql_query($query);

	    if (!empty($_GET["sSearch"])) {
	    	$query .= " and tags.tags LIKE '%".$_GET["sSearch"]."%'";
	    }

	    $query .= " group by tags.id";

		//ORDER BY
	    if($_GET['sSortDir_0']=='asc'){
	        $dir = 'ASC';
	    }else{
	        $dir = 'DESC';
	    }
	    if($_GET['iSortCol_0']==0){
	        $query .= " ORDER BY `tags`.`id` ".$dir;
	    }elseif($_GET['iSortCol_0']==1){
	        $query .= " ORDER BY `tags`.`tag` ".$dir;
	    }elseif($_GET['iSortCol_0']==2){
	        $query .= " ORDER BY `documents_categories`.`id` ".$dir;
	    }elseif($_GET['iSortCol_0']==3){
	        //$order = " ORDER BY `v`.`name` ".$dir;
	    }elseif($_GET['iSortCol_0']==4){
	        //$order = " ORDER BY `re`.`apelido` ".$dir;
	    }else{
	        //$order = " ORDER BY `p`.`idPedidos` DESC";
	        $query .= " ORDER BY `tags`.`id` ".$dir;
	    }



	    //echo $query;

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
			$send[$i][1] = '<a href="Javascript:void(0);" onclick="return load_edit_tag('.$row["id"].')">'.$row["tag"].'</a>';
			$send[$i][2] = $row["pastas"];
			$send[$i][3] = date("Y-m-d H:i:s", strtotime( $row["data_criacao"] ) );
			$send[$i][4] = $row["user_name"];
			$send[$i][5] = '<a href="index.php?mod=tags_list&act=delete_tag&id='.$row["id"].'" class="btn btn-danger btn-xs">Apagar</a>';
			//$send[$i][4] = $row[""];
			$i++;
		}


		$ret = new stdClass();
		$ret->aaData = $send;
		$ret->sEcho = $_GET['sEcho']+1;
		//echo $query_sem_limits;
	    $ret->iTotalRecords = mysql_num_rows(mysql_query($query_sem_limits));
	    $ret->iTotalDisplayRecords = mysql_num_rows($res);


	    $this->output = $ret;
	    $this->render_json();




		exit();
	}



}

?>