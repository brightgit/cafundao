<?php 
$inbox = new Inbox();

$emails = $inbox->get_unprocessed_emails();
$folders = $inbox->get_all_folder();
$all_tags = $inbox->get_all_tags();

//var_dump($all_tags);

 ?>
<div class="wrapper scrollable extended" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="index.php"><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a data-toggle="dropdown" href="#">Inbox</a>
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
		            <span>Virtual Data Room - Inbox</span>
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
		<div class="panel-group" id="inbox">

			<?php foreach ($emails as $key => $email): ?>
				<form action="index.php?mod=inbox_list&act=proccess_document" method="post">
				<input type="hidden" name="email_id" value="<?php echo $email["id"] ?>" />

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#inbox" href="#email_<?php echo $email["id"]; ?>"><?php echo $email["subject"]; ?></a>
						</h4>
					</div>
					<div class="panel-collapse collapse <?php echo ($key==0)?'in':''; ?>" id="email_<?php echo $email["id"]; ?>">
						<div class="panel-body">
							<div class="col-lg-4">	
								<h4>Ficheiros</h4>
								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<td>Ficheiro</td>
											<td>Nome a apresentar</td>
										</tr>
									</thead>
									<tbody>
										<?php
										$files = explode(",", $email["attachments"]);
										foreach ($files as $key => $value) {
											$file_u = explode(".", $value);
											$extension = $file_u[ count($file_u)-1 ];
											unset( $file_u[ count($file_u)-1 ] );
											$file_w_e = implode(".", $file_u);

											echo "<tr>";
												echo '<td class="tdcenter"><a target="_blank" href="get_unprocessed_file.php?name='.$value.'">'.$value.'</a></td>';
												echo '<td>
														<div class="input-group">
															<input type="text" required="required" name="new_file_name[]" class="form-control input-xs" value="'.$file_w_e.'" />
															<input type="hidden" name="new_file_extension[]" class="form-control input-xs" value="'.$extension.'" />
															<span class="input-group-addon">.'.$extension.'</span>
													</div>
													</td>';
											echo "<tr>";
										}

										?>
									<tbody>
								</table>

							</div>
							<div class="col-lg-4">
								<h4>Tags</h4>
								<small class="clearfix">Tags detectadas via email:</small>
								<?php 
								unset($tags);
								preg_match_all('/#([a-zA-z0-9 -]*)#/', $email["body"], $all_tags);

								//$tags = explode(",", $email["body"]);
								$tags = $all_tags[1];
									foreach ($tags as $key => $value) {
										$query = "select * from tags where tag='".trim($value)."'";
										$res = mysql_query($query) or die_sql( $query );
										if ( mysql_num_rows($res) == 0 ) {
											$label = "danger";
											$title = "Tag não encontrada. Será adicionada se seleccionada.";
										}else{
											$label = "success";
											$title = "Tag já existente.";
										}

										echo '<label data-toggle="tooltip" data-placement="top" data-original-title="'.$title.'" style="font-size:14px;" class="btn btn-'.$label.' btn-xs uses-tooltip"><input type="checkbox" name="tags[]" value="'.trim($value).'" checked="checked" /> '.$value.'</label> ';
									}
								 ?>
								 	<hr />
									<select placeholder="Outras tags" class="form-control select2 clearfix" name="mais_tags_da_lista[]" multiple="multiple">
										<?php foreach ($all_tags as $key => $value): ?>
											<?php if ( !in_array($value["tag"], $tags) ): ?>
												<option value="<?php echo $value["id"] ?>"><?php echo $value["tag"]; ?></option>
											<?php endif ?>										
										<?php endforeach ?>
									</select>
								 	<hr />
								 	<small class="clearfix">Deseja mais tags?</small>
								 	<input type="text" class="form-control" name="add_new_tags" placeholder="Escreva as várias tags separadas por vírgulas" />

							</div>
							<div class="col-lg-4">
								<h4>Pasta</h4>
								<select class="form-control" required="required" name="category_id">
									<option value="">--</option>
									<?php foreach ($folders as $key => $folder): ?>
										<option <?php echo ( $inbox->is_email_in_folder( $folder["id"], $email["to"] ) )?' selected="selected"':''  ?> value="<?php echo $folder["id"] ?>"><?php echo $folder["name"]; ?></option>
									<?php endforeach ?>
								</select>
								<h4>Dados de Envio</h4>
								<strong>De: </strong><span><?php echo $email["from"] ?> | <?php echo $email["from_email"]; ?></span><br />
								<strong>Para:</strong><span> <?php echo $email["to_name"]; ?> | <?php echo $email["to"]; ?></span><br />
								<strong>Data/Hora: </strong><span><?php echo date("Y-m-d H:i:s", strtotime($email["data_criacao"]) ); ?><span>
							</div>
							<div class="col-lg-12 form-actions">
							<hr />
								<a href="index.php?mod=inbox_list&act=reject_email&id=<?php echo $email["id"]; ?>" class="link-confirm btn btn-danger" data-toggle="modal-confirmation">Rejeitar</a>
								<input type="submit" name="accept_document" class="btn btn-success" value="Aceitar" />

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
