<?php 
$p = new Pesquisa();
 ?>
<div class="wrapper scrollable extended" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="index.php"><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a data-toggle="dropdown" href="#">Pesquisa Avançada</a>
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
		            <span>Virtual Data Room - Pesquisa Avançada</span>
		            <small>
		                Aqui poderá visualizar os últimos acesso à aplicação
		            </small>
		        </h1>
		    </div>
		</div>
	</div>
		 <div class="panel panel-default panel-block col-lg-8">
		 	<div class="list-group">
		 		<div class="list-group-item">
		 			<h4 class="section-title">Pesquisa Avançada </h4>
		 			

		            <table class="table table-bordered table-striped table-condensed" id="pesquisa_avancada">
		                <thead>
		                    <tr>
		                        <!--th style="width:10px;"></th Isto é suposto ser multiple actions-->
		                        <th style="width:20px;">Processo</th>
		                        <th>ID Cliente</th>
		                        <th>Cliente</th>
		                        <th>Balcão</th>
		                        <th>Estado</th>
		                        <th>Prazo</th>
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
			 <div class="panel panel-default panel-block">
			 	<div class="list-group">
			 		<div class="list-group-item">
						<div id="pesquisa-avancada-fake-form">
							<form onsubmit="vdr.tables.pesquisa_avancada_table.fnDraw(); $('#pesquisa_avanacada_button').addClass('disabled'); return false;" class="form-vertical">
								<h4>Filtro de Pesquisa</h4>
								<div class="control-group">
									<label >Processo</label>
									<input type="text" value="<?php echo $_GET["processo"]; ?>" onkeyup="$('#pesquisa_avanacada_button').removeClass('disabled');" class="form-control" name="processo" id="processo" />
								</div>
								<div class="control-group">
									<label >ID Cliente</label>
									<input type="text" value="<?php echo $_GET["id_cliente"]; ?>" onkeyup="$('#pesquisa_avanacada_button').removeClass('disabled');" class="form-control" name="id_cliente" id="id_cliente" />
								</div>
								<div class="control-group">
									<label >Cliente</label>
									<input type="text" value="<?php echo $_GET["cliente"]; ?>" onkeyup="$('#pesquisa_avanacada_button').removeClass('disabled');" class="form-control" name="cliente" id="cliente" />
								</div>
								<div class="control-group">
									<label >Balcão</label>
									<input type="text" value="<?php echo $_GET["balcao"]; ?>" onkeyup="$('#pesquisa_avanacada_button').removeClass('disabled');" class="form-control" name="balcao" id="balcao" />
								</div>
								<div class="control-group">
									<div class="control-label"><label>Estado</label></div>
									<label class="checkbox"> <input type="radio" name="estado" value="em_processamento" /> Não submetido para avaliação</label>
									<label class="checkbox"> <input type="radio" name="estado" value="avaliacao" /> Em Avaliação</label>
									<label class="checkbox"> <input type="radio" name="estado" value="aceite" /> Aprovado</label>
									<label class="checkbox"> <input type="radio" name="estado" value="reprovado" /> Reprovado</label>
								</div>
								<div class="control-group">
									<label>Prazo ( > que:)</label>
									<input type="text" onkeyup="$('#pesquisa_avanacada_button').removeClass('disabled');" class="datetimepicker-dat-range form-control" name="date_start" id="date_start" />
								</div>
								<div class="control-group">
									<label>Data ( < que: )</label>
									<input type="text" onkeyup="$('#pesquisa_avanacada_button').removeClass('disabled');" class="datetimepicker-dat-range form-control" name="date_end" id="date_end" />
								</div>
								<hr />
								<div class="form-actions">
									<button type="submit" id="pesquisa_avanacada_button" class="btn btn-primary disabled">Pesquisar</button>
								</div>
							</label>
						</div>
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
