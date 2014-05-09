<?php 
	$modules[ "login" ] = array( 'file' => "login/login", "login" => FALSE );
	$modules[ "ajax" ] = array( 'file' => "ajax/ajax", "login" => FALSE );
	$modules[ "upload" ] = array( 'file' => "upload/upload", "login" => TRUE );
	$modules[ "home" ] = array( 'file' => "home", "login" => TRUE );
	$modules[ "catalogo" ] = array( 'file' => "catalogo/catalogo", "login" => TRUE );
	$modules[ "files" ] = array( 'file' => "files/files", "login" => TRUE );

	$modules[ "files_view" ] = array( 'file' => "files/files_view", "login" => TRUE );

	//Tags
	$modules[ "tags_list" ] = array( 'file' => "tags/tags_list", "login" => TRUE );

	$modules[ "pesquisa_avancada" ] = array( 'file' => "pesquisa/pesquisa_avancada", "login" => TRUE );

	//Users
	$modules[ "users_list" ] = array( 'file' => "users/users_list", "login" => TRUE );
	$modules[ "users_view" ] = array( 'file' => "users/users_view", "login" => TRUE );

	$modules[ "inbox_list" ] = array( 'file' => "inbox/inbox_list", "login" => TRUE );

	$modules[ "workflow_view" ] = array( 'file' => "workflow/workflow_view", "login" => TRUE );
	
	$modules[ "access_control_list" ] = array( 'file' => "access_control/access_control_list", "login" => TRUE );
	
	//$modules[ "catalogo_list" ] = array( 'file' => "catalogo/catalogo_list", "login" => TRUE );
	//$modules[ "catalogo_edit" ] = array( 'file' => "catalogo/catalogo_edit", "login" => TRUE );

	$modules[ "settings" ] = array( 'file' => "settings/settings", "login" => TRUE );
	$modules[ "seo" ] = array( 'file' => "seo/seo", "login" => TRUE );
	$modules[ "clientes_list" ] = array( 'file' => "clientes/clientes_list", "login" => TRUE );
	$modules[ "subscritores_list" ] = array( 'file' => "subscritores/subscritores_list", "login" => TRUE );
?>