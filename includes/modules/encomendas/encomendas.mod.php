<?php 

class Encomendas
{
	
	public $table_display = 20;
	public $table_page = 0;
	public $table_order = "orders.id";
	public $table_order_dir = "DESC";
	public $num_total_encomendas = FALSE;
	public $status_code = "encomendas_nao_processadas";

	function __construct()
	{
		$this->load_attrs();
	}
	
	function load_attrs(){
		if( isset( $_GET["page"]) ) { $this->table_page = $_GET["page"] ; }
		if( isset( $_GET["num"]) ) { $this->table_display = $_GET["num"] ; }
		if( isset( $_GET["order"]) ) { $this->table_order = $_GET["order"] ; }
		if( isset( $_GET["dir"]) ) { $this->table_order_dir = $_GET["dir"] ; }
		if( isset( $_GET["status_code"]) ) { $this->status_code = $_GET["status_code"] ; }
		//if( isset( $_GET["num_total"]) ) { $this->num_total_products = $_GET["num_total"] ; }

	}


	function get_encomendas(  ){
		$query = "select orders.*, order_status.status_pt as nome_estado
			from orders
			left join order_status on orders.status = order_status.id
			left join paises on orders.shipping_country = paises.id
			where step = 'terminar_encomenda' and order_status.status_code = '".$this->status_code."'";

		foreach ($_GET["pesquisa"] as $key => $value) {
			$key = str_replace("__", ".", $key);
			if ( $key == "orders.data_min" ) {
				$query .=  " and orders.data_criacao >= '".$value."'";
			}elseif ( $key == "orders.data_max" ) {
				$query .=  " and orders.data_criacao <= '".$value."'";
			}else{
				$query .=  " and ".$key." LIKE '%".$value."%'";
				
			}
		}



		$aux_res = mysql_query($query) or die_sql( $query );
		$this->num_total_encomendas = mysql_num_rows($aux_res);


		//Add Order
		$query .= " ORDER BY ".$this->table_order." ".$this->table_order_dir;
		//Add limit
		$query .= " LIMIT ".( $this->table_page * $this->table_display ).", ".$this->table_display;

		//echo $query;


		$res = mysql_query($query) or die_sql( $query );
		while ($row = mysql_fetch_array($res)) {
			$ret[] = $row;
		}
		return $ret;


	}



	function generate_url( $attr, $value ) {
		if ($attr) {
			if (is_string($attr)) {
				$a_a[] = $attr;
				$a_v[] = $value;
			}else{
				$a_a = $attr;
				$a_v = $value;
			}
		}

		$ret["page"] = $this->table_page;
		$ret["num"] = $this->table_display;
		$ret["order"] = $this->table_order;
		$ret["dir"] = $this->table_order_dir;
		if (isset($_GET["pesquisa"])) {
			$ret["pesquisa"] = $_GET["pesquisa"];
		}
		//$ret["num_total"] = $this->num_total_products;
		//$ret["num_total_pages"] = ceil( $this->num_total_products / $this->table_display );

		foreach ($a_a as $key => $value) {
			$ret[ $a_a[$key] ] = $a_v[ $key ];
		}

		$s = http_build_query( $ret );

		$s = "index.php?mod=catalogo_list&".$s;
		return $s;

	}

	function get_attrs(){
		$ret["page"] = $this->table_page;
		$ret["num"] = $this->table_display;
		$ret["order"] = $this->table_order;
		$ret["dir"] = $this->table_order_dir;
		$ret["num_total"] = $this->num_total_encomendas;
		$ret["status_code"] = $this->status_code;
		$ret["num_total_pages"] = ceil( $this->num_total_encomendas / $this->table_display );
		return $ret;
	}


}


 ?>