<?php 
/**
* 
*/
class Access_control
{
	
	function __construct()
	{
		# code...
	}

	function get_login( $login_id ){
		$query = "select * from access_login where id = '".$login_id."'";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_fetch_array($res);

	}

	function num_logins_by_user( $user_id ){
		$query = "select * from access_login where user_id = '".$user_id."'";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_num_rows($res);

	}

	function num_views_por_login( $session_id ){
		$query = "select * from access_documents where session_id = '".$session_id."'";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_num_rows($res);

	}

	function num_views_por_login_processes( $session_id ){
		$query = "select * from access_processes where session_id = '".$session_id."'";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_num_rows($res);

	}

	function num_views_por_user( $user_id ){
		$query = "select * from access_documents where session_id in (select id from access_login where user_id = '".$user_id."')";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_num_rows($res);

	}

	function num_views_por_user_processes( $user_id ){
		$query = "select * from access_processes where session_id in (select id from access_login where user_id = '".$user_id."')";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_num_rows($res);

	}


}
 ?>