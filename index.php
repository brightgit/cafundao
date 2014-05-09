<?php

// Isto é definido no core
//error_reporting(E_ALL ^E_NOTICE ^E_WARNING ^E_STRICT);
//ini_set('display_errors',1);
//$time = round(microtime(), 3);
//header("Content-Type: text/html; charset=UTF-8");

include ("includes/core_admin.php");
$core = new Core_admin();



//Por defeito carrega o header
if ( !isset($core->mod_data->load_header) || $core->mod_data->load_header == TRUE ) {
	echo $core->load_view( "includes/views/header.php", $core->mod_data );
}

if ( !isset($core->mod_data->load_menu) || $core->mod_data->load_menu == TRUE ) {
	echo $core->load_view( "includes/views/menu.php", $core );
}

echo $core->load_view( "includes/views/".$core->mod_data->view_file.".php", $core->mod_data );

//Por defeito carrega o Footer
if ( !isset($core->mod_data->load_footer) || $core->mod_data->load_footer == TRUE ) {
	include "includes/views/footer.php";
}
