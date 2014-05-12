<?php 

class Clientes_list
{
	public $view_file = "clientes/clientes_list";
	function __construct()
	{
		include base_path("includes/modules/clientes/clientes.mod.php");
		switch ($_GET["act"]) {
			case 'list_process':	//Listar Cliente / Processo. ISto é ajax
				$this->list_process();
				break;
			case 'avaliar':	//Coloca o processo para avaliação
				$this->avaliar();
				break;
			
			case 'aceitar':	//Aceitar o processo
				$this->aceitar();
				break;
			case 'rejeitar':	//Rejeitar o processo
				$this->rejeitar();
				break;
			
			default:
				$this->ver();
				break;
		}
	}

	function ver(){
	}


	function avaliar(  ) {
		//Aqui falta colocar para a´nálise de risco caso seja o caso

		$query = "update processes set avaliacao = 1 where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		$query = "select * from users where level < 3";
		$res = mysql_query($query) or die_sql( $query );

		$num_total_votos = mysql_num_rows($res);


		while ( $row = mysql_fetch_array($res) ) {
			$query = "insert into votos values (NULL, '".$_GET["id"]."', '".$row["id"]."', NULL)";
			mysql_query($query) or die_sql( $query );
		}

		$query = "select * from users where level < 2";
		$res = mysql_query($query) or die_sql( $query );

		$num_total_votos_admin = mysql_num_rows($res);

		$metade_dos_votos = floor( $num_total_votos/2 )+1;
		$query = "update processes set num_min_votos = '".$metade_dos_votos."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		$metade_dos_votos_admin = floor( $num_total_votos_admin/2 )+1;
		$query = "update processes set num_min_votos_qualidade = '".$metade_dos_votos_admin."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		tools::notify_add( "Cliente colocado para avaliação.", "success" );
		redirect( "index.php?mod=clientes_list" );

	}


	function list_process() {
		$this->load_header = FALSE;
		$this->load_menu = FALSE;
		$this->load_footer = FALSE;
		$this->view_file = "clientes/list_process";

	}
	/*

	function actualizar_cliente(  ) {
		$query = "update clients set ";
		$query .= " metodo_voto = '".$_POST["metodo_voto"]."', ";
		$query .= " num_min_votos_qualidade = '".$_POST["num_min_votos_qualidade"]."', ";
		$query .= " num_min_votos = '".$_POST["num_min_votos"]."' ";
		$query .= " where id = '".$_POST["client_id"]."'";


		mysql_query($query) or die_sql( $query );

		$this->update_client_vote( $_POST["client_id"] );

		tools::notify_add( "Método de aprovação alterado.", "succcess" );

		redirect( "index.php?mod=workflow_view&id=".$_POST["client_id"] );


	}
	*/


	function aceitar(){
		$query = "update votos set vote_casted = 1 where process_id = '".$_GET["id"]."' and user_id = '".$_SESSION["user_bo"]."'";
		$res = mysql_query($query) or die_sql( $query );

		$this->update_client_vote( $_GET["id"] );

		tools::notify_add( "Voto alterado.", "success" );
		redirect( "index.php?mod=clientes_list&id=".$_GET["id"] );
	}

	function rejeitar() {
		$query = "update votos set vote_casted = 0 where process_id = '".$_GET["id"]."' and user_id = '".$_SESSION["user_bo"]."'";
		$res = mysql_query($query) or die_sql( $query );

		$this->update_client_vote( $_GET["id"] );

		tools::notify_add( "Voto alterado.", "success" );
		redirect( "index.php?mod=clientes_list&id=".$_GET["id"] );
	}

	function update_client_vote( $client_id ) {

		$query = "select * from processes where id = '".$client_id."'";
		$res = mysql_query($query) or die_sql( $query );

		$client = mysql_fetch_array($res);


		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.level = '1' and votos.vote_casted = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_admin = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.level = '2' and votos.vote_casted = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_normais = mysql_num_rows( $res );

		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.level = '1' and votos.vote_casted is null";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_admin_falta = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.level = '2' and votos.vote_casted is null";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_normais_falta = mysql_num_rows( $res );


		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.level = '1'";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_total_admin = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.level = '2'";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_total_normais = mysql_num_rows( $res );



		switch ( $client["metodo_voto"] ) {
			case 'normal':
				if ( ($num_votos_normais + $num_votos_admin) >= $client["num_min_votos"] ) {	//Temos mais votos que os necessários, vamos aceitar.
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( ( $num_votos_admin + $num_votos_normais + $num_votos_normais_falta + $num_votos_admin_falta ) < $client["num_min_votos"] ) {
					//é impossível obter todos os votos a favor
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;
			
			case 'basta_um_rejeitar':
				if ( $num_votos_admin != $num_votos_total_admin && $num_votos_admin_falta == 0  ) {	//alguem rejeitou
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}elseif( $num_votos_admin_falta == 0  ) {	//Já ninguem pode rejeitar
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}
				break;
			case 'basta_um_aceitar':
				if ( $num_votos_admin > 0 ) {
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( $num_votos_admin == 0 && $num_votos_admin_falta == 0 ){	//ninguem aprovou
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;
			case 'maioria_qualidade':
				if ( ($num_votos_admin) >= $client["num_min_votos_qualidade"] ) {	//Temos mais votos que os necessários, vamos aceitar.
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( ( $num_votos_admin  + $num_votos_normais_falta ) < $client["num_min_votos_qualidade"] ) {
					//é impossível obter todos os votos a favor
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;


			default:
				# code...
				break;
		}


	}




}


 ?>