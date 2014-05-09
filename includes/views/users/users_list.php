<?php 
$users_c = new Users();

$users_l = $users_c->get_all_users();

$this_user = $users_c->get_user_by_id( $_SESSION["user_bo"] );

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
		                Aqui poderá gerir todos os utilizadores
		            </small>
		        </h1>
		    </div>
		    <!-- div class="pull-right panel-title-block-actions">
		    	<a href="#adicionar-tag">
			    	<i class="icon-plus"></i>
			    	<span>Adicionar utilizador</span>
		    	</a>
		    </div -->
		</div>
	</div>
		<div class="col-lg-8 row">
		 <div class="panel panel-default panel-block">
		 	<div class="list-group">
		 		<div class="list-group-item">
		 			<h4 class="section-title">Utilizadores</h4>
		 			

					<div class="row">
						<!-- Datatable offline -->
						<table class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th>#</th>
									<th>Username</th>
									<th>Email</th>
									<th>Nível</th>
									<th>Acções</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($users_l as $key => $value): ?>
									<tr>
										<td><?php echo $value["id"]; ?></td>
										<td><?php echo $value["name"]; ?></td>
										<td><?php echo $value["email"]; ?></td>
										<td><?php echo ($value["is_admin"]?'Administrador':'Utilizador'); ?></td>
										<td>
											<?php if ($value["is_active"]): ?>
												<a href="index.php?mod=users_list&act=toggle_active&id=<?php echo $value["id"]; ?>" class="btn btn-xs btn-success">Inactivar</a>
											<?php else: ?>
												<a href="index.php?mod=users_list&act=toggle_active&id=<?php echo $value["id"]; ?>" class="btn btn-xs btn-danger">Activar</a>
											<?php endif ?>
											<a href="index.php?mod=users_view&id=<?php echo $value["id"]; ?>" class="btn btn-xs btn-primary">Ver</a>
											<?php if ( !$value["is_admin"] && $this_user["id"] != $value["id"] ): ?>
												<a href="index.php?mod=users_list&act=delete_user&id=<?php echo $value["id"]; ?>" class="btn btn-xs btn-danger">Eliminar</a>
											<?php endif ?>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>


					</div>

		 		</div>
		 	</div>
		 	</div>
		 </div>


		 <!-- Adicionar utilizador -->
		 <div class="col-lg-4 pull-right">
			 <!-- Adicionar utilizador -->
			 <div class="panel panel-default panel-block">
			 	<div class="list-group">
			 		<div class="list-group-item">
			 			<h4 class="section-title change_to_edit">Adicionar Utilizador</h4>
			 			<div class="col-lg-12">
				 			<form  action="index.php?mod=users_list&act=add_user" method="post" class="form-horizontal" enctype="multipart/form-data">
				 				<div class="form-group">
				 					<label>Nome</label>
				 					<input type="text" name="name" value="<?php echo $_POST["name"] ?>" required="required" class="form-control" placholder="" />
				 				</div>
				 				<div class="form-group">
				 					<label>Email</label>
				 					<input type="email" name="email" value="<?php echo $_POST["email"] ?>" required="required" class="form-control" placholder="" />
				 				</div>
				 				<div class="form-group">
				 					<label>Password</label>
				 					<input type="password" name="password" required="required" class="form-control" placholder="" />
				 				</div>
				 				<div class="form-group">
				 					<label>Confirme a password</label>
				 					<input type="password" name="password2" required="required" class="form-control" placholder="" />
				 				</div>
				 				<div class="form-group">
				 					<div class="control-label"><label>Activo</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="is_active" value="1" <?php echo (!isset($_POST["is_active"]) || $_POST["is_active"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="is_active" value="0" <?php echo (isset($_POST["is_active"]) && $_POST["is_active"] != 1 )?'checked="checked"':''; ?> />
				 						Não
				 					</label>
				 				</div>
				 				<div class="form-group">
				 					<div class="control-label"><label>Notificações via email?</label></div>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="receive_email" value="1" <?php echo (!isset($_POST["receive_email"]) || $_POST["receive_email"] == 1 )?'checked="checked"':''; ?> />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="receive_email" value="0" <?php echo (isset($_POST["receive_email"]) && $_POST["receive_email"] != 1 )?'checked="checked"':''; ?> />
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
				 					<label>Assinatura</label>
				 					<input type="file" name="assinatura" required="required" class="form-control" />
				 				</div>
				 				
				 				<div class="form-actions">
				 					<button  type="submit" name="adicionar_utilizador" class="btn btn-primary change_to_edit">Adicionar Utilizador</button>
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
