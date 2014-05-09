<?php 
/**
* 
*/
class Users
{
	
	function __construct()
	{
		# code...
	}

	public function get_all_users() {
		$query = "select * from users";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_user_by_id( $id ) {
		$query = "select * from users where id = '".$id."'";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_fetch_array($res);
	}

	function get_user_num_comments( $user_id ){
		$query = "select count(id) as num from comments where user_id = '".$user_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array($res);
		$ret["total_comments"] = $row["num"];

		$query = "select count(distinct(file_id)) as num from comments where user_id = '".$user_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array($res);
		$ret["total_files"] = $row["num"];

		return $ret;

	}

	function get_user_last_comments( $user_id ) {
		$query = "select comments.*, documents.id as d_id, documents.title as d_name from comments left join documents on documents.id = comments.file_id where user_id = '".$user_id."' order by date_in desc";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_last_uploaded_files( $user_id ) {
		$query = "select * from documents where criado_por = '".$user_id."' order by date_in desc limit 5";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_last_files_viewed( $user_id ) {
		$query = "select documents.*, access_documents.date_in as date_accessed from documents 
			left join access_documents on access_documents.document_id = documents.id 
			left join access_login on access_documents.session_id = access_login.id 
				where 
				access_login.user_id = '".$user_id."'
				order by access_documents.date_in desc limit 5";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_total_views_by_user( $user_id ) {
		$query = "select count(access_documents.session_id) as total_views, count( distinct( access_documents.document_id ) ) as total_files_viewed from access_documents left join access_login on access_login.id = access_documents.session_id where access_login.user_id = '".$user_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array($res);
		return $row;
	}

	function get_user_last_logins( $user_id ) {
		$query = "select * from access_login where user_id = '".$user_id."' order by date_in desc";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_user_num_logins( $user_id ) {
		$query = "select * from access_login where user_id = '".$user_id."'";
		$res = mysql_query($query) or die_sql( $query );
		return mysql_num_rows($res);
	}


	function get_total_uploads_by_user( $user_id ){
		$query ="select count(id) as num from documents where criado_por = '".$user_id."'";
		$res = mysql_query($query) or die_sql( $query );
		$row = mysql_fetch_array($res);
		return $row["num"];
	}

	function get_comments_by_month( $user_id ) {
		$query = "select count(id) as income, month(date_inserted) as year from comments where user_id = '".$user_id."' and date_inserted>'".date( "Y-m-d H:i:s", strtotime("-5 months") )."' group by month(date_inserted)";
		//echo $query;
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_object($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_folders_can_view($id){
		$sql = "SELECT folder_id, dc.name FROM user_permissions up LEFT JOIN documents_categories dc ON dc.id = up.folder_id WHERE can_view = 1 AND user_id = ".$id;
		$query = mysql_query($sql);
		if($query){
			while ($row = mysql_fetch_object($query)) {
				$folders[] = $row->folder_id;
			}

			return $folders;
		}
		return false;
	}

	function get_folders_can_upload($id){
		$sql = "SELECT folder_id, dc.name FROM user_permissions up LEFT JOIN documents_categories dc ON dc.id = up.folder_id WHERE can_upload = 1 AND user_id = ".$id;
		$query = mysql_query($sql);
		if($query){
			while ($row = mysql_fetch_object($query)) {
				$folders[] = $row->folder_id;
			}

			return $folders;
		}
		return false;
	}

}

 ?>