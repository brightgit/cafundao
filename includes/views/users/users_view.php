<?php 

require_once(base_path("includes/modules/files/files.mod.php"));

$users_c = new Users();
$files = new Files();


$user_val = $user = $users_c->get_user_by_id( $_GET["id"] );
foreach ($user_val as $key => $value) {
	if (isset($_POST[$key])) {
		$user_val[ $key ] = $_POST[ $key ];
	}
}

$this_user = $users_c->get_user_by_id( $_SESSION["user_bo"] ); //Futuro gráfico de comentários

//Comentários
$last_coments = $users_c->get_user_last_comments( $_GET["id"] );
$num_comments = $users_c->get_user_num_comments( $_GET["id"] );

//Comentários
$last_logins = $users_c->get_user_last_logins( $_GET["id"] );
$num_logins = $users_c->get_user_num_logins( $_GET["id"] );

//Ficheiros
$last_files = $users_c->get_last_uploaded_files( $_GET["id"] );
$last_views = $users_c->get_last_files_viewed( $_GET["id"] );
$total_views = $users_c->get_total_views_by_user( $_GET["id"] );
$total_uploads = $users_c->get_total_uploads_by_user( $_GET["id"] );
//$num_files = $users_c->get_num_files( $_GET["id"] );

//Permissões (raw, tem de ser mais complexo - apenas para apresentar)
$folders_view_permissions = $users_c->get_folders_can_view($_GET["id"]);
$folders_upload_permissions = $users_c->get_folders_can_upload($_GET["id"]);

$folders = $files->get_all_folders();


?>

<div class="wrapper scrollable extended" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="index.php"><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a data-toggle="dropdown" href="#">Utilizadores</a>
	    </li>
	</ol>

	<div class="special-logo pull-right">
        <div class="clearfix">
            <h5 onclick="window.location='index.php'">
                <span class="title text-right">
                    Virtual Data Room
                </span>
                <span class="subtitle">
                    Powered By Bright & Digidoc
                </span>
            </h5>
        </div>
	</div>



	<div class="panel panel-default panel-block panel-title-block">
		<div class="panel-heading">
		    <div>
		        <i class="icon-group"></i>
		        <h1>
		            <span>Virtual Data Room - Utilizadores</span>
		            <small>
		                A gerir detalhes do utilizador
		            </small>
		        </h1>
		    </div>
		</div>
	</div>
	 <div class="col-lg-4 pull-right">
        <ul class="nav nav-tabs panel panel-default panel-block">
            <!-- li><a href="#user-permissions" data-toggle="tab">Permissões</a></li -->
            <li><a href="#user-comments" data-toggle="tab">Comentários</a></li>
            <li class="active"><a href="#user-files" data-toggle="tab">Ficheiros</a></li>
            <li><a href="#user-logins" data-toggle="tab">Logins</a></li>
        </ul>
        <div class="tab-content panel panel-default panel-block">
            <div class="tab-pane list-group" id="user-permissions">
                <div class="list-group-item">
                	<p>Permissões do utilizador</p>
				</div>							 			
			</div>
			

             <div class="tab-pane list-group" id="user-comments">
                <div class="list-group-item">
                	<small>Total de <?php echo $num_comments["total_comments"]; ?> comentários em <?php echo $num_comments["total_files"]; ?> ficheiros.</small>
                	<h4>Últimos comentários</h4>
            		<?php if ($last_coments): ?>
            			<hr />
            		<?php foreach ($last_coments as $key => $value): ?>
                			<div><small><strong><?php echo $value["title"] ?></strong> | <?php echo date( "Y-m-d", strtotime($value["date_inserted"]) ) ?></small></div>
                			<div><?php echo $value["comment"]; ?></div>
                			<a href="index.php?mod=files_view&id=<?php echo $value["d_id"]; ?>"><?php echo $value["d_name"]; ?></a>
            				<hr />
            		<?php endforeach ?>
            		<?php else: ?>
            			<p>Nunca comentou</p>
            		<?php endif ?>
				</div>							 			
			</div>

             <div class="tab-pane list-group active" id="user-files">
                <div class="list-group-item">
                	<h4>Últimos Uploads</h4>
                	<small>Total de <?php echo $total_uploads; ?> uploads.</small><br />
            		<?php if ($last_files): ?>
            		<?php foreach ($last_files as $key => $value): ?>
                			<a href="index.php?mod=files_view&id=<?php echo $value["id"]; ?>"><?php echo $value["title"]; ?></a>
                			| <?php echo date("Y-m-d", strtotime($value["date_in"])) ?>
                			<br />
            		<?php endforeach ?>
            		<?php else: ?>
            			<p>Nenhum upload efectuado</p>
            		<?php endif ?>
                	<h4>Últimas Visualizações</h4>
                	<small>Total de <?php echo $total_views["total_views"]; ?> visualização em <?php echo $total_views["total_files_viewed"]; ?> ficheiros.</small><br />
            		<?php if ($last_views): ?>
            		<?php foreach ($last_views as $key => $value): ?>
                			<a href="index.php?mod=files_view&id=<?php echo $value["id"]; ?>"><?php echo $value["title"]; ?></a>
                			| <?php echo date("Y-m-d", strtotime($value["date_accessed"])) ?>
                			<br />
            		<?php endforeach ?>
            		<?php else: ?>
            			<p>Nenhuma visualização efectuada</p>
            		<?php endif ?>
				</div>							 			
			</div>

            <div class="tab-pane list-group" id="user-logins">
                <div class="list-group-item">
                	<h4>Últimos Logins</h4>
                	<small>Total de <?php echo $num_logins; ?>.</small><br />
                	<?php foreach ($last_logins as $key => $value): ?>
                		<small><?php echo $value["ip_address"] ?> | <?php echo date( "Y-m-d H:s:I", strtotime($value["date_in"]) ) ?></small><br />
                	<?php endforeach ?>

				</div>							 			
			</div>							 			
		</div>							 			
	 </div>


		 <!-- utilizador info -->
		 <div class="col-lg-8 pull-left">
			 <!-- Adicionar utilizador -->
			 <div class="panel panel-default panel-block">
			 	<div class="list-group">
			 		<div class="list-group-item">
			 			<h4 class="section-title change_to_edit">Editar Utilizador</h4>
			 			<div class="col-lg-12">
				 			<form  action="index.php?mod=users_list&act=edit_user" method="post" class="form-horizontal" enctype="multipart/form-data">
				 				<input type="hidden" name="user_editing" value="<?php echo $_GET["id"]; ?>" />
				 				<div class="form-group">
				 					<label>Nome</label>
				 					<input type="text" name="name" value="<?php echo $user_val["name"] ?>" required="required" class="form-control" placholder="" />
				 				</div>
				 				<div class="form-group">
				 					<label>Email</label>
				 					<input type="email" name="email" value="<?php echo $user_val["email"] ?>" required="required" class="form-control" placholder="" />
				 				</div>
	                    
		                    
		                    	<div class="form-group">
				 					<div class="control-label"><label>Permissão de visualização em</label></div>
				 					<select placeholder="Seleccione uma pasta" name="user_view_permissions[]" class="form-control select2 populate" multiple="multiple">
	                        			<?php foreach ($folders as $key => $folder): ?>
	                        				<option <?php echo ( in_array($folder->id, $folders_view_permissions) )?' selected="selected"':'' ?> value="<?php echo $folder->id ?>"><?php echo $folder->name; ?></option>
	                        			<?php endforeach ?>
                        			</select>
				 				</div>

				 				<div class="form-group">
				 					<div class="control-label"><label>Permissão de upload em</label></div>
				 					<select placeholder="Seleccione uma pasta" name="user_upload_permissions[]" class="form-control select2 populate" multiple="multiple">
	                        			<?php foreach ($folders as $key => $folder): ?>
	                        				<option <?php echo ( in_array($folder->id, $folders_upload_permissions) )?' selected="selected"':'' ?> value="<?php echo $folder->id ?>"><?php echo $folder->name; ?></option>
	                        			<?php endforeach ?>
                        			</select>
				 				</div>
		                
				 				<div class="form-group">
				 					<div class="control-label"><label>Activo</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="is_active" value="1" <?php echo (!isset($user_val["is_active"]) || $user_val["is_active"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="is_active" value="0" <?php echo (isset($user_val["is_active"]) && $user_val["is_active"] != 1 )?'checked="checked"':''; ?> />
				 						Não
				 					</label>
				 				</div>
				 				<div class="form-group">
				 					<div class="control-label"><label>Notificações via email?</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="receive_email" value="1" <?php echo (!isset($user_val["receive_email"]) || $user_val["receive_email"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="receive_email" value="0" <?php echo (isset($user_val["receive_email"]) && $user_val["receive_email"] != 1 )?'checked="checked"':''; ?> />
				 						Não
				 					</label>
				 				</div>
				 				<?php if ($this_user["level"] < 3): ?>
				 				<div class="form-group">
				 					<div class="control-label"><label>Nível:</label></div>
				 					<?php if ($this_user["level"] == 1 ): ?>
					 					<label class="checkbox">
					 						<input type="radio" name="level" value="1" <?php echo (isset($_POST["level"]) && $_POST["level"] == 1 )?'checked="checked"':''; ?> />
					 						Administrador ( com voto de qualidade )
					 					</label>
				 					<?php endif ?>
				 					<label class="checkbox">
				 						<input type="radio" name="level" value="2" <?php echo (!isset($_POST["level"]) || $_POST["level"] == 2 )?'checked="checked"':''; ?> />
				 						Utilizador ( com voto normal )
				 					</label>
				 					<label class="checkbox">
				 						<input type="radio" name="level" value="3" <?php echo (!isset($_POST["level"]) || $_POST["level"] == 3 )?'checked="checked"':''; ?> />
				 						Gestor de conteúdo ( sem voto )
				 					</label>
				 				</div>
				 				<?php else: ?>
				 					<input type="hidden" name="level" value="3" />
				 				<?php endif ?>
				 				<div class="form-group">
				 					<div class="control-label"><label>Pode criar / editar pastas?</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="can_create_folders" value="1" <?php echo (!isset($user_val["can_create_folders"]) || $user_val["can_create_folders"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="can_create_folders" value="0" <?php echo (isset($user_val["can_create_folders"]) && $user_val["can_create_folders"] != 1 )?'checked="checked"':''; ?> />
				 						Não
				 					</label>
				 				</div>
				 				<div class="form-group">
				 					<div class="control-label"><label>Pode criar / editar tags?</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="can_create_tags" value="1" <?php echo (!isset($user_val["can_create_tags"]) || $user_val["can_create_tags"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="can_create_tags" value="0" <?php echo (isset($user_val["can_create_tags"]) && $user_val["can_create_tags"] != 1 )?'checked="checked"':''; ?> />
				 						Não
				 					</label>
				 				</div>
				 				<div class="form-group">
				 					<div class="control-label"><label>Pode apagar pastas / ficheiros ?</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="can_delete" value="1" <?php echo (!isset($user_val["can_delete"]) || $user_val["can_delete"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="can_delete" value="0" <?php echo (isset($user_val["can_delete"]) && $user_val["can_delete"] != 1 )?'checked="checked"':''; ?> />
				 						Não
				 					</label>
				 				</div>

				 				<div class="form-group">
				 					<label>Assinatura</label><br />
				 					<small>Deixe vazio para manter <a target="_blank" href="<?php echo base_url("client_files/".$user_val["assinatura"]); ?>">esta assinatura</a>.</small>
				 					<input type="file" name="assinatura" required="required" class="form-control" />
				 				</div>


				 				<div class="form-actions">
				 					<button  type="submit" name="adicionar_utilizador" class="btn btn-primary change_to_edit">Editar Utilizador</button>
				 				</div>
				 			</form>
				 			<div class="clearfix"></div>
			 			</div>

			 			<div class="clearfix"></div>
			 		</div>
			 	</div>
			 </div>
		 </div>





</div>


<!-- Modal -->
<div class="modal hide" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Por favor confirme</h4>
      </div>
      <div class="modal-body">
        Tem a certeza que pretende prosseguir?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="confirm" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
