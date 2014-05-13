<?php 
$c = new Clientes();

$cliente = $c->get_cliente( $_GET["clientid"] );

if ( $_GET["processid"] != 0 ) {	//Temos processo
	$processo = $c->get_processo( $_GET["processid"] );
}
require_once( base_path( "includes/modules/users/users.mod.php" ) );

$u = new Users();
$this_user = $u->get_user_by_id( $_SESSION["user_bo"] ); //Futuro gráfico de comentários


 ?>

<div class="form-horizontal">
 <h4>Cliente</h4> <a href="index.php?mod=process&act=add&id=<?php echo $cliente["id"]; ?>">Adicionar novo processo a este cliente</a>
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
 
 <h4>Processo</h4>
 <div class="col-lg-12">

 	<div class="col-lg-6">
 		<div><strong>CCC nº:</strong> <?php echo $processo["ccc_num"]; ?></div>
 		<div><strong>#:</strong> <?php echo $processo["id"]; ?></div>
 		<div><strong>Prazo:</strong> <?php echo tools::time_for( $processo["prazo"] ); ?></div>
 	</div>

 	<div class="col-lg-6"> 
 		<div><a href="index.php?mod=process&amp;act=view&amp;process_id=<?php echo $processo["id"]; ?>"><i class="icon-file-text"></i> Ver processo</a></div>
 		<?php if ($processo["avaliacao"] == 0): ?>
 			<div><a href="index.php?mod=process&act=add&client_id=<?php echo $processo["id"]; ?>"><i class="icon-edit"></i> Editar processo</a></div>
 		<?php endif ?>
	</div>
 </div>

 <div class="clearfix"></div>
 
 <div class="panel gallery-uploader active">
 <h4>Outros Ficheiros</h4>
 <?php if ( $processo["avaliacao"] == 0 ): ?>
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
	                <form action="<?php echo base_url("index.php?mod=ajax&act=ajax_image_upload&folder_id=" . $data->id) ?>" class="dropzone" id="imageGalleryDropzone">
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

            <table class="table table-bordered table-striped">
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
		 			<?php foreach ($$processo["documents"] as $key => $value): ?>
			 			<tr>
			 				<td><?php echo $value["id"]; ?></td>
			 				<td><?php echo $value["nome"]; ?></td>
			 				<td><?php echo tools::display_date( $value["date_in"] ); ?></td>
			 				<td>
			 					<a href="#">Ver</a>
			 					<a href="#">Download</a>
			 				</td>
			 			</tr>
		 			<?php endforeach ?>
		 		</tbody>
            </table>
        </div>
    </div>


 </div>


 <div class="clearfix"></div>
 <h4>Estado</h4>
 <?php 
switch ($processo["avaliacao"]) {
	case 0:	//Ainda não está em avaliação
		?>
		<p>Este processo ainda não submetido para avaliação. <a href="index.php?mod=clientes_list&act=avaliar&id=<?php echo $processo["id"] ?>" class="btn btn-primary pull-right">Submeter para avaliação</a> </p>
		<?php
		break;
	case 1:	//Em avaliação
		require( base_path( "includes/modules/workflow/workflow.mod.php" ) );
		$w = new Workflow();

		?>
		<p>Este processo encontra-se em avaliação.</p>
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
				echo '<span class="btn btn-'.( ($voto["level"] == 1)?'s':'xs' ).' not-bold btn-'.$response.'">'.$voto["username"].'</span> ';
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
	case 2:	//Avaliado
		?>
		<?php if ( $processo["resultado"] ==  1): ?>
			<p class="alert alert-success padding-10">Processo Aprovado</p>
		<?php else: ?>
			<p class="alert alert-danger padding-10">Processo Rejeitado</p>
		<?php endif ?>
		<?php
		break;
}
  ?>

 <div class="clearfix"></div>
</div>

<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/image-gallery-uploader.js") ?>"></script>
<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/dropzone.min.js") ?>"></script>
<script>


vdr.formComponents.select2();


</script>