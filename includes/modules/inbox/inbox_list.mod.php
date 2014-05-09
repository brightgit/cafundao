<?php 
/**
* 
*/
class Inbox_list
{
	public $view_file = "inbox/inbox_list";

	function __construct()
	{
		require_once( "includes/modules/inbox/inbox.mod.php" );
		switch ($_GET["act"]) {
			case 'proccess_document':
				$this->proccess_document();
				break;
			case 'reject_email':
				$this->reject_email();
				break;
		}
	}

	function reject_email() {
		$query = "update emails set processed = 1 where id = '".$_GET["id"]."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Email rejeitado.", "success");
		redirect( "index.php?mod=inbox_list" );
	}

	function proccess_document() {
		if (isset($_POST["reject_document"])) {
			$query = "update emails set processed = 1 where id = '".$_POST["email_id"]."'";
			//mysql_query($query) or die_sql( $query );
			tools::notify_add("Email rejeitado.", "success");
			redirect( "index.php?mod=inbox_list" );
		}

		//GET EMAIL
		$query = "select * from emails where id = '".$_POST["email_id"]."'";
		$res = mysql_query($query) or die();
		$email = mysql_fetch_array($res);
		$files = explode(",", $email["attachments"]);


		foreach ($_POST["tags"] as $key => $value) {
			$query = "select * from tags where tag = '".$value."'";
			$res = mysql_query($query);
			if ( mysql_num_rows($res) == 0 ) {
				$query = "insert into tags values (null, '".$value."', 1, 0, '".$_SESSION["user_bo"]."', NOW() )";
				mysql_query($query) or die_sql( $query );
				$query = "select * from tags order by id desc";
				$res = mysql_query($query);
				$row = mysql_fetch_array($res);
				$tag_ids[] = $row["id"];

				$query = "insert into tags_link_documents_categories values (null, '".$row["id"]."', '".$_POST["category_id"]."' )";
				mysql_query($query) or die_sql( $query );

			}else{
				$row = mysql_fetch_array($res);
				$tag_ids[] = $row["id"];
			}
		}

		//Tags da list
		foreach ($_POST["mais_tags_da_lista"] as $key => $value) {
			$tag_ids[] = $value;
		}

		//Novas tags para a aplicação
		$a = explode(",", $_POST["add_new_tags"]);
		foreach ($a as $key => $value) {
			$value = trim( $value );
			if (empty($value)) {
				continue;
			}
			$query = "select * from tags where tag = '".$value."'";
			$res = mysql_query($query);
			if ( mysql_num_rows($res) == 0 ) {
				$query = "insert into tags values (null, '".$value."', 1, 0, '".$_SESSION["user_bo"]."', NOW() )";
				mysql_query($query) or die_sql( $query );
				$query = "select * from tags order by id desc";
				$res = mysql_query($query);
				$row = mysql_fetch_array($res);

				$query = "insert into tags_link_documents_categories values (null, '".$row["id"]."', '".$_POST["category_id"]."' )";
				mysql_query($query) or die_sql( $query );


				$tag_ids[] = $row["id"];
			}else{
				$row = mysql_fetch_array($res);
				$tag_ids[] = $row["id"];
			}
		}

		//Ficheiros
		foreach ($_POST["new_file_name"] as $key => $value) {


			$value = $value.'.'.$_POST["new_file_extension"][$key];

			$file = $files[ $key ];

			/*
			$conn = ftp_connect( "95.154.220.60" );
			$login = ftp_login( $conn, "upload@virtualdataroom.pt", "Hugo#$12" );
			if( !file_exists("../virtualdataroom_upload/unprocessed/".$file) ){
				die("../virtualdataroom_upload/unprocessed/".$file);
				exit();
			}
			$upload = ftp_put( $conn, "virtualdataroom_upload/".$value, "../virtualdataroom_upload/unprocessed/".$file, FTP_BINARY);
			ftp_close( $conn );
			*/

			rename( "../../virtualdataroom_upload/unprocessed/".$file, "../../virtualdataroom_upload/".$value );

			$query = "insert into documents set ";
				$query .= " title = '".$value."', ";
				$query .= " category = '".$_POST["category_id"]."', ";
				$query .= " file = '".$value."', ";
				$query .= " already_processed = 1, ";
				$query .= " uploaded_from = 'email', ";
				$query .= " criado_por = '".$_SESSION["user_bo"]."'";

			mysql_query($query) or die_sql( $query );

			$query = "select * from documents order by id desc";
			$res = mysql_query($query) or die_sql( $query );
			$row = mysql_fetch_array($res);
			$d_id = $row["id"];

			foreach ($tag_ids as $tag) {
				$query = "insert into tags_link_documents values (null, '".$tag."', '".$d_id."')";
				mysql_query($query) or die_sql( $query );
			}

		}


		$query = "update emails set processed = 1 where id = '".$_POST["email_id"]."'";
		mysql_query($query) or die_sql( $query );
		tools::notify_add("Email aceitado.", "success");
		redirect( "index.php?mod=inbox_list" );






	}



}

 ?>