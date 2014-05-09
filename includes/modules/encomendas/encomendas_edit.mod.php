<?php 

class Encomendas_edit
{
	public $view_file = "encomendas/encomendas_edit";

	function __construct()
	{
		if (isset($_POST["submit"])) {
			$this->save_encomenda();
		}
	}

	function save_encomenda(){
		foreach ($_POST as $key => $value) {
			if ($key == "submit") {
				continue;
			}
			$a[] = " `".$key."` = '".$value."' ";
		}

		$query = "update orders set ".implode(",", $a)." where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

	}

	function get_produtos_comprados( $encomenda_id ) {
		$query = "select * from products_bought where order_id = '".$encomenda_id."'";
		$res = mysql_query($query) or die_sql( $query );
		while ($row = mysql_fetch_array($res)) {
			$ret[] = $row;
		}
		return $ret;
	}


	function load_encomenda( $id ){
		$query = "select orders.*, users.full_name as user_name
			from orders
			left join paises on orders.shipping_country = paises.id
			left join users on users.id = orders.user_id
			where orders.id = '".$id."'";
		$res = mysql_query($query) or die_sql( $query );

		return mysql_fetch_array($res);

	}

	function get_estados(){
		$query = "select * from order_status order by id asc";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_paises(){
		$query = "select * from paises order by id asc";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	
}


 ?>