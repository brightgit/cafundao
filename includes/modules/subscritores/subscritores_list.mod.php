<?php 

class Subscritores_list
{
	public $view_file = "subscritores/subscritores_list";
	function __construct()
	{
		if (isset($_GET["eliminar"])) {
			$query = "delete from newsletter_emails where id = '".$_GET["eliminar"]."'";
			mysql_query($query);
			$this->ok_messages[] = "Email Eliminado com sucesso.";
		}
		if (isset($_POST["email"])) {
			$this->add();
		}
		$this->ver();
	}

	function ver(){
		include base_path("includes/modules/subscritores/subscritores.mod.php");
	}

	function add(){
		$query = "select * from newsletter_emails where email = '".$_POST["email"]."'";
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {

		}else{
			$query = "insert into newsletter_emails set email = '".$_POST["email"]."'";
			mysql_query($query) or die_sql( $query );
		}
	}

}


 ?>