<?php 

class Login
{
	public $view_file = "login/login_page";
	public $load_menu = FALSE;
	public $data;
	
	function __construct()
	{
		if ($_GET["act"] == "recover_email") {
			$this->recover_email();
			return;
		}
		if (isset($_GET["logout"])) {
			unset($_SESSION["user_bo"]);
			header("Location: index.php?mod=home");
		}
		//Process Login From
		if ( isset($_POST["username"]) )
			$this->process_login();
	}

	function process_login(){

		$query = "SELECT * FROM users where email = '".$_POST["username"]."' and password = '".md5( $_POST["password"] )."' and is_active = 1";
		$res = mysql_query($query) or die_sql();

		if ( mysql_num_rows($res) == 0 ) {
			///Erro No Login
			tools::notify_add( "Username / Password inválidos", "error" );
		}else{
			//Login OK
			$user = mysql_fetch_array($res);
			$_SESSION["user_bo"] = $user["id"];
			$query = "insert into access_login VALUES (null, '".$user["id"]."', '".$_SERVER["REMOTE_ADDR"]."', null, null)";
			mysql_query($query) or die_sql( $query );
			$query = "select * from access_login order by id desc limit 1";
			$res = mysql_query($query) or die_sql( $query );
			$row = mysql_fetch_array($res);
			$_SESSION["session_db_id"] = $row["id"];
			tools::notify_add( "Login efectuado com sucesso", "success" );
			redirect(base_url("index.php?mod=home"));
		}
	}	

	function recover_email(  ) {
		$query = "select * from users where email = '".$_GET["email_recover"]."'";
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) == 0 ) { //Email Não encontrado
			echo '<div class="alert alert-dismissable alert-danger fade in"><span class="title"><i class="icon-remove-sign"></i> Email inexistente</span></div>';
		}else{

		    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
		    $randstring = '';
		    for ($i = 0; $i < 8; $i++) {
		        $randstring .= $characters[rand(0, strlen($characters))];
		    }
		    $query = "update users set password = '".md5( $randstring )."' where email = '".$_GET["email_recover"]."'";
		    mysql_query($query) or die_sql( $query );
		    require_once( "includes/classes/email.php" );
		    $message = "<p>A sua nova password de acesso é:<br />".$randstring."</p>";
		    $subject = 'Pedido de nova password';
		    var_dump($message);
		    var_dump($subject);

		    $email = new email();
		    $email->send_email( "no-reply@virtualdataroom.pt", $_GET["email_recover"], $subject, $message );


			echo '<div class="alert alert-dismissable alert-success fade in"><span class="title"><i class="icon-envelope"></i> Nova password enviada para o seu email.</span></div>';
		}
		exit();
	}
}

?>