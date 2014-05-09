<?php 

class Tags_list
{
	public $view_file = "tags/tags_list";
	public $success_messages = "";

	function __construct()
	{

		include base_path("includes/modules/tags/tags.mod.php");


		switch ($_GET["act"]) {
			case 'tag_info':
				$this->tag_info();
				break;
			case 'add_tag':
				$this->add_tag();
				break;
			case 'add_tag_category':
				$this->add_tag_category();
				break;
			case 'remover_categoria':
				$this->remover_categoria();
				break;
			case 'delete_tag':
				$this->delete_tag();
				break;
		}


		if (isset($_GET["order"])) {
			$this->order();
			exit();
		}


	}

	function tag_info() {
		$this->load_header = FALSE;
		$this->load_menu = FALSE;
		$this->load_footer = FALSE;
		$this->view_file = "tags/tags_info";

	}

	public function remover_categoria(  ){
		$query = "delete from tags_link_tags_categories where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Categoria removida com sucesso.", "success");
		redirect( "index.php?mod=tags_list" );
	}

	public function delete_tag(  ){
		$query = "delete from tags where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );
		$query = "delete from tags_link_documents where tags_id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );
		$query = "delete from tags_link_documents_categories where tags_id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Tag removida com sucesso.", "success");
		redirect( "index.php?mod=tags_list" );
	}

	public function add_tag_category(  ){

		$query = "insert into tags_link_tags_categories values(NULL, '".$_POST["tag_id"]."', '".$_POST["category_id"]."')";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Categoria associada com sucesso.", "success");
		redirect( "index.php?mod=tags_list" );
	}

	function add_tag() {
		$this->load_header = FALSE;
		$this->load_menu = FALSE;
		$this->load_footer = FALSE;


		if ( $_POST["tag_id"] == 0 ) {
			$query = "insert into tags set ";
		}else{
			$query = "update tags set ";
		}
		$query .= " `tag` = '".$_POST["tag"]."', ";
		$query .= " `active` = '".$_POST["active"]."', ";
		$query .= " `criado_por` = '".$_SESSION["user_bo"]."' ";
		if ( $_POST["tag_id"] != 0 ) {
			$query .= " where id = '".$_POST["tag_id"]."'";
			tools::notify_add("Tag alterada com sucesso.", "success");
		}else{
			tools::notify_add("Tag inserida com sucesso.", "success");
		}

		mysql_query($query) or die_sql( $query );

		redirect( "index.php?mod=tags_list" );
	}


	function order(){
		$this->load_header = FALSE;
		$this->load_menu = FALSE;
		$this->load_footer = FALSE;

		$i = 1;
		foreach ($_GET["default"] as $key => $value) {
			$query = "update tags set sort_order = '".$i."' where id = '".$key."'";
			mysql_query($query) or die_sql( $query );
			$i++;
		}

		die();
	}


	

}


 ?>