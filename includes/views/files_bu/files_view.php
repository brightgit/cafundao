<?php
$files = new Files();

$file = $files->add_access( $_GET["id"] );

$file = $files->get_file( $_GET["id"] );
$user = $files->get_file_user( $_GET["id"] );
$comments = $files->get_file_comments( $_GET["id"] );
$tags = $files->get_file_tags( $_GET["id"] );
$tags_ids = $files->get_file_tags_ids( $_GET["id"] );

//var_dump($tags_ids);


$file_tags = $files->get_file_tags( $file["id"] );
$cat_tags = $files->get_tags_in_folder( $file["category"] );
$other_tags = $files->get_tags_not_in_folder( $file["category"] );

$access_documents = $files->get_file_access( $_GET["id"] );

$type_a = explode( ".", $file["file"] );
$type = $type_a[ count( $type_a )-1 ];
$file_path = $files->get_file_path( $file["category"] );

?>
<section class="wrapper user-profile extended scrollable">

	<!-- Breadcrumbs -->
	<nav class="user-menu">
		
	</nav>
	<ol class="breadcrumb breadcrumb-nav">
		<li class=""> <a href="index.php"><i class="icon icon-home"></i></a> </li>
		<?php 
		$file_url= '';
		foreach ($file_path as $key => $value) {
			$file_url .= $value["name"].'/';
			if ($key == (count($file_path)-1) ) {
				$class = "active";
			}else{
				$class = "";
			}
			echo '<li><a href="#">'.$value["name"].'</a></li>';
		}
		 ?>
	</ol>

	<div class="row">
		<div class="col-lg-7">
			<div class="panel panel-default panel-block panel-title-block">
                <div class="panel-heading clearfix">
                    <div class="avatar">
                    	<?php if ($type == "jpg"): ?>
                        	<img src="get_file.php?id=<?php echo $file["id"] ?>" alt="" />
                    	<?php else: ?>
                    		<div class="pdf-placeholder"></div>
                    	<?php endif ?>
                    </div>
                    <span class="title"><?php echo $file["title"]; ?></span>
                    <small>
                        <b class="text-bold">Filename:</b> <?php echo $file["file"]; ?> (<?php echo $file_url; ?>)
                    </small>
                    <small>
                        <b class="text-bold">Acções:</b> 
                        <a href="get_file.php?id=<?php echo $file["id"] ?>&act=d">Download</a> | 
                        <a href="index.php?mod=files_view&act=delete_file&id=<?php echo $file["id"] ?>">Apagar</a></a>
                    </small>
                    <small>
                    	<b>Criado Por:</b> <?php echo $user["name"]; ?>
                    </small>
                    <small>
                    	<b>Criado Em:</b> <?php echo date( "Y-m-d H:s:i", strtotime($file["date_in"]) ); ?>
                    </small>
                </div>
            </div>
		</div>


		<div class="col-lg-5 pull-right">

	        <div class="panel panel-default panel-block">
	            <div class="list-group">
	                <div class="list-group-item">
	                    <h4 class="section-title">Tags</h4>
	                    <div class="form-group">
	                        <?php if (isset($tags) && !empty($tags)): ?>
	                        	<?php foreach ($tags as $key => $value): ?>
	                    			<span class="btn btn-info btn-xs"><?php echo $value["tag"]; ?> <a href="index.php?mod=files_view&id=<?php echo $file["id"] ?>&act=remove_document_tag&tag_id=<?php echo $value["id"] ?>"><i class="icon icon-xs icon-remove-sign"></i></a></span>
	                        	<?php endforeach ?>
	                        <?php else: ?>
	                        	<p>Este documento ainda não tags definidas.</p>
	                        <?php endif ?>
	                    </div>
	                    <h4 class="section-title">&nbsp;</h4>
	                    <p><?php echo ( $file["uploaded_from"] == "email" )?'Enviado via email':'Adicionado via aplicação' ?> por <strong><?php echo $user["name"]; ?></strong>.<br />
	                    No dia: <strong><?php echo date("Y-m-d H:s:i", strtotime($file["date_in"])); ?></strong></p>
	                </div>
	            </div>
	        </div>

	        <div class="panel panel-default panel-block">
	            <div class="list-group">
	                <div class="list-group-item">
	                    <h4 class="section-title">Acessos</h4>
	                    <div class="form-group table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead class="">
                                    <tr>
                                        <th>User</th>
                                        <th>Ip</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
				                    <?php foreach ($access_documents as $key => $value): ?>
				                    	<tr>
				                    		<td><?php echo $value["name"]; ?></td>
				                    		<td><?php echo $value["ip_address"] ?></td>
				                    		<td><?php echo date("Y-m-d H:s:i", strtotime($value["date_in"])) ?></td>
				                    	</tr>
				                    <?php endforeach ?>
                                </tbody>
                            </table>
	                    </div>
	                </div>
	            </div>
	        </div>

		</div>
		<div class="col-lg-7">

			<div class="panel panel-info front">
	            <div class="panel-heading" onclick="$('#comments_block').toggleClass('hide');">
	                <i class="icon-comment"></i>
	                <span>Comentários</span>
	            </div>
	            <div id="comments_block" class="hide">
	                <ul class="list-group pending">

	                		<?php //var_dump($comments); ?>
	                	<?php foreach ($comments as $key => $value): ?>
		                	<li class="list-group-item generated-item animated">
		                		<h5><i class="icon icon-user"></i> &nbsp;  <span class="text-holder"><?php echo $value["title"]; ?></span>
		                			<a class="pull-right" href="index.php?mod=files_view&act=remove_comment&id=<?php echo $file["id"]; ?>&comment_id=<?php echo $value["id"]; ?>"><i class="icon icon-remove-sign"></i></a>
		                		</h5>
		                		<p><?php echo $value["comment"]; ?></p>
		                		<small class="stat-value">Comentado por <strong><?php echo $value["name"]; ?></strong> ás <?php echo date( "Y-m-d H:s:i", strtotime($value["date_inserted"]) ); ?></small>
		            		</li>                    
	                	<?php endforeach ?>
	            		<li class="list-group-item generated-item animated">
	            			<div>
		            			<div>
			            			<form action="index.php?mod=files_view&act=add_coment" method="post" class="form-horizontal">
			            				<input type="hidden" name="file_id" value="<?php echo $file["id"]; ?>" />
			            				<div class="control-group">
			            					<label>Novo comentário</label>
			            					<div class="controls">
			            						<input required="required" type="text" class="form-control" name="title" value="" placeholder="Título para o comentário" />
			            					</div>
			            				</div>
			            				<div class="clearfix margin-top-xs"></div>
			            				<div class="control-group">
			            					<div class="controls">
			            						<textarea required="required" class="form-control" name="comentario" placeholder="O seu comentário"></textarea>
			            					</div>
			            				</div>
			            				<div class="clearfix"></div>
			            				<div class="form-actions margin-top-xs">
			            					<input type="submit" name="adicionar_novo_comentario" value="Adicionar Comentário" class="btn btn-xs btn-primary" />
			            				</div>
			            			</form>
		            			</div>
		            			<div class="clerafix"></div>
	            			</div>
	            		</li>
	                </ul>
	            </div>
        	</div>

			<div class="panel panel-warning front">
	            <div class="panel-heading" onclick="$('#categorizacao_block').toggleClass('hide');">
	                <i class="icon-tag"></i>
	                <span>Adicionar Tags</span>
	            </div>
	            <form action="index.php?mod=files_view&id=<?php echo $file["id"] ?>&act=add_tags" method="post" class="form-horizontal">
		            <div id="categorizacao_block">
		                <div class="panel panel-default panel-block">
		                    <div class="list-group">
		                        <div class="list-group-item">
		                            <div class="form-group">
                                		<div class="col-lg-2">
	                            			<label class="control-label">Tags desta pasta:</label>
                                		</div>
	                            		<div class="col-lg-8">
	                            			<select placeholder="seleccione uma tag" name="tags_in_folder[]" class="form-control select2 populate" multiple="multiple">
	                            			<?php foreach ($cat_tags as $key => $tag): ?>
	                            				<option <?php echo ( in_array($tag["id"], $tags_ids) )?' selected="selected"':'' ?> value="<?php echo $tag["id"] ?>"><?php echo $tag["tag"]; ?></option>
	                            			<?php endforeach ?>
	                            			</select>
	                            		</div>
		                            </div>
		                            <div class="form-group">
                                		<div class="col-lg-2">
	                            			<label class="control-label">Outras Tags:</label>
                                		</div>
	                            		<div class="col-lg-8">
	                            			<select placeholder="seleccione uma tag" name="other_tags[]" class="form-control select2 populate" multiple="multiple">
	                            			<?php foreach ($other_tags as $key => $tag): ?>
	                            				<option <?php echo ( in_array($tag["id"], $tags_ids) )?' selected="selected"':'' ?> value="<?php echo $tag["id"] ?>"><?php echo $tag["tag"]; ?></option>
	                            			<?php endforeach ?>
	                            			</select>
	                            		</div>
		                            </div>
		                            <div class="form-actions">
		                            	<input type="submit" value="Adicionar Tags" name="submit" class="btn btn-primary" />
		                            </div>
		                        </div>
		                    </div>
		                </div>
	        		</div>
        		</form>
        	</div>


		</div>



	</div>


</section>