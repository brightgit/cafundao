<?php 

class Freepages_list
{
	public $view_file = "freepages/freepages_list";

	function __construct()
	{
		include base_path("includes/modules/freepages/freepages.mod.php");
	
		if (isset($_GET["act"]) && $_GET["act"] == "toggle_active") {
			$this->toggle_active();
		}
		if (isset($_GET["act"]) && $_GET["act"] == "eliminar") {
			//passar todos os filhos para pais
			$query = "delete from freepages where id = '".$_GET["id"]."' and not_deletable=0";
			mysql_query($query) or die_sql( $query );
			$this->success_messages[] = "Freepages Eliminada.";
			header("Location: index.php?mod=freepages_list");

		}



	}


	function toggle_active(){
		$query = "select * from freepages where id = '".$_GET["id"]."'";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array($res);
		if ( $row["active"] == 1 ) {
			$query = "update freepages set active = 0 where id = '".$row["id"]."'";
		}else{
			$query = "update freepages set active = 1 where id = '".$row["id"]."'";
		}

		mysql_query($query) or die_sql( $query );

		header( "Location: ".$_SERVER["HTTP_REFERER"] );
		exit;
	}
	

	

}


 ?>