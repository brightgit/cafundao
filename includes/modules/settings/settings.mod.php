<?php 
/**
* 
*/
class Settings
{
	public $view_file = "settings/settings";

	function __construct()
	{

		if (isset($_POST["submit"])) {
			$this->update_settings();
		}

		$this->ver();
	}

	function ver(){

	}

	function get_settings(){
		$query = "select * from settings order by `group`, display_name";
		$res = mysql_query($query) or die_sql( $query );
		while ($row = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function update_settings(){
		foreach ($_POST as $key => $value) {
			if ($key == "submit") {
				continue;
			}
			
			$query = "update settings set value = '".$_POST[$key]."' where name = '".$key."'";
			mysql_query($query) or die_sql( $query );


		}
	}


}

 ?>