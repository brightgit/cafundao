<?php 

class Files_view
{
	public $view_file = "files/files_view";

	function __construct()
	{
		require_once("includes/modules/files/files.mod.php");
		switch ($_GET["act"]) {
			case 'add_coment':
				$this->add_coment();
				break;
			case 'remove_comment':
				$this->remove_comment();
				break;
			case 'add_tags':
				$this->add_tags();
				break;
			case 'delete_file':
				$this->delete_file( $_GET["id"] );
				break;
		}
	}
	
	function delete_file( $file_id ) {
		$query = "delete from documents where id = '".$file_id."'";
		mysql_query($query) or die_sql($query);
		$query = "delete from access_documents where document_id = '".$file_id."'";
		mysql_query($query) or die_sql( $query );
		$query = "delete from tags_link_documents where documents_id = '".$file_id."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Documento Eliminado.", "success");
		redirect( "index.php?mod=files" );

	}

	function add_tags() {
		$query = "delete from tags_link_documents where documents_id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		foreach ($_POST["tags_in_folder"] as $key => $value) {
			$query = "insert into tags_link_documents values(null, '".$value."', '".$_GET["id"]."')";
			mysql_query($query) or die_sql( $query );
		}

		foreach ($_POST["other_tags"] as $key => $value) {
			$query = "insert into tags_link_documents values(null, '".$value."', '".$_GET["id"]."')";
			mysql_query($query) or die_sql( $query );
		}

		tools::notify_add("Tags associadas.", "success");
		redirect( "index.php?mod=files_view&id=".$_GET["id"] );

	}
	
	function remove_comment() {
		$query = "delete from comments where id = '".$_GET["comment_id"]."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Comentário eliminado.", "success");
		redirect( "index.php?mod=files_view&id=".$_GET["id"] );

	}
	
	function add_coment() {
		$query = "insert into comments set ";
		$query .= " `file_id` = '".$_POST["file_id"]."', ";
		$query .= " `user_id` = '".$_SESSION["user_bo"]."', ";
		$query .= " `title` = '".$_POST["title"]."', ";
		$query .= " `comment` = '".$_POST["comentario"]."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Comentário inserido com sucesso.", "success");
		redirect( "index.php?mod=files_view&id=".$_POST["file_id"] );
	}

	function get_list(){

		$sql = "SELECT * FROM documents d LEFT JOIN documents_categories dc ON d.category = dc.id";	
		$query = mysql_query($sql) or die_sql( $sql );

		while ($row = mysql_fetch_object($query))
			$output[] = $row;
		
		$this->num_total_products = count($output);

		return $output;
	}

	//fetch infinite folders
	function get_folders(){
		$sql = "SELECT * FROM documents_categories WHERE parent = 0";
		$query = mysql_query($sql) or die_sql($sql);

		if($query){
			while($category = mysql_fetch_object($query))
				$main_folders[] = $category;

			return $main_folders;
		}

		return false;
	}

	function folder_open($id){
		
		//primeiro as pastas
		$sql = "SELECT * FROM documents_categories WHERE parent = " . $id . " ORDER BY `name` ASC";
		$query = mysql_query($sql);

		if($query){
			while($row = mysql_fetch_object($query))
				$output["folders"][] = $row;
		}

		//depois os ficheiros
		$sql = "SELECT * FROM documents WHERE category = " . $id . " ORDER BY `title` ASC";
		$query = mysql_query($sql);

		if($query){
			while($row = mysql_fetch_object($query))
				$output["files"][] = $row;
		}

		return !empty($output) ? $output : false;
	}

	function get_files_from_folder($folder_id){
		$sql = "SELECT * FROM documents WHERE category = ".$folder_id;
		$query = mysql_query($sql) or die_sql($sql);

		if($query){
			while ($row = mysql_fetch_object($query))
				$output[] = $row;
			
			return $output;
		}
			
	}
}

?>