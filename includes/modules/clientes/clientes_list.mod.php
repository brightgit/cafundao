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
			case 'analise_de_risco':	//Rejeitar o processo
				$this->analise_de_risco();
				break;
			case 'change_voting_method':	//Rejeitar o processo
				$this->change_voting_method();
				break;
			
			default:
				$this->ver();
				break;
		}
	}

	function ver(){
	}

	function analise_de_risco(){
		require_once( base_path( "includes/modules/emails/emails.mod.php" ) );
		$e = new Email();



		$query = "update processes set analise_risco = NULL, data_analise_risco = NOW(), analise_risco_texto = '".mysql_real_escape_string($_POST["analise_risco_texto"])."', analise_risco_user = '".$_SESSION["user_bo"]."' where id = '".$_POST["process_id"]."'";
		mysql_query($query) or die_sql( $query );


		$res_email_risco = mysql_query($query) or die_sql( $query );
		$query = "select * from processes left join clients on clients.id = processes.client_id where processes.id = '".$_POST["process_id"]."'";
		$row_email_risco = mysql_fetch_array($res_email_risco);
		include( "includes/views/emails/email_analise_risco.php" );



		$subject = "Novo processo em análise de risco.";

		$query = "select * from users where p_analise_risco = 1";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$email_to_send = str_replace("{name}", $row["name"], $email);
			$e->send_email( $row["email"], $subject, $email_to_send );
		}



		tools::notify_add( "Análise submetida com sucesso.", "success" );
		redirect( "index.php?mod=clientes_list&id=".$_POST["process_id"] );
	}

	function change_voting_method(){
		if (!isset($_POST["processo"])) {
			tools::notify_add("Método de decisão não alterado pois nenhum processo foi escolhido.", "error");
			redirect("index.php?mod=workflow_view");
		}
		//Processos a actualizar
		$processes_to_change = implode(",", $_POST["processo"]);
		$query = "update processes set ";
		$query .= " metodo_voto = '".$_POST["metodo_voto"]."', ";
		$query .= " num_min_votos = '".$_POST["num_min_votos"]."', ";
		$query .= " num_min_votos_qualidade = '".$_POST["num_min_votos_qualidade"]."' ";
		$query .= " where id in (".$processes_to_change.")";
		mysql_query( $query ) or die_sql( $query );


		//Actualizar o resultado
		foreach ($_POST["processo"] as $key => $value) {
			$this->update_client_vote( $value );
		}

		redirect("index.php?mod=workflow_view");


	}


	function avaliar(  ) {
		require_once( base_path( "includes/modules/emails/emails.mod.php" ) );
		$e = new Email();

		//Aqui falta colocar para análise de risco caso seja o caso
		//Ir buscar o montante
		$query = "select processes_form.montante from processes left join processes_form on processes_form.process_id = processes.id where processes.id = '".$_GET["id"]."'";
		$res = mysql_query($query) or die_sql( $query );
		$montante = mysql_fetch_array($res);
		if ( $montante["montante"] > 50000 ) {
			$extra_update = ", analise_risco = 1";
		}else{
			$extra_update = "";
		}


		$query = "update processes set avaliacao = 1".$extra_update." where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );



		//Email para analise de risco
		if ( $montante["montante"] > 50000 ) {

			//Dados
			$query = "select * from processes left join clients on clients.id = processes.client_id where processes.id = '".$_GET["id"]."'";
			$res_email_risco = mysql_query($query) or die_sql( $query );
			$row_email_risco = mysql_fetch_array($res_email_risco);
			include( "includes/views/emails/email_analise_risco.php" );



			$subject = "Novo processo em análise de risco.";

			$query = "select * from users where p_analise_risco = 1";
			$res = mysql_query($query) or die_sql( $query );
			while ( $row = mysql_fetch_array($res) ) {
				$email_to_send = str_replace("{name}", $row["name"], $email);
				$e->send_email( $row["email"], $subject, $email_to_send );
			}

		} else {
			//Emails para votar

			//Dados
			$query = "select * from processes left join clients on clients.id = processes.client_id where processes.id = '".$_GET["id"]."'";
			$res_email_risco = mysql_query($query) or die_sql( $query );
			$row_email_risco = mysql_fetch_array($res_email_risco);
			include( "includes/views/emails/email_para_votacao.php" );



			$subject = "Novo processo em análise de risco.";

			$query = "select * from users where p_vote = 1 OR p_quality_vote = 1";
			$res = mysql_query($query) or die_sql( $query );
			while ( $row = mysql_fetch_array($res) ) {
				$email_to_send = str_replace("{name}", $row["name"], $email);
				$e->send_email( $row["email"], $subject, $email_to_send );
			}



		}


		$query = "select * from users where p_vote = 1 or p_quality_vote = 1";	//Utilizadores que podem votar
		$res = mysql_query($query) or die_sql( $query );

		$num_total_votos = mysql_num_rows($res);


		while ( $row = mysql_fetch_array($res) ) {
			$query = "insert into votos values (NULL, '".$_GET["id"]."', '".$row["id"]."', NULL)";
			mysql_query($query) or die_sql( $query );
		}

		$query = "select * from users where p_quality_vote = 1 ";	//Votos de qualidade
		$res = mysql_query($query) or die_sql( $query );

		$num_total_votos_admin = mysql_num_rows($res);

		$metade_dos_votos = floor( $num_total_votos/2 )+1;
		$query = "update processes set num_min_votos = '".$metade_dos_votos."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		$metade_dos_votos_admin = floor( $num_total_votos_admin/2 )+1;
		$query = "update processes set num_min_votos_qualidade = '".$metade_dos_votos_admin."' where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );

		tools::notify_add( "Processo colocado para avaliação.", "success" );
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
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.p_quality_vote = 1 and votos.vote_casted = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_admin = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.p_quality_vote = 0 and votos.vote_casted = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_normais = mysql_num_rows( $res );

		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.p_quality_vote = 1 and votos.vote_casted is null";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_admin_falta = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.p_quality_vote = 0 and votos.vote_casted is null";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_normais_falta = mysql_num_rows( $res );


		//Ir buscar num votos admins
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.p_quality_vote = 1";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_total_admin = mysql_num_rows( $res );

		//Ir buscar num votos normais
		$query = "select * from votos left join users on users.id = votos.user_id where process_id = '".$client_id."' and users.p_quality_vote = 0";
		$res = mysql_query($query) or die_sql( $query );
		$num_votos_total_normais = mysql_num_rows( $res );



		switch ( $client["metodo_voto"] ) {
			case 'normal':
				if ( ($num_votos_normais + $num_votos_admin) >= $client["num_min_votos"] ) {	//Temos mais votos que os necessários, vamos aceitar.
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_aprovacao( $client["id"] );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( ( $num_votos_admin + $num_votos_normais + $num_votos_normais_falta + $num_votos_admin_falta ) < $client["num_min_votos"] ) {
					//é impossível obter todos os votos a favor
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_rejeicao( $client["id"] );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;
			
			case 'basta_um_rejeitar':
				if ( $num_votos_admin != $num_votos_total_admin && $num_votos_admin_falta == 0  ) {	//alguem rejeitou
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_rejeicao( $client["id"] );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}elseif( $num_votos_admin_falta == 0  ) {	//Já ninguem pode rejeitar
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_aprovacao( $client["id"] );
					tools::notify_add( "Cliente aprovado.", "success" );
				}
				break;
			case 'basta_um_aceitar':
				if ( $num_votos_admin > 0 ) {
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_aprovacao( $client["id"] );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( $num_votos_admin == 0 && $num_votos_admin_falta == 0 ){	//ninguem aprovou
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_rejeicao( $client["id"] );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;
			case 'maioria_qualidade':
				if ( ($num_votos_admin) >= $client["num_min_votos_qualidade"] ) {	//Temos mais votos que os necessários, vamos aceitar.
					$query = "update processes set resultado = '1', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_aprovacao( $client["id"] );
					tools::notify_add( "Cliente aprovado.", "success" );
				}elseif( ( $num_votos_admin  + $num_votos_normais_falta ) < $client["num_min_votos_qualidade"] ) {
					//é impossível obter todos os votos a favor
					$query = "update processes set resultado = '0', avaliacao = '2', data_avaliacao = NOW() where id = '".$client["id"]."'";
					mysql_query( $query ) or die_sql( $query );
					$this->email_rejeicao( $client["id"] );
					tools::notify_add( "Cliente rejeitado.", "success" );
				}
				break;


			default:
				# code...
				break;
		}


	}

	function email_aprovacao( $process_id ) {
		require_once( base_path( "includes/modules/emails/emails.mod.php" ) );
		$e = new Email();
		//Dados
		$query = "select * from processes left join clients on clients.id = processes.client_id where processes.id = '".$process_id."'";
		$res_email_risco = mysql_query($query) or die_sql( $query );
		$row_email_risco = mysql_fetch_array($res_email_risco);
		include( "includes/views/emails/email_aprovacao.php" );



		$subject = "Novo processo em análise de risco.";

		$query = "select * from users where p_analise_risco = 1";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$email_to_send = str_replace("{name}", $row["name"], $email);
			$e->send_email( $row["email"], $subject, $email_to_send );
		}

	}

	function email_rejeicao( $process_id ) {
		require_once( base_path( "includes/modules/emails/emails.mod.php" ) );
		$e = new Email();
		//Dados
		$query = "select * from processes left join clients on clients.id = processes.client_id where processes.id = '".$process_id."'";
		$res_email_risco = mysql_query($query) or die_sql( $query );
		$row_email_risco = mysql_fetch_array($res_email_risco);
		include( "includes/views/emails/email_aprovacao.php" );



		$subject = "Novo processo em análise de risco.";

		$query = "select * from users where p_analise_risco = 1";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$email_to_send = str_replace("{name}", $row["name"], $email);
			$e->send_email( $row["email"], $subject, $email_to_send );
		}

	}




}


 ?>