<?php 

class Files extends Core_admin
{
	public $view_file = "files/files_list";

	function __construct()
	{
		$this->load_settings();
		switch ($_GET["act"]) {
			case 'add_folder':
				$this->add_folder();
				break;
			case 'delete_folder':
				$this->delete_folder();
				break;
			case 'change_folder':
				$this->change_folder();
				break;
			case 'para_avaliar':
				$this->para_avaliar();
				break;
			case 'aceitar':
				$this->aceitar();
				break;
			case 'rejeitar':
				$this->rejeitar();
				break;
		}

	}

	function aceitar (  ) {
		require_once( base_path("includes/modules/users/users.mod.php") );
		$users_c = new Users();
		$this_user = $users_c->get_user_by_id( $_SESSION["user_bo"] );

		$query = "update votos set vote_casted = 1 where category_id = '".$_GET["id"]."' and user_id = '".$this_user["id"]."'";
		mysql_query( $query ) or die_sql( $query );

		$query = "select * from votos where category_id = '".$_GET["id"]."' and vote_casted is null";
		$res = mysql_query( $query ) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {	//Isto não está completo, avançar
			tools::notify_add( "Voto aplicado com sucesso.", "success" );
			redirect( "index.php?mod=files" );
		}

		$this->end_vote();
		
		tools::notify_add( "Voto aplicado com sucesso.", "success" );
		redirect( "index.php?mod=files" );

	}

	function end_vote( $category_id ){
		//Verificar se todos votaram
		$query = "select * from documents_categories where id = '".$category_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array( $res );
		switch ( $row["metodo_voto"] ) {
			case 'normal':
				$votos_sim = 0;
				$votos_nao = 0;
				$query = "select count(id) as num_votos, vote_casted from votos where category_id = '".$category_id."' group by vote_casted";
				$res = mysql_query($query) or die_sql( $query );
				while ( $row = mysql_fetch_array($res) ) {
					if ( $row["vote_casted"] == 1 ) {
						$votos_sim += $row["num_votos"];
					}elseif( $row["vote_casted"] == 0 ) {
						$votos_nao += $row["num_votos"];
					}
				}

				if ( $votos_sim > $votos_nao ) {
					$query = "update documents_categories set resultado = 1 where id = '".$category_id."'";
					mysql_query($query) or die_sql( $query );
				}elseif( $votos_nao > $votos_sim ) {
					$query = "update documents_categories set resultado = 0 where id = '".$category_id."'";
					mysql_query($query) or die_sql( $query );
				}

				break;
			case "basta_um_rejeitar":
				break;
			case "basta_um_aceitar":
				break;
			
			default:
				# code...
				break;
		}

	}

	function para_avaliar(  ) {

		$query = "update documents_categories set avaliacao = 1 where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		$query = "select * from users where level < 3";
		$res = mysql_query($query) or die_sql( $query );

		$num_total_votos = mysql_num_rows($res);


		while ( $row = mysql_fetch_array($res) ) {
			$query = "insert into votos values (NULL, '".$_GET["id"]."', '".$row["id"]."', NULL)";
			mysql_query($query) or die_sql( $query );
		}

		$query = "select * from users where level < 2";
		$res = mysql_query($query) or die_sql( $query );

		$num_total_votos_admin = mysql_num_rows($res);

		$metade_dos_votos = floor( $num_total_votos/2 )+1;
		$query = "update documents_categories set num_min_votos = '".$metade_dos_votos."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		$metade_dos_votos_admin = floor( $num_total_votos_admin/2 )+1;
		$query = "update documents_categories set num_min_votos_qualidade = '".$metade_dos_votos_admin."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		tools::notify_add( "Cliente colocado para avaliação.", "success" );
		redirect( "index.php?mod=files" );

	}

	function list_children($children){


		echo "<ul>";

		foreach ($children as $child) {

			$folder_type = ($child["values"]->type == "0") ? "files" : "images";
			$class_has_children = empty($child["children"]) ? "no-children" : "has-children";

			echo '<li class="'.$class_has_children.'" data-item-type="folder" data-act="folder_open" data-folder-type="'.$folder_type.'" data-id="'.$child["values"]->id.'">
					<a href="#" title="'.$child["values"]->name.'">' . $child["values"]->name . '</a>';

			if(!empty($child["children"]))
				self::list_children($child["children"]);

			echo '</li>';

		}

		echo "</ul>";
	}

	function change_folder(){

		//Verificar se o cliente não existe
		$query = "select * from documents_categories where client_id = '". $_POST["client_id"] ."' and id != '". $_GET["id"] ."' and is_client = 1";
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows( $res ) > 0 || trim($_POST["client_id"]) == "" ) {
			tools::notify_add("cliente já existente.", "error");
			redirect( "index.php?mod=files" );

		}

		//Ir buscar a pasta
		$query  = "select * from documents_categories where id = '".$_GET["id"]."'";
		$res = mysql_query($query) or die_sql( $query );
		$folder_to_change = mysql_fetch_array($res);
		if ( $folder_to_change["client_id"] ) {
			//Aqui vamos ter que alterar o email defeito
			$query = "delete from emails_by_folder where is_default = 1 and folder_id = '".$folder_to_change["id"]."' ";
			mysql_query($query) or die_sql( $query );

			$query = "insert into emails_by_folder set email = '".tools::foldify( $_POST["client_id"] ).".creditoagricola@virtualdataroom.pt', folder_id = '".$folder_to_change["id"]."', is_default = 1";
			mysql_query($query) or die_sql( $query );
		}

		#Alterar nome
		$query = "update documents_categories set name = '".$_POST["folder_new_name"]."', client_id = '".$_POST["client_id"]."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql($query);

		#Alterar emails
		$query = "delete from emails_by_folder where folder_id = '".$_GET["id"]."' and is_default = 0";
		mysql_query($query) or die_sql( $query );

		foreach ($_POST["emails"] as $key => $value) {
			$value = trim($value);
			if (empty($value)) {
				continue;
			}
			$query = "insert into emails_by_folder set email = '".$value.'@'.$this->settings->domain[0]."', folder_id = '".$_GET["id"]."'";
			mysql_query($query) or die_sql( $query );
		}

		#Alterar Tags
		$query = "delete from tags_link_documents_categories where documents_categories_id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		foreach ($_POST["tags"] as $key => $value) {
			$value = trim($value);
			if (empty($value)) {
				continue;
			}
			$query = "insert into tags_link_documents_categories set tags_id = '".$value."', documents_categories_id = '".$_GET["id"]."'";
			mysql_query($query) or die_sql( $query );
		}


		tools::notify_add("Pasta alterada com sucesso.", "success");
		redirect( "index.php?mod=files" );


	}
	
	function get_list(){

		$sql = "SELECT * FROM documents d LEFT JOIN documents_categories dc ON d.category = dc.id";	
		$query = mysql_query($sql) or die_sql( $sql );

		while ($row = mysql_fetch_object($query))
			$output[] = $row;
		
		$this->num_total_products = count($output);

		return $output;
	}

	//fetch main folders
	function get_folders($parent = 0){

		//2 listagens possiveis:

		//1 - main folders ou $parent = 0
		if(true){
			$sql = "SELECT *, (SELECT count(id) FROM documents_categories WHERE parent = dc.id) AS children FROM documents_categories dc WHERE parent = ".$parent;
			$query = mysql_query($sql) or die_sql($sql);

			if($query){
				while($category = mysql_fetch_object($query)){

					$folders[$category->id]["values"] = $category;

					//2 - recursive $parent != 0
					if($category->children > 0)
						$folders[$category->id]["children"] = self::get_folders($category->id);
					else
						$folders[$category->id]["children"] = false;
				}
			}
		}

		return $folders;

	}

	function get_all_folders(){
		$sql = "SELECT * FROM documents_categories";
		$query = mysql_query($sql);

		if($query){
			while ($row = mysql_fetch_object($query)) {
				$folders[] = $row;
			}
			return $folders;
		}
		return false;		
	}

	//fetch main folders
	function get_folders_old(){
		$sql = "SELECT id, name FROM documents_categories WHERE parent = 0";
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

	function get_category( $category_id ) {
		$query = "select * from documents_categories where id = '".$category_id."'";
		$res = mysql_query($query) or die_sql( $query );

		return mysql_fetch_array($res);

	}

	function get_everything_from_folder( $folder_id ) {
		$query = "select * from documents_categories where id = '".$folder_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$folder = mysql_fetch_array($res);

		//Get Tags
		$query = "select tags.* from tags_link_documents_categories left join tags on tags_link_documents_categories.tags_id = tags.id where tags_link_documents_categories.documents_categories_id = '".$folder["id"]."'";
		$res = mysql_query($query);

		while( $row = mysql_fetch_array($res) ) {
			$folder["tags"][] = $row["id"];
		}

		//Get Email
		$query = "select * from emails_by_folder where folder_id = '".$folder["id"]."' order by is_default desc, id asc";
		//echo $query;
		$res = mysql_query($query);
		while ( $row = mysql_fetch_array($res) ) {
			$folder["emails"][] = $row;
		}

		return $folder;


	}


	function get_file( $file_id ){
		$query ="select * from documents where id = '".$file_id."'";
		$res = mysql_query($query);

		return mysql_fetch_array($res);

	}
	function get_file_user( $file_id ){
		$query ="select users.* from users left join documents on documents.criado_por = users.id where documents.id = '".$file_id."'";
		$res = mysql_query($query) or die_sql($query);

		return mysql_fetch_array($res);

	}

	public function get_file_path( $file_category )
	{
		$row = $this->get_category( $file_category );
		$count = 0;
		while ( $row["parent"] != 0 ) {
			$ret[$count] = $row = $this->get_category( $row["parent"] );
			$count ++;
		}
		if (!isset($ret)) {
			$ret[$count] = $row;
		}



		return array_reverse($ret);

	}

	function add_access() {
		$query = "select * from access_documents where session_id = '".$_SESSION["session_db_id"]."' and document_id = '".$_GET["id"]."'";
		$res = mysql_query($query);
		if (mysql_num_rows($res) == 0) {
			$query = "insert into access_documents values ('".$_SESSION["session_db_id"]."', '".$_GET["id"]."', null)";
			mysql_query($query) or die_sql( $query );
		}
	}

	function get_file_comments( $file_id ) {
		$query = "select comments.*, users.name as name from comments left join users on comments.user_id = users.id where file_id = '".$file_id."'";
		$res = mysql_query($query) or die_sql();
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_file_tags( $file_id ) {
		$query = "select tags.*, tags_link_documents.id as tld_id from tags left join tags_link_documents on tags_link_documents.tags_id = tags.id where tags_link_documents.documents_id = '".$file_id."'";
		$res = mysql_query($query) or die_sql();
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}
	function get_file_tags_ids( $file_id ) {
		$query = "select tags.*, tags_link_documents.id as tld_id from tags left join tags_link_documents on tags_link_documents.tags_id = tags.id where tags_link_documents.documents_id = '".$file_id."'";
		$res = mysql_query($query) or die_sql();
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row["id"];
		}
		return $ret;
	}
	function get_file_access( $file_id ) {
		$query = "select access_documents.*, access_login.ip_address, users.name
			from access_documents 
			left join access_login on access_login.id = access_documents.session_id
			left join users on users.id = access_login.user_id
			where document_id = '".$file_id."'
			order by access_documents.date_in desc";

		$res = mysql_query($query) or die_sql( $query );

		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}

		return $ret;

	}
	function get_all_tags_by_category( ) {
		$query = "select * from tags_categories order by sort_order asc";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			unset($aux);
			$aux["category"] = $row;

			$query = "select tags.* from tags_link_tags_categories left join tags on tags.id = tags_link_tags_categories.tag_id where tags_link_tags_categories.category_id = '".$row["id"]."' order by tags.sort_order";
			$res2 = mysql_query($query) or die_sql( $query );
			while ( $row2 = mysql_fetch_array($res2) ) {
				$aux["tags"][] = $row2;
			}
			$ret[] = $aux;
		}

		return $ret;
	}
	function get_tags_in_folder( $folder_id ) {
		$query = "select tags.* from tags_link_documents_categories left join tags on tags_link_documents_categories.tags_id = tags.id where documents_categories_id = '".$folder_id."'";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_tags_not_in_folder( $folder_id ) {
		$query = "select * from tags where id not in (select tags_id from tags_link_documents_categories where documents_categories_id = '".$folder_id."')";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	public function get_all_tags(  )
	{
		$query = "select * from tags where active = 1 order by sort_order asc";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}


	function add_folder(  ){

		//Não pode haver clientes com o mesmo nome.
		$query = "select * from documents_categories where client_id = '".$_POST["client_id"]."' and parent = 1";
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {	//Existe pasta com este nome
			tools::notify_add("Cliente já existente.", "error");
			redirect( "index.php?mod=files" );
		}

		//GET last id
		$query = "select * from documents_categories where parent = '".$_POST["parent_folder"]."' order by sort_order desc";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array($res);

		$query = "INSERT INTO documents_categories set ";
		$query .= " `name` = '".$_POST["nova_pasta_nome"]."', ";
		$query .= " `sort_order` = '".($row["sort_order"]+1)."', ";
		$query .= " `client_id` = '".$_POST["client_id"]."', ";
		$query .= " `is_active` = '1', ";
		$query .= " `parent` = '".$_POST["parent_folder"]."', ";
		//$query .= " `page` = '".$_POST["parent_folder"]."', ";	//Confimar mas acho que é para retirar, isto uma especie de cliente
		//Data -> não precisa pois mete current timestamp
		$query .= " `type` = '".$_POST["nova_pasta_tipo"]."', ";

		if ( $_POST["parent_folder"] == 1 ) {
			$query .= " is_client = 1, ";
		}else{
			$query .= " is_client = 0, ";
		}

		$query .= " `criado_por` = '".$_SESSION["user_bo"]."'";


		mysql_query($query) or die_sql( $query );

		if ( $_POST["parent_folder"] == 1 ) {
			//Inserir mail de defeito
			$query = "select * from documents_categories order by id desc limit 1";
			$res = mysql_query($query) or die_sql( $query );
			$row = mysql_fetch_array($res);
			$query = "insert into emails_by_folder set ";
			$query .= " email  = '".tools::foldify ( $row["client_id"] ) .".creditoagricola@virtualdataroom.pt', ";
			$query .= " is_default  = '1', ";
			$query .= " folder_id  = '".$row["id"]."'";
			mysql_query($query) or die_sql( $query );
		}

		tools::notify_add("Pasta criada com sucesso.", "success");

		redirect( "index.php?mod=files" );

	}


	//Notas para esta funcao
		//Terá que eliminar as associadoes: permissões / tags / etc
		//Terá que eliminar os ficheiros.
		//Confirmar melhor que eliminar todas as pastas e ficheiros
	function delete_folder(  ){

		function get_children( $start_id, $delete_ids ) {
			$query = "select * from documents_categories where parent = '".$start_id."'";
			$res = mysql_query($query) or die_sql( $query );
			while ( $row = mysql_fetch_array( $res ) ) {
				$delete_ids .= ','.$row["id"];
				$delete_ids = get_children( $row["id"], $delete_ids );
			}
			return $delete_ids;
		}

		//Ir buscar o path com todas as pastas
		$file_path = $this->get_file_path( $_POST["folder_to_delete_id"] );

		$delete_ids = $_POST["folder_to_delete_id"];

		$query = "select * from documents_categories where parent = '".$_POST["folder_to_delete_id"]."'";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array( $res ) ) {
			$delete_ids .= ','.$row["id"];
			$delete_ids = get_children( $row["id"], $delete_ids );
		}

		//Eliminar ficheiros
		$query  = "delete from documents where category in( ".$delete_ids." )";
		mysql_query($query) or die_sql( $query );

		$query = "delete from documents_categories where id in(".$delete_ids.")";
		mysql_query($query) or die_sql( $query );

		tools::notify_add("Pasta eliminada com sucesso.", "success");

		redirect( "index.php?mod=files" );

	}

}

?>