<?php 

class Freepages_edit
{
	public $view_file = "freepages/freepages_edit";
	public $error_messages = FALSE;

	function __construct()
	{
		include base_path("includes/modules/freepages/freepages.mod.php");
		if ($_POST["submit"]) {
			$suc = $this->save_freepage();
			if ($suc) {
				header("Location: index.php?mod=freepages_list");
			}
		}
	}

	public function load_freepage(  )
	{

		//Imagem



		if (isset($_GET["id"])) {	//Edit
			$freepage = Freepages::get_by_id( $_GET["id"] );
		}else{	//Load Defaults
			$freepage["active"] = 1;
			$freepage["url_pt"] = '';
			$freepage["url_en"] = '';
			$freepage["nome_pt"] = '';
			$freepage["nome_en"] = '';
			$freepage["descricao_pt"] = '';
			$freepage["descricao_en"] = '';
			$freepage["description_seo_pt"] = '';
			$freepage["description_seo_en"] = '';
			$freepage["keywords_seo_pt"] = '';
			$freepage["keywords_seo_en"] = '';
			$freepage["title_seo_pt"] = '';
			$freepage["title_seo_en"] = '';
			$freepage["conteudo_pt"] = '';
			$freepage["conteudo_en"] = '';
		}



		/* Caso tenha havido um POST com valores */
		foreach ($freepage as $key => $value) {
			if (isset($_POST[$key])) {
				$freepage[ $key ] = $_POST[ $key ];
			}
		}

		return $freepage;

	}


	public function save_freepage(){


		//verificar o url;
		$query = "select * from freepages where url_pt = '".tools::url_title($_POST["url_pt"])."'";
		if (isset($_POST["id"])) { $query .= " AND id != '".$_POST["id"]."'"; }
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			$this->error_messages[] = "url (pt) já está definido.";
			return false;
		}
		//verificar o url;
		$query = "select * from freepages where url_en = '".tools::url_title($_POST["url_en"])."'";
		if (isset($_POST["id"])) { $query .= " AND id != '".$_POST["id"]."'"; }
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			$this->error_messages[] = "url (en) já está definido.";
			return false;
		}

		if ( isset($_POST["id"]) ) {
			$query = "update freepages set ";
		}else{
			$query = "insert into freepages set ";
		}
			$query .= " active = '".$_POST["active"]."', ";
			$query .= " date_updated = NOW(), ";
			$query .= " url_pt = '".tools::url_title($_POST["url_pt"])."', ";
			$query .= " url_en = '".tools::url_title($_POST["url_en"])."', ";
			$query .= " conteudo_pt = '".$_POST["conteudo_pt"]."', ";
			$query .= " conteudo_en = '".$_POST["conteudo_en"]."', ";
			$query .= " description_seo_pt = '".$_POST["description_seo_pt"]."', ";
			$query .= " description_seo_en = '".$_POST["description_seo_en"]."', ";
			$query .= " keywords_seo_pt = '".$_POST["keywords_seo_pt"]."', ";
			$query .= " keywords_seo_en = '".$_POST["keywords_seo_en"]."', ";
			$query .= " title_seo_pt = '".$_POST["title_seo_pt"]."', ";
			$query .= " title_seo_en = '".$_POST["title_seo_en"]."', ";
			$query .= " nome_pt = '".$_POST["nome_pt"]."', ";
			$query .= " nome_en = '".$_POST["nome_en"]."'";

		if ( isset($_POST["id"]) ) {
			$query .= " where id = ".$_POST["id"];
		}else{
			$query .= "";
		}
		if (isset($_POST["id"])) {
			$this->success_messaes[] = "Freepage Alterada.";
			$id = $_POST["id"];
		}else{
			$this->success_messaes[] = "Freepages Adicionada.";
		}




		mysql_query($query) or die_sql( $query );

		return true;
	}
	

}


 ?>