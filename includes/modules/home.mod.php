<?php 

/**
* 
*/
class Home
{

	public $view_file = "homepage";
	public $data;
	
	function __construct()
	{
		require_once( "includes/modules/files/files.mod.php" );
	}



	function get_ficheiros_a_processar() {
		$query = "SELECT * from documents where already_processed = 0";
		$res = mysql_query($query) or die_sql();
		return mysql_num_rows($res);
	}

	function get_total_ficheiros() {
		$query = "select * from documents";
		$res = mysql_query($query) or die_sql();
		return mysql_num_rows($res);
	}

	function get_total_pastas() {
		$query = "select * from clients";
		$res = mysql_query($query) or die_sql();
		return mysql_num_rows($res);
	}
	function get_total_tags() {
		$query = "select * from tags";
		$res = mysql_query($query) or die_sql();
		return mysql_num_rows($res);
	}

	function get_latest_aprovals() {
		$query = "SELECT p.*, count(votos.id) as num
			from processes p
				left join votos on votos.process_id = p.id 
					where 
					resultado is not null 
					group by p.id order by data_avaliacao desc limit 5";

		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_object($res) ) {
			$ret[] = $row;
			$query = "SELECT * FROM votos where vote_casted = 1 and process_id = ".$row->id;
			$res2 = mysql_query($query);
			$row->num_sim = mysql_num_rows( $res2 );
			$query = "SELECT * FROM votos where vote_casted = 0 and process_id = ".$row->id;
			$res2 = mysql_query($query);
			$row->num_nao = mysql_num_rows( $res2 );
		}
		return $ret;
	}


	function get_latest_views(){
		$sql = "SELECT u.id, u.email, u.name, ad.`date_in`, d.file, d.title AS document_name, d.id AS document_id FROM access_documents ad INNER JOIN access_login al ON al.`id` = ad.`session_id` INNER JOIN documents d ON d.id = ad.document_id INNER JOIN users u ON u.id = al.user_id ORDER BY ad.date_in DESC";
		$query = mysql_query($sql);
		if($query){
			while ($row = mysql_fetch_object($query))
				$output[] = $row;
			return $output;
		}
		return false;
	}

	function get_latest_accesses(){
		$sql = "SELECT u.id, u.name, u.email, ac.date_in, ac.ip_address FROM access_login ac INNER JOIN users u ON u.id = ac.user_id ORDER BY ac.date_in DESC";
		$query = mysql_query($sql);
		if($query){
			while ($row = mysql_fetch_object($query))
				$output[] = $row;
			return $output;
		}
		return false;
	}

	//retorna dados estatísticos / numéricos, num array - ** WARNING ** esta function pode tornar-se pesada 
	function get_stats(){
		

		//parâmetros
		$current_month = date("m");
		$current_year = date("Y");
		$previous_month = $current_month - 1;

		//1 - total de documentos em comparação com o mês anterior
		$sql = "SELECT COUNT(id) AS total FROM documents UNION SELECT COUNT(id) AS total FROM documents WHERE MONTH(date_in) < ".$current_month." AND YEAR(date_in) <= ".$current_year;
		$query = mysql_query($sql);
		if ($query) {
			while($row = mysql_fetch_object($query))
				$total_documentos[] = $row;
		}

		//escrever nas estatisticas
		$stats["documents"]["this_month"] = $total_documentos[0]->total;
		$stats["documents"]["previous_month"] = $total_documentos[1]->total;
		$stats["documents"]["percentage"] = $percentage = ($stats["documents"]["this_month"] / $stats["documents"]["previous_month"] * 100);
		$stats["documents"]["total"] = $total_documentos[0]->total;

		//2 - total de users em comparação com o mês anterior
		$sql = "SELECT COUNT(id) AS total FROM users UNION SELECT COUNT(id) AS total FROM users WHERE MONTH(date_in) < ".$current_month." AND YEAR(date_in) <= ".$current_year;
		$query = mysql_query($sql);
		if ($query) {
			while($row = mysql_fetch_object($query))
				$users[] = $row;
		}
		

		//escrever nas estatisticas
		$stats["users"]["this_month"] = $users[0]->total;
		$stats["users"]["previous_month"] = $users[1]->total;
		$stats["users"]["percentage"] = ($stats["users"]["this_month"] / $stats["users"]["previous_month"] * 100);
		$stats["users"]["total"] = $users[0]->total;

		//3 - total de clientes em comparação com o mês anterior
		$sql = "SELECT COUNT(id) AS total FROM clients UNION SELECT COUNT(id) AS total FROM clients WHERE MONTH(date_in) < ".$current_month." AND YEAR(date_in) <= ".$current_year;
		$query = mysql_query($sql);
		if ($query) {
			while($row = mysql_fetch_object($query))
				$clients[] = $row;
		}	

		//escrever nas estatisticas
		$stats["clients"]["this_month"] = (int) $clients[0]->total;
		$stats["clients"]["previous_month"] = (int) $clients[1]->total;
		$stats["clients"]["percentage"] = ($stats["clients"]["this_month"] / $stats["clients"]["previous_month"] * 100);
		$stats["clients"]["total"] = $clients[0]->total;

		//4 - total de comments
		$sql = "SELECT COUNT(id) AS total FROM comments UNION SELECT COUNT(id) AS total FROM comments WHERE MONTH(date_in) < ".$current_month." AND YEAR(date_in) <= ".$current_year;
		$query = mysql_query($sql);
		if ($query) {
			while($row = mysql_fetch_object($query))
				$comments[] = $row;
		}	

		//escrever nas estatisticas
		$stats["comments"]["this_month"] = (int) $comments[0]->total;
		$stats["comments"]["previous_month"] = (int) $comments[1]->total;
		$stats["comments"]["percentage"] = ($stats["comments"]["this_month"] / $stats["comments"]["previous_month"] * 100);
		$stats["comments"]["total"] = (int) $comments[0]->total;

		//4 - total de comments
		$sql = "SELECT COUNT(id) AS total FROM clients UNION SELECT COUNT(id) AS total FROM clients WHERE MONTH(data) < ".$current_month." AND YEAR(data) <= ".$current_year;
		$query = mysql_query($sql);
		if ($query) {
			while($row = mysql_fetch_object($query))
				$folders[] = $row;
		}	

		//escrever nas estatisticas
		$stats["folders"]["this_month"] = (int) $folders[0]->total;
		$stats["folders"]["previous_month"] = (int) $folders[1]->total;
		$stats["folders"]["percentage"] = ($stats["folders"]["this_month"] / $stats["folders"]["previous_month"] * 100);
		$stats["folders"]["total"] = (int) $folders[0]->total;

		//5 - tags não identificadas no sistema
		$sql = "SELECT body FROM emails WHERE processed = 0";
		$query = mysql_query($sql);

		if($query){
			while ($row = mysql_fetch_object($query)) {
				//regex
				preg_match_all('/#([a-zA-z0-9 -]*)#/', $row->body, $all_tags);
				
				//as tags "limpas" estão na posição 1
				foreach ($all_tags[1] as $tag) {
					//manter o array limpo com tags únicas
					if(!in_array($tag, $tags))
						$tags[] = $tag;
				}				
			}
		}

		//total de tags
		$total_tags_to_check = count($tags);
		$tags_to_string = implode("\",\"", $tags);

		$sql = "SELECT COUNT(id) AS total FROM tags WHERE tag IN (\"".$tags_to_string."\")";
		$query = mysql_query($sql);
		$total_tags_matched_in_db = mysql_fetch_object($query)->total;

		$total_tags_unprocessed = $total_tags_to_check - $total_tags_matched_in_db;

		//escrever no return
		$stats["tags"]["unprocessed"] = $total_tags_unprocessed;

		//retornar o array de stats
		return $stats;

	}


	function get_processos_por_processar() {
		$query = "select * from processes where avaliacao = 1";
		return mysql_num_rows( mysql_query($query) );
	}
	function get_processos_totais() {
		$query = "select * from processes where 1";
		return mysql_num_rows( mysql_query($query) );
	}


	function get_analises_em_falta(){
		$query = "select * from processes where analise_risco = 1";
		return mysql_num_rows( mysql_query($query) );		
	}

}


 ?>