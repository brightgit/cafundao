<?php

//criação das pastas principais na sidebar
$files = new files;
//$folders = $files->clientes(); //retorna estilo $folder["name"]["file1"]
$clients = $files->get_clients();

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
						Pastas
					</span>
					<a href="#" class="hide add" data-toggle="modal" data-target="#remover_pasta" id="remove_folder_link" title="Remover Pasta">
						<i class="icon-remove-sign"></i>
					</a>
					<a href="#" data-toggle="modal" data-target="#adicionar_pasta" class="add" title="Adicionar Pasta">
						<i class="icon-plus-sign"></i>
					</a>

				</div>


				<div class="tab-content panel-default panel-block">
					<div id="categorias" class="jstree tab-pane list-group active tree-body">
						<div class="list-group-item scrollable jstree-vdr" id="vdr-tree">
							<ul>
								<?php foreach ($folders as $folder): ?>
								<li data-item-type="folder" data-act="folder_open" data-folder-type="<?php echo ($folder["values"]->type == "0") ? "files" : "images" ?>" data-id="<?php echo $folder["values"]->id ?>">
									<a href="#" title="<?php echo $folder["values"]->name ?>">
										<?php echo $folder["values"]->name ?>
									</a>
									<?php if(!empty($folder["children"])) $files->list_children($folder["children"]); ?>
								</li>
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
					  <input type="text" name="nome_ficheiro" class="form-control" placeholder="Nome do ficheiro" required="required">
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
					<p>Seleccione uma pasta para apresentar ficheiros</p>
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
	<form action="index.php?mod=files&act=delete_folder" method="post" class="form-horizontal">
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
