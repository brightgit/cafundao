<?php 
$c = new Clientes();

$cliente = $c->get_cliente( $_GET["clientid"] );

if ( $_GET["processid"] != 0 ) {	//Temos processo
	$processo = $c->get_processo( $_GET["processid"] );
	$query = "insert into access_processes set process_id = '".$processo["id"]."', session_id = '".$_SESSION["session_db_id"]."' ";
	mysql_query($query) or die_sql( $query );
}


require_once( base_path( "includes/modules/users/users.mod.php" ) );

$u = new Users();

$this_user = $u->get_user_by_id( $_SESSION["user_bo"] ); //Futuro gráfico de comentários



 ?>

<div class="form-horizontal">

 <h4>Cliente</h4> 
<?php if ($this_user["p_upload"]): ?>
 <a href="index.php?mod=process&act=add&id=<?php echo $cliente["id"]; ?>">Adicionar novo processo a este cliente</a>
<?php endif ?>

 <div class="col-lg-12">
 	<div class="col-lg-6">
 		<div><strong>Nome:</strong> <?php echo $cliente["nome"]; ?></div>
 		<div><strong>Núm. Cliente:</strong> <?php echo $cliente["numero_cliente"]; ?></div>
 		<div><strong>Balcão:</strong> <?php echo $cliente["balcao"]; ?></div>
 	</div>
 	<div class="col-lg-6">
 		<div><strong>Tlm.:</strong> <?php echo $cliente["telemovel"]; ?></div>
 		<div><strong>NIPC.:</strong> <?php echo $cliente["nipc"]; ?></div>
 		<div><strong>Criado em:</strong> <?php echo tools::display_date( $cliente["date_in"] ); ?></div>
 	</div>
 </div>
 <div class="clearfix"></div>
 

 <?php if (isset($processo)): ?>
 	
 <h4>Processo</h4>
 <div class="col-lg-12">

 	<div class="col-lg-6">
 		<div><strong>CCC nº:</strong> <?php echo $processo["ccc_num"]; ?></div>
 		<div><strong>Id Aplicação:</strong> <?php echo $processo["id"]; ?></div>
 		<div><strong>Prazo:</strong> <?php echo tools::display_date( $processo["prazo"] ); ?></div>
 	</div>

 	<div class="col-lg-6"> 
 		<!-- Emails para os quais é possível fazer upload -->
 		<div><a href="index.php?mod=process&act=view&process_id=<?php echo $processo["id"]; ?>"><i class="icon-file-text"></i> Ver processo</a></div>
 		<?php 
 		if ( $processo["avaliacao"] == 0 && $this_user["p_upload"] == 1 ):

	 		$emails = $c->get_emails_by_process( $processo["id"] );
	 		if ($emails) {
	 			echo '<span>Poderá enviar ficheiros directamente para:</span>';
	 			echo "<ul>";
	 			foreach ($emails as $key => $value) {
	 				echo "<li>".$value["email"]."</li>";
	 			}
	 			echo "</ul>";
	 		}
 		endif;
 		
 		 ?>
	</div>
 </div>

 <div class="clearfix"></div>
 
 <div class="panel gallery-uploader active">
 <h4>Outros Ficheiros</h4>
 <?php if ( $processo["avaliacao"] == 0 && $this_user["p_upload"] == 1 ): ?>
	 <a href="Javascript:void(0);" onclick="$('.dropzone-container').toggleClass('hide'); return false;" class="btn btn-link">
	    <i class="icon-plus-sign"></i>
	    <span>
	        Adicionar Ficheiros
	    </span>
	</a>
 <?php endif ?>
    <div class="list-group">
    	<?php if ($processo["avaliacao"] == 0 ): ?>
	        <div class="list-group-item dropzone-container hide">
	            <div class="form-group">
	                <form action="<?php echo base_url("index.php?mod=ajax&act=ajax_image_upload&folder_id=" . $processo["id"]) ?>" class="dropzone" id="imageGalleryDropzone">
	                    <div class="dz-message clearfix">
	                        <i class="icon-picture"></i>
	                        <span>Arraste os ficheiros para aqui ou clique para seleccionar</span>
	                        <div class="hover">
	                            <i class="icon-download"></i>
	                            <span>ARRASTAR FICHEIROS PARA AQUI</span>
	                        </div>
	                    </div>
	                </form>
	            </div>
	        </div>
    	<?php endif ?>
        <div class="list-group-item preview-container">
            <div class="gallery-container files-container">
            </div>

            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th style="width:30px;">#</th>
                        <th>Nome</th>
                        <th>Data</th>
                        <th style="width:130px;">Ac&ccedil;&otilde;es</th>
                        <!-- th>Peso</th -->
                    </tr>
                </thead>
		 		<tbody>
		 			<?php foreach ($processo["documents"] as $key => $value): ?>
			 			<tr>
			 				<td><?php echo $value["id"]; ?></td>
			 				<td><?php echo $value["title"]; ?></td>
			 				<td><?php echo tools::display_date( $value["date_in"] ); ?></td>
			 				<td class="align-right">
			 					<?php 
			 						$able_to_view = array("png", "jpg", "jpeg", "gif"); 
			 						$file_extension = tools::get_file_extension($value["title"]);

			 						
		 						?>
			 					<?php if (in_array($file_extension, $able_to_view)): ?>
			 					<a class="btn btn-xs btn-primary fancythis fancybox.iframe" href="<?php echo base_url("get_file.php?full=1&id=" . $value["id"]) ?>">Ver</a>	
			 					<?php endif ?>			 					
			 					<a class="btn btn-xs btn-success" href="<?php echo base_url("get_file.php?act=d&id=" . $value["id"]) ?>">Download</a>
			 				</td>
			 			</tr>
		 			<?php endforeach ?>
		 		</tbody>
            </table>
        </div>
    </div>


 </div>


 <div class="clearfix"></div>
 <?php 


//Vou colocar aqui a valição do switch para melhor visibilidade
 if ( $processo["avaliacao"] == 0 ) {	//Processso ainda sem avaliação
 	if ($this_user["p_upload"]) {
 		$var = 0.1;	//Se for 0 há conflito
 	}else{
 		$var = FALSE;
 	}
 }elseif( $processo["avaliacao"] == 1 ) {	//Processo em avaliação

 	if ( $processo["analise_risco"] == 1 ) {	//U utilizador não pode votar
 		if ( $this_user["p_analise_risco"] == 1 ) {
 			$var = 1.2;	//Analisar o risco
 		}else{
 			$var = 1.3;	//Está em análise mas não está analisado.
 		}
 	}elseif( !($processo["analise_risco"]) ){	//O utilizador pode votar e não é necessário análise de risco
 		$var = 1.1;	//Votar
 	}elseif( !$this_user["p_vote"] && !$this_user["p_quality_vote"] ){	//Está em análise de risco e o utilizador pode analisar
 		$var = false;
 	}else{
 		$var = false;
 	}

 }elseif( $processo["avaliacao"] == 2 && ($this_user["p_vote"] || $this_user["p_quality_vote"]) ){	//Já terminou a votação e o utilizador pode votar
 	$var = 2;	//Resultado da votação
 }else{
 	$var = false;
 }

switch ($var) {
	case 0.1:	//Ainda não está em avaliação
		?>

		<p>Este processo ainda não foi submetido para avaliação. <a href="index.php?mod=clientes_list&act=avaliar&id=<?php echo $processo["id"] ?>" class="btn btn-primary pull-right">Submeter para avaliação</a> </p>

		<?php
		break;
	case 1.1:	//Em avaliação
		require( base_path( "includes/modules/workflow/workflow.mod.php" ) );
		$w = new Workflow();

		?>

		<?php if ( !empty( $processo["analise_risco_user"] ) ): ?>
			<?php
			$analise_user = $u->get_user_by_id( $processo["analise_risco_user"] );
			?>
			<h4>Análise de risco:</h4>
			<p>Processo analisado por <strong><?php echo $analise_user["name"]; ?></strong> em: <strong><?php echo tools::display_date( $processo["data_analise_risco"] ); ?></strong></p>
			<div class="alert alert-info text-left">
				<?php echo str_replace("\n", "<br />", $processo["analise_risco_texto"]); ?>
			</div>
		<?php endif ?>

 		<h4>Estado</h4>
		<p>Este processo encontra-se em avaliação.</p>
		<hr style="margin-bottom:0;" />
		<div class="">
				<h3>Votos
					<span class="pull-right">
 					<span class="btn btn-xs not-bold btn-default">Ainda não votou</span>
 					<span class="btn btn-xs not-bold btn-success">Aceitou</span>
 					<span class="btn btn-xs not-bold btn-danger">Rejeitou</span>
 					<?php if ( $processo["metodo_voto"] != "normal" ): ?>
 						<strong class="btn btn-s btn-default not-bold">Voto de qualidade</strong>
 					<?php endif ?>
				</span>
			</h3>

			<?php 
			unset($votos);
			unset($seu_voto);
			$votos = $w->get_client_votos( $processo["id"] );
			//var_dump($votos);
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
				echo '<span class="btn btn-'.( ($voto["p_quality_vote"] == 1 && $processo["metodo_voto"] != "normal")?'s':'xs' ).' not-bold btn-'.$response.'">'.$voto["username"].'</span> ';
			}
			 ?>
		
			 <hr />
			 <?php 
			 if (isset($seu_voto)) {
				 switch ($seu_voto["vote_casted"]) {
				 	case '1':
				 		echo '<h3>Aceitou este cliente, deseja alterar: <a class="btn btn-danger pull-right" href="index.php?mod=clientes_list&act=rejeitar&id='.$seu_voto["process_id"].'">Rejeitar</a></h3>';
				 		break;
				 	case '0':
				 		echo '<h3>Rejeitou este cliente, deseja alterar: <a class="btn btn-success pull-right" href="index.php?mod=clientes_list&act=aceitar&id='.$seu_voto["process_id"].'">Aceitar</a></h3>';
				 		break;
				 	default:
				 		echo '<h3>Ainda não votou: <span class="pull-right"><a class="btn btn-success" href="index.php?mod=clientes_list&act=aceitar&id='.$seu_voto["process_id"].'">Aceitar</a> 
				 		<a class="btn btn-danger" href="index.php?mod=clientes_list&act=rejeitar&id='.$seu_voto["process_id"].'">Rejeitar</a></h3>';
				 		break;
				 }
			 }
			  ?>

		</div>

		<?php
		break;
	case 1.2:	//Avaliação de risco
		?>
 		<h4>Análise de risco</h4>
 		<div class="col-lg-12">
 			<form class="form-horizontal" action="index.php?mod=clientes_list&act=analise_de_risco" method="post">
 				<input type="hidden" name="process_id" value="<?php echo $processo["id"] ?>" />
 				<div class="control-group">
 					<label>Por favor insira a sua análise:</label>
 					<div class="">
 						<textarea style="height:250px;" class="form-control auto-resize" name="analise_risco_texto"></textarea>
 					</div>
 				</div>
 				<hr />
 				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="submit_analise_risco" value="Submeter análise de risco">
				</div>
 			</form>
 		</div>
		<?php
		break;
	case 1.3:	//Em análise de risco mas não pode fazer análise
		?>
		<div class="alert alert-dismissable alert-info fade in">
			<span class="title"><i class="icon-info-sign"></i> Em Análise</span>
			Este processo está a ser processado pela análise de risco.
		</div>

		<?php
		break;
	case 2:	//Avaliado
		?>
		<?php if ( !empty( $processo["analise_risco_user"] ) ): ?>
			<?php
			$analise_user = $u->get_user_by_id( $processo["analise_risco_user"] );
			?>
			<h4>Análise de risco:</h4>
			<p>Processo analisado por <strong><?php echo $analise_user["name"]; ?></strong> em: <strong><?php echo tools::display_date( $processo["data_analise_risco"] ); ?></strong></p>
			<div class="alert alert-info text-left">
				<?php echo str_replace("\n", "<br />", $processo["analise_risco_texto"]); ?>
			</div>
		<?php endif ?>

 		<h4>Estado</h4>
		<?php if ( $processo["resultado"] ==  1): ?>
			<p class="alert alert-success padding-10">Processo Aprovado</p>
		<?php else: ?>
			<p class="alert alert-danger padding-10">Processo Rejeitado</p>
		<?php endif ?>
		<?php
		break;


	default:
		echo "<p>Este processo encontra-se em análise de risco</p>";
	break;
}
  ?>

 	<div class="clearfix"></div>
  <?php endif ?>
</div>

<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/image-gallery-uploader.js") ?>"></script>
<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/dropzone.min.js") ?>"></script>
<script>


vdr.formComponents.select2();


</script>