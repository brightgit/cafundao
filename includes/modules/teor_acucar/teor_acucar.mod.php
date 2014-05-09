<?php 

class Teor_acucar
{
	
	function __construct()
	{
		# code...
	}
	
	function simple_get_all(){
		$query = "select * from teor_acucar order by sort_order ASC";
		$res = mysql_query($query) or die_sql( $query );
		while ($row = mysql_fetch_array($res)) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_by_id( $id ){
		$query = "select * from teor_acucar where id = '".$id."'";
		$res = mysql_query($query) or die_sql( $query );
		if ( mysql_num_rows($res) !== 1 ) {
			return FALSE;
		}

		return mysql_fetch_array($res);

	}


}


 ?>