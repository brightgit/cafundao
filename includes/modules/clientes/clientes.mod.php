<?php 

class Clientes
{

	function __construct()
	{

	}
	
	public function get_processos_and_clients() {

		require_once( base_path( "includes/modules/users/users.mod.php" ) );

		$u = new Users();
		$this_user = $u->get_user_by_id( $_SESSION["user_bo"] ); //Futuro gráfico de comentários

		if ( $this_user["p_analise_risco"] ) {
			$extra_where = " and avaliacao = 2";	//Para análise de risco pode-se ver processos anteriores mas não se pode ver os não terminados.
		}else{
			$extra_where = "";
		}


		$clientes = $this->get_all_clientes();



		foreach ($clientes as $key => $value) {
			$query = "select * from processes where client_id = '".$value["id"]."'".$extra_where." order by `data` asc";
			$res = mysql_query($query) or die_sql( $query );

			while ( $row = mysql_fetch_array($res) ) {
				$clientes[ $key ]["processes"][] = $row;
			}



		}

		return $clientes;


	}

	public function get_all_clientes(){
		require_once( base_path( "includes/modules/users/users.mod.php" ) );

		$u = new Users();
		$this_user = $u->get_user_by_id( $_SESSION["user_bo"] ); //Futuro gráfico de comentários

		if ( $this_user["p_analise_risco"] ) {
			$where = " where processes.analise_risco = 1 ";
		}else{
			$where = "";
		}

		$query = "select clients.* from processes left join clients on clients.id = processes.client_id ".$where." group by clients.id order by `data` asc ";
		//echo $query;
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