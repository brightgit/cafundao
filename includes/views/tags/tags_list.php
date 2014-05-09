<?php 
$tags_c = new Tags;
$tags = $tags_c->get_all_tags();
 ?>
<div class="wrapper scrollable extended" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="index.php"><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a data-toggle="dropdown" href="#">Tags</a>
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
		        <i class="icon-list-ul"></i>
		        <h1>
		            <span>Virtual Data Room - Tags</span>
		            <small>
		                Aqui poderá gerir todas as tags
		            </small>
		        </h1>
		    </div>
		    <div class="pull-right panel-title-block-actions">
		    	<a href="#adicionar-tag">
			    	<i class="icon-plus"></i>
			    	<span>Adicionar Tag</span>
		    	</a>
		    </div>
		</div>
	</div>
		 <div class="panel panel-default panel-block col-lg-8">
		 	<div class="list-group">
		 		<div class="list-group-item">
		 			<h4 class="section-title">Tags </h4>
		 			

		            <table class="table table-bordered table-striped" id="tags_sortable">
		                <thead>
		                    <tr>
		                        <th style="width:30px;">#</th>
		                        <th>Nome</th>
		                        <th>Pastas</th>
		                        <th>Data Criação</th>
		                        <th>Criada Por</th>
		                        <th style="width:130px;">Ac&ccedil;&otilde;es</th>
		                        <!-- th>Peso</th -->
		                    </tr>
		                </thead>
		                <tbody>
		                </tbody>
		            </table>
		 		</div>
		 	</div>
		 </div>


		 <!-- Tag info -->
		 <div class="col-lg-4 pull-right">
			 <div class="panel panel-default panel-block hide">
			 	<div class="list-group">
			 		<div class="list-group-item">
						<div id="tag-info-block">
							<p>Escolha uma tag para visualizar.</p>
						</div>
			 		</div>
			 	</div>
			 </div>

			 <!-- Adicionar tag -->
			 <div class="panel panel-default panel-block">
			 	<div class="list-group">
			 		<div class="list-group-item">
			 			<h4 class="section-title change_to_edit">Adicionar tag</h4>
			 			<div class="col-lg-12">
				 			<form  action="index.php?mod=tags_list&act=add_tag" method="post" class="form-horizontal">
				 				<input type="hidden" value="0" name="tag_id" id="input_tag_id" />
				 				<div class="form-group">
				 					<label>Nome da tag</label>
				 					<input type="text" id="input_tag" name="tag" required="required" class="form-control" placholder="" />
				 				</div>
				 				<div class="form-group">
				 					<label>Activo</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="active" value="1" checked="checked" />
				 						Sim
				 					</label>
				 					<label class="checkbox-inline">
				 						<input type="radio" name="active" value="0" />
				 						Não
				 					</label>
				 				</div>
				 				<div class="form-actions">
				 					<button  type="submit" name="adicionar_tag" class="btn btn-primary change_to_edit">Adicionar Tag</button>
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
<div class="modal fade" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
