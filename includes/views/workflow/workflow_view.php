<?php 
$w = new Workflow();

$client_para_avaliacao = $w->get_all_evaluations();
$processes = $w->get_all_processes();

require_once( base_path( "includes/modules/users/users.mod.php" ) );

$u = new Users();
$this_user = $u->get_user_by_id( $_SESSION["user_bo"] ); //Futuro gráfico de comentários


//var_dump($all_tags);

 ?>
<div class="wrapper scrollable extended" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="index.php"><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a data-toggle="dropdown" href="#">Método de decisão</a>
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
		        <i class="icon-inbox"></i>
		        <h1>
		            <span>Virtual Data Room - Método de decisão</span>
		            <small>
		                Aqui poderá processar todos os documentos recebidos por email.
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

	<?php 
	//echo '<pre>';
	//var_dump( $processes[0] );
	//echo '</pre>';
	 ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel-group" id="workflow">
					<form action="index.php?mod=clientes_list&act=change_voting_method" class="form-horizontal" method="post">
						<div class="panel panel-default panel-block preface">
							<div class="list-group">
								<div class="list-group-item">
									<?php if ($processes): ?>
										<h4 class="section-title">1. Alterar método de decisão para os processos:</h4>
									<?php foreach ($processes as $key => $value): ?>
										<div class="control-group">
											<label> <input type="checkbox" name="processo[]" value="<?php echo $value["id"] ?>" /> <?php echo $value["ccc_num"] ?></label>
											<div class="list-process-details">

											</div>
										</div>
									<?php endforeach ?>
									<div class="clearfix"></div>
									<h4 class="section-title">2. Novo método de decisão:</h4>
									<div class="">

										<div class="form-group">
						 					<label class="checkbox-inline">
						 						<input onchange="if( this.checked ) { $('#num_votos_normal').show(); $('#num_votos_admin').hide(); }" type="radio" name="metodo_voto" value="normal" <?php echo (!isset($processes[0]["metodo_voto"]) || $processes[0]["metodo_voto"] == "normal" )?'checked="checked"':''; ?> />
						 						Núm. votos indiscriminados
						 					</label>
						 					<label class="checkbox-inline">
						 						<input onchange=" if( this.checked ) { $('#num_votos_normal').hide(); $('#num_votos_admin').hide(); }" type="radio" name="metodo_voto" value="basta_um_rejeitar" <?php echo (!isset($processes[0]["metodo_voto"]) || $processes[0]["metodo_voto"] == "basta_um_rejeitar" )?'checked="checked"':''; ?> />
						 						Todos os votos de qualidade aceitarem
						 					</label>
						 					<label class="checkbox-inline">
						 						<input onchange="if( this.checked ) { $('#num_votos_normal').hide(); $('#num_votos_admin').hide(); }" type="radio" name="metodo_voto" value="basta_um_aceitar" <?php echo (!isset($processes[0]["metodo_voto"]) || $processes[0]["metodo_voto"] == "basta_um_aceitar" )?'checked="checked"':''; ?> />
						 						Basta um voto de qualidade aceitar
						 					</label>
						 					<label class="checkbox-inline">
						 						<input onchange=" if( this.checked ) { $('#num_votos_admin').show(); $('#num_votos_normal').hide(); }" type="radio" name="metodo_voto" value="maioria_qualidade" <?php echo (!isset($processes[0]["metodo_voto"]) || $processes[0]["metodo_voto"] == "maioria_qualidade" )?'checked="checked"':''; ?> />
						 						Núm. votos de qualidade
						 					</label>

										</div>
										<div class="form-group" <?php echo ( $processes[0]["metodo_voto"] == 'maioria_qualidade' )?'':' style="display:none;"' ?> id="num_votos_admin">
											<label for="num_min_votos_qualidade" class=" col-lg-2">Número de votos de qualidade</label>
											<div class="col-lg-2">
												<input required="required" type="number" name="num_min_votos_qualidade" class="form-control" value="<?php echo (isset($processes[0]["num_min_votos_qualidade"]))?$processes[0]["num_min_votos_qualidade"]:1; ?>" min="0" max="<?php echo $processes[0]["num_votos"]["admins"]; ?>" />
											</div>
										</div>
										<div class="form-group" <?php echo ( $processes[0]["metodo_voto"] == 'normal' )?'':' style="display:none;"' ?> id="num_votos_normal">
											<label class="col-lg-2" for="num_min_votos">Número de votos indiscriminados</label>
											<div class="col-lg-2">
												<input required="required" type="number" class="form-control" id="num_min_votos" name="num_min_votos"  value="<?php echo (isset($processes[0]["num_min_votos"]))?$processes[0]["num_min_votos"]:1; ?>" min="0" max="<?php echo $processes[0]["num_votos"]["normal"] + $processes[0]["num_votos"]["admins"]; ?>" />
											</div>
										</div>

									</div>
									<hr />
									<div class="">
										<input type="submit" class="btn btn-danger" value="Alterar método de decisão" />
										<small>Caso um processo já tenha votos tal processo poderá ser automaticamente aprovado / reprovado (sendo impossível voltar atrás na decisão).</small>
									</div>
								</div>
								<?php else: ?>
									<div class="alert alert-info">Nenhum processo em votação.</div>
								<?php endif ?>
							</div>
						</div>
					</form>






			</div>
		</div>
	</div>








</div>


<!-- Modal -->
<div class="modal" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Por favor confirme</h4>
      </div>
      <div class="modal-body">
        O ficheiro será apagado. Confirma?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="confirm" class="btn btn-primary">Apagar</button>
      </div>
    </div>
  </div>
</div>
