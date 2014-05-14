<?php 

/**
* Upload
*/
class Upload_lib extends Settings
{
	public $prefix = FALSE;
	public $dir = FALSE;
	public $host;
	public $user;
	public $password;
	public $conn;
	public $file = FALSE;


	function __construct()
	{
		parent::__construct();
	}

	function initialize(){
		
		$this->dir = $this->ftp_upload_folder;
		$this->prefix = $this->server_prefix;
		$this->conn = ftp_connect( $this->ftp_host );
		$login = ftp_login( $this->conn, $this->ftp_user, $this->ftp_password );

	}

	function set_extension( $name ){


		$a = explode( ".", $name );
		if ( is_array($a) && count($a) > 1 ) {
			$this->file->ext = $a[ ( count($a) - 1 ) ];
			return TRUE;
		}else{
			return FALSE;
		}
	}


	function generate_file_name( $name ) {

		$a = explode(".", $name);
		array_pop($a);
		$name = implode(".", $a);

		//New name
		$p_name = tools::url_title( $name ); //processed name

		if ( !file_exists( $this->prefix.'/'.$this->dir.'/'.$p_name.".".$this->file->ext ) ) {
			$this->file->name = $this->dir.'/'.$p_name.".".$this->file->ext;
			$this->file->display_name = $p_name.".".$this->file->ext;
			return TRUE;
		}else{
			$num = 1;
			$new_name = $p_name." (".$num.").".$this->file->ext;
			while ( 1 ) {
				if ( !file_exists( $this->prefix.'/'.$this->dir.'/'.$new_name ) ) {
					$this->file->name = $this->dir.'/'.$new_name;
					$this->file->display_name = $new_name;
					return TRUE;
				}else{
					$num ++;
					$new_name = $p_name." (".$num.").".$this->file->ext;
				}
			}
		}


	}

	public function upload( $file_path){

		if ( !$this->_check_file_info() ) {
			echo "Something wrong in _check_file_info() <br />";
		}
		//die($this->file->name);
		$upload = ftp_put($this->conn, $this->file->name, $file_path, FTP_BINARY);
		$file = FALSE;		
		return $upload;
	}


	function _check_file_info(){
		if ( !isset( $this->file->ext ) || !$this->file->ext ) {
			return false;
		}
		
		if ( !isset( $this->file->name ) || !$this->file->name ) {
			return false;
		}

		return TRUE;

	}


	function delete_file( $settings, $file_id ){
		$query = "SELECT * FROM `documents` where `id` = '".$file_id."'";
		$res = mysql_query($query) or die( mysql_error() );
		if (mysql_num_rows($res) == 0) {
			return false;
		}

		$file = mysql_fetch_object($res);
		$dir = $settings->get_setting("upload_folder");
		$server = $settings->get_setting("server_prefix");
		$file_name = $server.'/'.$file->file;
		unset( $file_name );

	}

	function __destruct(){
		ftp_close( $this->conn );
	}
}

?>