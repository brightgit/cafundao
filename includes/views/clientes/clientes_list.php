<?php

//criação das pastas principais na sidebar
$c = new Clientes();

$clientes = $c->get_processos_and_clients();

require_once( base_path( "includes/modules/users/users.mod.php" ) );
$u = new Users();

$this_user = $u->get_user_by_id( $_SESSION["user_bo"] );

?>

<section class="sidebar extended" style="max-height: none; opacity: 1;">
	<div class="sidebar-handle">
		<i class="icon-ellipsis-horizontal"></i>
		<i class="icon-ellipsis-vertical"></i>
	</div>

	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="clearfix">
				<!--img src="<?php echo base_url("images/logos/bright-logo.png") ?>" alt=""-->
				<h5>
					<span class="title">
						<?php echo $this->settings->display("client_name") ?>
					</span>
					<span class="subtitle">
						Powered By Bright & Digidoc
					</span>
				</h5>
			</div>
		</div>
		<div class="panel-body">
			<form action="" method="get" class="form-horitontal">
				<input type="hidden" name="mod" value="catalogo_list" />

				<div class="title">
					<i class="icon-folder-open"></i>
					<span>
						Processos
					</span>

					<?php if ( $this_user["p_upload"] ): ?>
						<a href="index.php?mod=process&act=add"  class="add" title="Adicionar Cliente">
							<i class="icon-plus-sign"></i>
						</a>
					<?php endif ?>

				</div>


				<div class="tab-content panel-default panel-block">
					<div class="jstree tab-pane list-group active tree-body">
						<div class="list-group-item scrollable jstree-vdr">
							<ul>

							<?php foreach ($clientes as $cliente): ?>
								<?php if ( count($cliente["processes"]) > 1 ) : ?>
									<li data-clientid="<?php echo $cliente["id"]; ?>" data-processid="0"><a href="#"><?php echo $cliente["nome"]; ?></a>
										<ul>
										<?php foreach ($cliente["processes"] as $key => $value): ?>
											<?php
											if ( $value["analise_risco"] == 1 ) {
												$class="analise_risco";
											}elseif( $value["avaliacao"] == 0 ){
												$class = "incompleto";
											}elseif( $value["avaliacao"] == 1 ) {
												$class = "para_avaliacao";
											}elseif( $value["avaliacao"] == 2 && $value["resultado"] == 1 ) {
												$class="aprovado";
											}elseif( $value["avaliacao"] == 2 && $value["resultado"] == 0 ) {
												$class="reprovado";
											}
											?>
											<li class="<?php echo $class; ?>" data-clientid="<?php echo $cliente["id"]; ?>" data-processid="<?php echo $value["id"]; ?>"><a href="#"><?php echo $value["ccc_num"] ?></a></li>
										<?php endforeach ?>
										</ul>
									</li>
								<?php else: ?>
									<?php
										if ( $cliente["processes"][0]["analise_risco"] == 1 ) {
											$class="analise_risco";
										}elseif( $cliente["processes"][0]["avaliacao"] == 0 ){
											$class = "incompleto";
										}elseif( $cliente["processes"][0]["avaliacao"] == 1 ) {
											$class = "para_avaliacao";
										}elseif( $cliente["processes"][0]["avaliacao"] == 2 && $cliente["processes"][0]["resultado"] == 1 ) {
											$class="aprovado";
										}elseif( $cliente["processes"][0]["avaliacao"] == 2 && $cliente["processes"][0]["resultado"] == 0 ) {
											$class="reprovado";
										}
									?>

									<li class="<?php echo $class; ?>" data-clientid="<?php echo $cliente["id"]; ?>" data-processid="<?php echo $cliente["processes"][0]["id"]; ?>"><a href="#"><?php echo $cliente["processes"][0]["ccc_num"]; ?> - <?php echo $cliente["nome"]; ?></a></li>
								<?php endif ?>
							<?php endforeach ?>
					</ul>
				</div>
			</div>
			
		</div>
	</form>
</div>
</div>
</section>

<div class="wrapper retracted scrollable" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
		<li><a href="."><i class="icon-home"></i></a></li>
		<li class="active">
			<span>Processos</span>
		</li>
	</ol>


	<div class="panel panel-default panel-block panel-title-block">
		<div class="panel-heading">
			<div>
				<i class="icon-files-alt"></i>
				<h1>
					<span><?php echo $this->settings->display("client_name") ?> - Processos</span>
					<small>
						A listar processos
					</small>
				</h1>
			</div>
			<div class="pull-right col-lg-4">
				<form action="index.php" method="get" >
					<input type="hidden" name="mod" value="pesquisa_avancada" />
					<div class="input-group">
					  <input type="text" name="processo" class="form-control" placeholder="" required="required">
					  <span class="input-group-btn">
	       				<button class="btn btn-default" style="background-color:#3699d2;"  type="submit">Pesquisa Global</button>
				  	  </span>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default panel-block">
				<div class="list-group" id="ajax-content">
					<p>Seleccione um cliente ou processo para visualizar o estado.</p>
					<!-- content will be loaded here -->
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Add Folder -->
<div class="modal fade" id="adicionar_pasta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="index.php?mod=files&act=add_folder" method="post" class="form-horizontal">
		<!-- Isto tem que ser alterado sempre passo por uma pasta -->
		<input type="hidden" name="parent_folder" id="parent_folder" value="0" />
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Adicionar nova pasta em: <span class="put_name_here">Raíz</span></h4>
	      </div>
	      <div class="modal-body">
	        <div class="form-group">
	        	<label for="nova_pasta_nome" class="col-lg-4">Nome da nova pasta</label>
	        	<div class="col-lg-8">
	        		<input id="nova_pasta_nome" class="form-control" name="nova_pasta_nome" placholder="Insira um nome" required="required" />
	        	</div>
	        </div>
	        <!--  -->
	        <div class="form-group client_id_block">
	        	<label for="client_id" class="col-lg-4">Id do cliente</label>
	        	<div class="col-lg-8">
	        		<input id="client_id" class="form-control" name="client_id" placholder="" />
	        	</div>
	        </div>
	        <!-- Galeria desactivada para esta aplicação -->
	        <input type="hidden" name="nova_pasta_tipo" value="0" />
	        <!-- div class="form-group">
	        	<label for="nova_pasta_tipo" class="col-lg-4">Tipo</label>
	        	<div  class="col-lg-8">
		        	<select name="nova_pasta_tipo" id="nova_pasta_tipo" required="required" class="form-control">
		        		<option value="">...</option>
		        		<option value="0">Ficheiros</option>
		        		<option value="1">Galeria</option>
		        	</select>
	        	</div>
	        </div -->
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-primary">Adicionar</button>
	      </div>
	    </div>
	  </div>
	</form>
</div>


<!-- Modal Delete Folder -->
<div class="modal fade" id="remover_pasta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="index.php?mod=files&amp;act=delete_folder" method="post" class="form-horizontal">
		<!-- Isto tem que ser alterado sempre passo por uma pasta -->
		<input type="hidden" name="folder_to_delete_id" id="folder_to_delete_id" value="0" />
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Remover pasta: <span class="put_name_here">Raíz</span></h4>
	      </div>
	      <div class="modal-body">
	      	<p>Tem a certeza que deseja remover esta pasta e todas as pastas / ficheiros associados.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-primary">Comfirmar</button>
	      </div>
	    </div>
	  </div>
	</form>
</div>




<script>
if ($.cookie('vdrSidebar') == 'retracted') {
	$('.sidebar').removeClass('extended').addClass('retracted');
	$('.wrapper').removeClass('retracted').addClass('extended');
}
if ($.cookie('vdrSidebar') == 'extended') {
	$('.wrapper').removeClass('extended').addClass('retracted');
	$('.sidebar').removeClass('retracted').addClass('extended');
}
</script>
