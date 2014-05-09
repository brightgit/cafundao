<?php 

class Tags
{
	
	function __construct()
	{
		
	}
	
	function get_all_tags(  ){
		$query = "select * from tags order by sort_order asc";
		$res = mysql_query($query) or die_sql( $query );
		while ($row  = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}
	function get_all_tags_categories(  ){
		$query = "select * from tags_categories order by sort_order asc";
		$res = mysql_query($query) or die_sql( $query );
		while ($row  = mysql_fetch_array($res) ) {
			$ret[] = $row;
		}
		return $ret;
	}

	function get_tag_info( $id ) {
		//Categoria
		$query = "select * from tags where id = '".$id."'";
		$res = mysql_query($query) or die_sql( $query );
		$ret["tag"] = $tag = mysql_fetch_array($res);

		$query = "select * from users where id = '".$tag["criado_por"]."'";
		$res = mysql_query($query) or die_sql($query);
		$row = mysql_fetch_array($res);
		$data["user"] = $user = $row;

		//Categorias
		$query = "select * from tags_link_tags_categories left join tags_categories on tags_categories.id = tags_link_tags_categories.category_id where tag_id = '".$tag["id"]."'";
		$res = mysql_query($query) or die_sql( $query );
		while ( $row = mysql_fetch_array($res) ) {
			$ret["categories"][] = $row;
		}

		return $ret;

	}



}


 ?>