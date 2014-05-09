<?php 
/**
* 
*/
class Users_list
{
	public $view_file = "users/users_list";
	function __construct()
	{
		require_once( "includes/modules/users/users.mod.php" );

		switch ($_GET["act"]) {
			case 'add_user':
				$this->add_user();
				break;
			case 'edit_user':
				$this->edit_user( $_GET["id"] );
				break;
			case 'delete_user':
				$this->delete_user( $_GET["id"] );
				break;
			case 'toggle_active':
				$this->toggle_active( $_GET["id"] );
				break;
		}

	}

	public function toggle_active( $user_id ) {
		$query = "select * from users where id = '".$_GET["id"]."'";
		$res = mysql_query($query) or die_sql( $query );
		$user = mysql_fetch_array($res);
		if ( $user["is_active"] ) {
			$query = "update users set is_active = 0 where id = '".$_GET["id"]."'";
			tools::notify_add( "Utilizador inactivado.", "success" );
		}else{
			$query = "update users set is_active = 1 where id = '".$_GET["id"]."'";
			tools::notify_add( "Utilizador reactivado.", "success" );			
		}

		mysql_query($query) or die_sql( $query );
		redirect("index.php?mod=users_list");

	}

	public function delete_user( $id )
	{
		$query = "delete from users where id = '".$id."'";
		mysql_query($query) or die_sql();
		tools::notify_add( "Utilizador removido.", "success" );
		redirect("index.php?mod=users_list");

	}

	function add_user(){

		//Fazer o upload da imagem
		if (empty($_FILES["assinatura"]["name"])) {
			$assinatura = NULL;
		}else{
			$assinatura = tools::upload_image_from_post( "assinatura", tools::url_title( $_POST["name"] ), "assinaturas" );
			if ($assinatura) {
			}else{
				$this->error_messages[] = "Erro no upload da imagem.";
				return false;
			}
		}


		$valid = TRUE;
		//Verificar se o email não existe
		$query = "select * from users where email = '".$_POST["email"]."'";
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			tools::notify_add( "Utilizador já existente", "error" );
			$valid = false;
		}
		//Verificar se as password coincidem
		if ( strlen($_POST["password"]) < 8 || ($_POST["password"] != $_POST["password2"]) ) {
			tools::notify_add( "As passwords têm que coincidir e ter mais que 8 caracteres.", "error" );
			$valid = false;
		}

		$query ="insert into users set ";
		$query .= " name = '".$_POST["name"]."', ";
		$query .= " email = '".$_POST["email"]."', ";
		$query .= " password = '".md5($_POST["password"])."', ";
		$query .= " is_active = '".$_POST["is_active"]."', ";
		$query .= " receive_email = '".$_POST["receive_email"]."', ";
		$query .= " assinatura = '".$assinatura."', ";
		$query .= " level = '".$_POST["level"]."'";

		if ( $valid ) {
			mysql_query($query) or die_sql( $query );
			tools::notify_add( "Utilizador adicionado.", "success" );
		}

		redirect("index.php?mod=users_list");

	}
	function edit_user(){

		//Fazer o upload da imagem
		if (empty($_FILES["assinatura"]["name"])) {
			$assinatura = NULL;
		}else{
			$assinatura = tools::upload_image_from_post( "assinatura", tools::url_title( $_POST["name"] ), "assinaturas" );
			if ($assinatura) {
			}else{
				$this->error_messages[] = "Erro no upload da imagem.";
				return false;
			}
		}


		//Ir buscar o utilizador a ser editado
		$query ="select * from users where id = '".$_POST["user_editing"]."'";
		$res = mysql_query($query) or die_sql( $query );
		$user_editing = mysql_fetch_array($res);

		$valid = TRUE;
		//Verificar se o email não existe
		
		if ($_POST["email"] != $user_editing["email"]) {
			$query = "select * from users where email = '".$_POST["email"]."'";
			$res = mysql_query($query) or die_sql( $query );
			if ( mysql_num_rows($res) > 0 ) {
				tools::notify_add( "Email já atribuido a outro utilizador.", "error" );
				$valid = false;
			}
		}
		
		//Verificar se as password coincidem
		//if ( strlen($_POST["password"]) < 8 || ($_POST["password"] != $_POST["password2"]) ) {
		//	tools::notify_add( "As passwords têm que coincidir e ter mais que 8 caracteres.", "error" );
		//	$valid = false;
		//}
		$user_id = $_POST["user_editing"];


		$query ="update users set ";
		$query .= " name = '".$_POST["name"]."', ";
		$query .= " email = '".$_POST["email"]."', ";
		//$query .= " password = '".md5($_POST["password"])."', ";
		$query .= " is_active = '".$_POST["is_active"]."', ";
		$query .= " receive_email = '".$_POST["receive_email"]."', ";
		$query .= " level = '".$_POST["level"]."',";
		$query .= " can_create_folders = '".$_POST["can_create_folders"]."',";
		$query .= " can_create_tags = '".$_POST["can_create_tags"]."',";
		if ( $assinatura ) {
			$query .= " assinatura = '".$assinatura."',";
		}
		$query .= " can_delete = '".$_POST["can_delete"]."'";
		$query .= " where id = '".$_POST["user_editing"]."'";


		if ( $valid ) {
			//permissão de upload e permissão de visualização
			//apagar tudo
			$sql = "DELETE FROM user_permissions WHERE user_id = ".$user_id;
			$delete = mysql_query($sql) or die_sql($sql);

			$user_view_permissions = $_POST["user_view_permissions"];
			$user_upload_permissions = $_POST["user_upload_permissions"];


			foreach ($user_view_permissions as $key => $value) {
				$sql = "REPLACE INTO user_permissions (user_id, folder_id, can_view) VALUES (".$user_id.", ".$value.", 1)";
				$insert_view_permissions = mysql_query($sql) or die_sql ($sql);
			}

			foreach ($user_upload_permissions as $key => $value) {
				$sql = "REPLACE INTO user_permissions (user_id, folder_id, can_upload) VALUES (".$user_id.", ".$value.", 1)";
				$insert_upload_permissions = mysql_query($sql) or die_sql ($sql);
			}

			mysql_query($query) or die_sql( $query );
			tools::notify_add( "Utilizador actualizado.", "success" );
		}

		redirect("index.php?mod=users_view&id=".$_POST["user_editing"]);

	}

}

 ?>