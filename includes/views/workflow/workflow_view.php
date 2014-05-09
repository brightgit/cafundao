<?php 
$w = new Workflow();

$client_para_avaliacao = $w->get_all_evaluations();

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
	        <a data-toggle="dropdown" href="#">Workflow</a>
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
		            <span>Virtual Data Room - Workflow</span>
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

	<div class="row">
		<div class="col-lg-12">
			<div class="panel-group" id="workflow">
				<?php foreach ($client_para_avaliacao as $key => $value): ?>
					<form action="index.php?mod=workflow_view&act=actualizar_cliente" class="form-horizontal" method="post">
						<input type="hidden" value="<?php echo $value["cliente"]["id"]; ?>" name="client_id" />
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#inbox" href="#cliente_<?php echo $value["cliente"]["id"]; ?>"><?php echo $value["cliente"]["name"]; ?> <i>#<?php echo $value["cliente"]["client_id"] ?></i></a>
								</h3>
							</div>
							<div class="panel-collapse collapse <?php echo ($key==0)?'in':''; ?>" id="cliente_<?php echo $value["cliente"]["id"]; ?>">
								<div class="panel-body">
									<div class="form-group">
					 					<div class="control-label col-lg-12"><label>Método de decisão:</label></div>
					 					<label class="checkbox-inline">
					 						<input onchange="if( this.checked ) { $('#num_votos_normal').show(); $('#num_votos_admin').hide(); }" type="radio" name="metodo_voto" value="normal" <?php echo (!isset($value["cliente"]["metodo_voto"]) || $value["cliente"]["metodo_voto"] == "normal" )?'checked="checked"':''; ?> />
					 						Núm. votos indiscriminados
					 					</label>
					 					<label class="checkbox-inline">
					 						<input onchange=" if( this.checked ) { $('#num_votos_normal').hide(); $('#num_votos_admin').hide(); }" type="radio" name="metodo_voto" value="basta_um_rejeitar" <?php echo (!isset($value["cliente"]["metodo_voto"]) || $value["cliente"]["metodo_voto"] == "basta_um_rejeitar" )?'checked="checked"':''; ?> />
					 						Todos os votos de qualidade aceitarem
					 					</label>
					 					<label class="checkbox-inline">
					 						<input onchange="if( this.checked ) { $('#num_votos_normal').hide(); $('#num_votos_admin').hide(); }" type="radio" name="metodo_voto" value="basta_um_aceitar" <?php echo (!isset($value["cliente"]["metodo_voto"]) || $value["cliente"]["metodo_voto"] == "basta_um_aceitar" )?'checked="checked"':''; ?> />
					 						Basta um voto de qualidade aceitar
					 					</label>
					 					<label class="checkbox-inline">
					 						<input onchange=" if( this.checked ) { $('#num_votos_admin').show(); $('#num_votos_normal').hide(); }" type="radio" name="metodo_voto" value="maioria_qualidade" <?php echo (!isset($value["cliente"]["metodo_voto"]) || $value["cliente"]["metodo_voto"] == "maioria_qualidade" )?'checked="checked"':''; ?> />
					 						Núm. votos de qualidade
					 					</label>

									</div>
									<div class="form-group" <?php echo ( $value["cliente"]["metodo_voto"] == 'maioria_qualidade' )?'':' style="display:none;"' ?> id="num_votos_admin">
										<label for="num_min_votos_qualidade" class=" col-lg-2">Número de votos de qualidade</label>
										<div class="col-lg-2">
											<input required="required" type="number" name="num_min_votos_qualidade" class="form-control" value="<?php echo (isset($value["cliente"]["num_min_votos_qualidade"]))?$value["cliente"]["num_min_votos_qualidade"]:1; ?>" min="0" max="<?php echo $value["num_votos"]["admins"]; ?>" />
										</div>
									</div>
									<div class="form-group" <?php echo ( $value["cliente"]["metodo_voto"] == 'normal' )?'':' style="display:none;"' ?> id="num_votos_normal">
										<label class="col-lg-2" for="num_min_votos">Número de votos indiscriminados</label>
										<div class="col-lg-2">
											<input required="required" type="number" class="form-control" id="num_min_votos" name="num_min_votos"  value="<?php echo (isset($value["cliente"]["num_min_votos"]))?$value["cliente"]["num_min_votos"]:1; ?>" min="0" max="<?php echo $value["num_votos"]["normal"] + $value["num_votos"]["admins"]; ?>" />
										</div>
									</div>
									<div class="form-controls">
										<input type="submit" name="change_cliente_voting" class="btn btn-primary" value="Alterar" />
									</div>
									<hr style="margin-bottom:0;" />
									<div class="">
					 					<h3>Votos
					 						<span class="pull-right">
							 					<span class="btn btn-xs not-bold btn-default">Ainda não votou</span>
							 					<span class="btn btn-xs not-bold btn-success">Aceitou</span>
							 					<span class="btn btn-xs not-bold btn-danger">Rejeitou</span>
							 					<strong class="btn btn-s btn-default not-bold">Voto de qualidade</strong>
											</span>
										</h3>

										<?php 
										unset($votos);
										unset($seu_voto);
										$votos = $w->get_client_votos( $value["cliente"]["id"] );
										foreach ($votos as $key => $voto) {
											if ( $voto["user_id"] == $this_user["id"] ) {
												$seu_voto = $voto;
											}
											switch ($voto["vote_casted"]) {
												case 1:
													$response = "success";
													break;
												case "0":
													$response = "danger";
													break;
												default:
													$response = "default";
													break;
											}
											echo '<span class="btn btn-'.( ($voto["level"] == 1)?'s':'xs' ).' not-bold btn-'.$response.'">'.$voto["username"].'</span> ';
										}
										 ?>
									
										 <hr />
										 <?php 
										 if (isset($seu_voto)) {
											 switch ($seu_voto["vote_casted"]) {
											 	case '1':
											 		echo '<h3>Aceitou este cliente, deseja alterar: <a class="btn btn-danger pull-right" href="index.php?mod=workflow_view&act=rejeitar&id='.$seu_voto["category_id"].'">Rejeitar</a></h3>';
											 		break;
											 	case '0':
											 		echo '<h3>Rejeitou este cliente, deseja alterar: <a class="btn btn-success pull-right" href="index.php?mod=workflow_view&act=aceitar&id='.$seu_voto["category_id"].'">Aceitar</a></h3>';
											 		break;
											 	default:
											 		echo '<h3>Ainda não votou: <a class="btn btn-success pull-right" href="index.php?mod=workflow_view&act=aceitar&id='.$seu_voto["category_id"].'">Aceitar</a><a class="btn btn-danger pull-right" href="index.php?mod=workflow_view&act=rejeitar&id='.$seu_voto["category_id"].'">Rejeitar</a></h3>';
											 		break;
											 }
										 }
										  ?>

									</div>
								</div>
							</div>
						</div>
					</form>
				<?php endforeach ?>






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
