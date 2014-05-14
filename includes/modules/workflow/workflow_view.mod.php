<?php 
/**
* 
*/
class Workflow_view
{
	public $view_file = "workflow/workflow_view";

	function __construct()
	{
		require_once( "includes/modules/workflow/workflow.mod.php" );
		switch ($_GET["act"]) {
			case 'aceitar':
				$this->aceitar();
				break;
			case 'rejeitar':
				$this->rejeitar();
				break;
			case 'actualizar_cliente':
				$this->actualizar_cliente();
				break;
			case 'actualizar_processos':
				$this->actualizar_processos();
				break;
		}
	}

	function actualizar_cliente(  ) {
		$query = "update documents_categories set ";
		$query .= " metodo_voto = '".$_POST["metodo_voto"]."', ";
		$query .= " num_min_votos_qualidade = '".$_POST["num_min_votos_qualidade"]."', ";
		$query .= " num_min_votos = '".$_POST["num_min_votos"]."' ";
		$query .= " where id = '".$_POST["client_id"]."'";


		mysql_query($query) or die_sql( $query );

		$this->update_client_vote( $_POST["client_id"] );

		tools::notify_add( "Método de aprovação alterado.", "succcess" );

		redirect( "index.php?mod=workflow_view&id=".$_POST["client_id"] );


	}


	function aceitar(){
		$query = "update votos set vote_casted = 1 where category_id = '".$_GET["id"]."' and user_id = '".$_SESSION["user_bo"]."'";
		$res = mysql_query($query) or die_sql( $query );

		$this->update_client_vote( $_GET["id"] );

		tools::notify_add( "Voto alterado.", "success" );
		redirect( "index.php?mod=workflow_view&id=".$_GET["id"] );
	}

	function rejeitar() {
		$query = "update votos set vote_casted = 0 where category_id = '".$_GET["id"]."' and user_id = '".$_SESSION["user_bo"]."'";
		$res = mysql_query($query) or die_sql( $query );

		$this->update_client_vote( $_GET["id"] );

		tools::notify_add( "Voto alterado.", "success" );
		redirect( "index.php?mod=workflow_view&id=".$_GET["id"] );
	}

	function update_client_vote( $client_id ) {

		$query = "select * from documents_categories where id = '".$client_id."'";
		$res = mysql_query($query) or die_sql( $query );

		$client = mysql_fetch_array($res);


		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where category_id = '".$client_id."' and users.level = '1' and votos.vote_casted = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_admin = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where category_id = '".$client_id."' and users.level = '2' and votos.vote_casted = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_normais = mysql_num_rows( $res );

		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where category_id = '".$client_id."' and users.level = '1' and votos.vote_casted is null";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_admin_falta = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where category_id = '".$client_id."' and users.level = '2' and votos.vote_casted is null";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_normais_falta = mysql_num_rows( $res );


		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where category_id = '".$client_id."' and users.level = '1'";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_total_admin = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where category_id = '".$client_id."' and users.level = '2'";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_total_normais = mysql_num_rows( $res );



		switch ( $client["metodo_voto"] ) {
			case 'normal':
				if ( ($num_votos_normais + $num_votos_admin) >= $client["num_min_votos"] ) {	//Temos mais votos que os necessários, vamos aceitar.
					$query = "update documents_categories set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( ( $num_votos_admin + $num_votos_normais + $num_votos_normais_falta + $num_votos_admin_falta ) < $client["num_min_votos"] ) {
					//é impossível obter todos os votos a favor
					$query = "update documents_categories set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;
			
			case 'basta_um_rejeitar':
				if ( $num_votos_admin != $num_votos_total_admin && $num_votos_admin_falta == 0  ) {	//alguem rejeitou
					$query = "update documents_categories set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}elseif( $num_votos_admin_falta == 0  ) {	//Já ninguem pode rejeitar
					$query = "update documents_categories set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}
				break;
			case 'basta_um_aceitar':
				if ( $num_votos_admin > 0 ) {
					$query = "update documents_categories set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( $num_votos_admin == 0 && $num_votos_admin_falta == 0 ){	//ninguem aprovou
					$query = "update documents_categories set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;
			case 'maioria_qualidade':
				if ( ($num_votos_admin) >= $client["num_min_votos_qualidade"] ) {	//Temos mais votos que os necessários, vamos aceitar.
					$query = "update documents_categories set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( ( $num_votos_admin  + $num_votos_normais_falta ) < $client["num_min_votos_qualidade"] ) {
					//é impossível obter todos os votos a favor
					$query = "update documents_categories set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
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