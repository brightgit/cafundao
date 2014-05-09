<?php 
/**
* 
*/
class Inbox
{
	
	function __construct()
	{
		# code...
	}


	function get_unprocessed_emails() {
		$query = "select * from emails where processed = 0";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}


	function get_all_folder(){
		$query = "select * from documents_categories";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function is_email_in_folder( $folder_id, $email ) {
		$query = "select * from emails_by_folder where email = '".$email."' and folder_id = '".$folder_id."'";
		//echo $query;
		$res = mysql_query($query);
		if (mysql_num_rows($res)) {
			return true;
		}
		return false;
	}


	function get_all_tags( ) {
		$query  = "select * from tags where active = 1";
		$res = mysql_query($query);
		while ($row = mysql_fetch_array($res)) {
			$ret[] = $row;
		}
		return $ret;
	}








}

 ?>