<?php 

class Clientes
{

	function __construct()
	{
	}
	
	public function get_processos_and_clients() {
		$clientes = $this->get_all_clientes();


		foreach ($clientes as $key => $value) {
			$query = "select * from processes where client_id = '".$value["id"]."' order by `data` asc";
			$res = mysql_query($query) or die_sql( $query );

			while ( $row = mysql_fetch_array($res) ) {
				$clientes[ $key ]["processes"][] = $row;
			}



		}

		return $clientes;


	}

	public function get_all_clientes(){
		$query = "select clients.* from processes left join clients on clients.id = processes.client_id group by clients.id order by `data` asc ";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_cliente( $cliente_id ) {
		$query = "select * from clients where id = '".$cliente_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$ret = mysql_fetch_array($res);
		$query = "select * from processes where client_id = '".$cliente_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$ret["num_processos"] = mysql_num_rows($res);
		return $ret;
	}

	function get_processo( $p_id ){
		//processo
		$query ="select * from processes where id = '".$p_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$ret = mysql_fetch_array($res);

		//documentos do processo
		$query = "select * from documents where process_id = '".$p_id."'";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret["documents"][] = $row;
		}


		return $ret;
	}

}


 ?>