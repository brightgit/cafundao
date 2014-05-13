<?php 
/**
* 
*/
class Workflow
{
	
	function __construct()
	{
		# code...
	}

	function get_all_evaluations(  ) {
		$query = "select * from processes where parent = 1 and avaliacao = 1";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			unset($aux);
			$aux["cliente"] = $row;
			$query = "select votos.*, count(votos.id) as num_votos, users.level from votos left join users on votos.user_id = users.id where process_id = '". $row["id"] ."' group by users.level";
			//echo $query;
			$res2 = mysql_query($query) or die_sql( $query );
			$aux["num_votos"]["admins"] = 0;
			$aux["num_votos"]["normal"] = 0;
			while( $row2 = mysql_fetch_array($res2) ) {
				if ($row2["level"] == 1) {
					$aux["num_votos"]["admins"] = $row2["num_votos"];
				}elseif ( $row2["level"]  == 2 ) {
					$aux["num_votos"]["normal"] = $row2["num_votos"];
				}
			}
			$ret[] = $aux;
		}
		return $ret;
	}

	function get_client_votos( $cliente_id ) {
		$query = "select votos.*, users.p_quality_vote, users.id as is_user, users.name as username from votos left join users on users.id = votos.user_id where votos.process_id = '".$cliente_id."'";
		$res = mysql_query($query);
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}



}

 ?>