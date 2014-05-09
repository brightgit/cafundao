<?php 

class Teor_acucar_edit
{
	public $view_file = "teor_acucar/teor_acucar_edit";
	public $error_messages = FALSE;

	function __construct()
	{
		include base_path("includes/modules/teor_acucar/teor_acucar.mod.php");
		if ($_POST["submit"]) {
			$suc = $this->save_teor_acucar();
			if ($suc) {
				header("Location: index.php?mod=teor_acucar_list");
			}
		}
	}

	public function load_teor_acucar(  )
	{
		if (isset($_GET["id"])) {	//Edit
			$teor_acucar = Teor_acucar::get_by_id( $_GET["id"] );
		}else{	//Load Defaults
			$teor_acucar["active"] = 1;
			$teor_acucar["url_pt"] = '';
			$teor_acucar["url_en"] = '';
			$teor_acucar["description_seo_pt"] = '';
			$teor_acucar["description_seo_en"] = '';
			$teor_acucar["keywords_seo_pt"] = '';
			$teor_acucar["keywords_seo_en"] = '';
			$teor_acucar["title_seo_pt"] = '';
			$teor_acucar["title_seo_en"] = '';
			$teor_acucar["teor_acucar_pt"] = '';
			$teor_acucar["teor_acucar_en"] = '';
		}



		/* Caso tenha havido um POST com valores */
		foreach ($teor_acucar as $key => $value) {
			if (isset($_POST[$key])) {
				$teor_acucar[ $key ] = $_POST[ $key ];
			}
		}

		return $teor_acucar;

	}


	public function save_teor_acucar(){

		//verificar o url;
		$query = "select * from teor_acucar where url_pt = '".tools::url_title($_POST["url_pt"])."'";
		if (isset($_POST["id"])) { $query .= " AND id != '".$_POST["id"]."'"; }
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			$this->error_messages[] = "url (pt) já está definido.";
			return false;
		}
		//verificar o url;
		$query = "select * from teor_acucar where url_en = '".tools::url_title($_POST["url_en"])."'";
		if (isset($_POST["id"])) { $query .= " AND id != '".$_POST["id"]."'"; }
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			$this->error_messages[] = "url (en) já está definido.";
			return false;
		}

		if ( isset($_POST["id"]) ) {
			$query = "update teor_acucar set ";
		}else{
			$query = "insert into teor_acucar set ";
		}
			$query .= " active = '".$_POST["active"]."', ";
			$query .= " date_updated = NOW(), ";
			$query .= " url_pt = '".tools::url_title($_POST["url_pt"])."', ";
			$query .= " url_en = '".tools::url_title($_POST["url_en"])."', ";
			$query .= " description_seo_pt = '".$_POST["description_seo_pt"]."', ";
			$query .= " description_seo_en = '".$_POST["description_seo_en"]."', ";
			$query .= " keywords_seo_pt = '".$_POST["keywords_seo_pt"]."', ";
			$query .= " keywords_seo_en = '".$_POST["keywords_seo_en"]."', ";
			$query .= " title_seo_pt = '".$_POST["title_seo_pt"]."', ";
			$query .= " title_seo_en = '".$_POST["title_seo_en"]."', ";
			$query .= " teor_acucar_pt = '".$_POST["teor_acucar_pt"]."', ";
			$query .= " teor_acucar_en = '".$_POST["teor_acucar_en"]."'";


		if ( isset($_POST["id"]) ) {
			$query .= " where id = ".$_POST["id"];
		}else{
			$query .= "";
		}
		if (isset($_POST["id"])) {
			$this->success_messaes[] = "Items Alterado.";
		}else{
			$this->success_messaes[] = "Items Adicionado.";
		}

		mysql_query($query) or die_sql( $query );

		return true;
	}
	

}


 ?>