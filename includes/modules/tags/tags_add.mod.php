<?php 

class Categorias_add
{
	public $view_file = "categorias/categorias_add";
	public $error_messages = FALSE;

	function __construct()
	{
		include base_path("includes/modules/categorias/categoria.mod.php");
		if ($_POST["submit"]) {
			$suc = $this->save_categoria();
			if ($suc) {
				header("Location: index.php?mod=categorias_list");
			}
		}
	}

	public function load_categoria(  )
	{
		if (isset($_GET["id"])) {	//Edit
			$categoria = Categoria::get_by_id( $_GET["id"] );
		}else{	//Load Defaults
			$categoria["active"] = 1;
			$categoria["url_pt"] = '';
			$categoria["url_en"] = '';
			$categoria["description_seo_pt"] = '';
			$categoria["description_seo_en"] = '';
			$categoria["keywords_seo_pt"] = '';
			$categoria["keywords_seo_en"] = '';
			$categoria["title_seo_pt"] = '';
			$categoria["title_seo_en"] = '';
			$categoria["categoria_pt"] = '';
			$categoria["categoria_en"] = '';
			$categoria["parent"] = 0;
		}

		/* Caso tenha havido um POST com valores */
		foreach ($categoria as $key => $value) {
			if (isset($_POST[$key])) {
				$categoria[ $key ] = $_POST[ $key ];
			}
		}

		return $categoria;

	}

	function get_destaques_categoria( $categoria_id ){
		$query = "select * from catalogo where categoria = '".$categoria_id."'";
		echo $query;
		$res = mysql_query($query) or die_sql( $query );
		while ($row = mysql_fetch_array($res)) {
			$ret[] = $row;
		}
		return $ret;
	}


	public function save_categoria(){

		//verificar o url;
		$query = "select * from categorias where url_pt = '".tools::url_title($_POST["url_pt"])."'";
		if (isset($_POST["id"])) { $query .= " AND id != '".$_POST["id"]."'"; }
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			$this->error_messages[] = "url (pt) já está definido.";
			return false;
		}
		//verificar o url;
		$query = "select * from categorias where url_en = '".tools::url_title($_POST["url_en"])."'";
		if (isset($_POST["id"])) { $query .= " AND id != '".$_POST["id"]."'"; }
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) > 0 ) {
			$this->error_messages[] = "url (en) já está definido.";
			return false;
		}

		if ( isset($_POST["id"]) ) {
			$query = "update categorias set ";
		}else{
			$query = "insert into categorias set ";
		}
			$query .= " active = '".$_POST["active"]."', ";
			$query .= " date_updated = NOW(), ";
			$query .= " url_pt = '".tools::url_title($_POST["url_pt"])."', ";
			$query .= " url_en = '".tools::url_title($_POST["url_en"])."', ";
			$query .= " description_seo_pt = '".$_POST["description_seo_en"]."', ";
			$query .= " description_seo_en = '".$_POST["description_seo_en"]."', ";
			$query .= " keywords_seo_pt = '".$_POST["keywords_seo_pt"]."', ";
			$query .= " keywords_seo_en = '".$_POST["keywords_seo_en"]."', ";
			$query .= " title_seo_pt = '".$_POST["title_seo_pt"]."', ";
			$query .= " title_seo_en = '".$_POST["title_seo_en"]."', ";
			$query .= " categoria_pt = '".$_POST["categoria_pt"]."', ";
			$query .= " categoria_en = '".$_POST["categoria_en"]."', ";

			if ($_POST["parent"]) {	//Qualquer número diferente de 0
				$query .= " parent = '".$_POST["parent"]."'";
			}else{
				$query .= " parent = NULL";
			}

		if ( isset($_POST["id"]) ) {
			$query .= " where id = ".$_POST["id"];
		}else{
			$query .= "";
		}

		mysql_query($query) or die_sql( $query );

		return true;
	}
	

}


 ?>