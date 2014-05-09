<?php 
$ac = new Access_control;
 ?>
<div class="wrapper scrollable extended" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="index.php"><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a data-toggle="dropdown" href="#">Controlo de Acessos</a>
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
		            <span>Virtual Data Room - Controlo de Acessos</span>
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
		 			<h4 class="section-title">Logins </h4>
		 			

		            <table class="table table-bordered table-striped" id="access_control">
		                <thead>
		                    <tr>
		                        <!--th style="width:10px;"></th Isto é suposto ser multiple actions-->
		                        <th style="width:20px;">#</th>
		                        <th>Enderço de Ip</th>
		                        <th>Email</th>
		                        <th>Início</th>
		                        <th>Fim</th>
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
			 <div class="panel panel-default panel-block">
			 	<div class="list-group">
			 		<div class="list-group-item">
						<div id="ac-info-block">
							<p>Seleccione um ip para visualizar informação sobre a sessão.</p>
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
