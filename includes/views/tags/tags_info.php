<?php 
$tags_c = new Tags();
$tag = $tags_c->get_tag_info( $_GET["id"] );
$tag_categories = $tags_c->get_all_tags_categories( $_GET["id"] );
 ?>
<h4 id="tag_info" data-tag_name="<?php echo $tag["tag"]["tag"]; ?>" data-tag_id="<?php echo $tag["tag"]["id"] ?>" data-tag_active="<?php echo $tag["tag"]["active"]; ?>" class="section-title">
	<?php echo $tag["tag"]["tag"]; ?>
	<a href="#input_tag" class="pull-right"><i class="icon icon-edit" title="editar"></i></a>
</h4>
<p><strong>Criada Em:</strong> <?php echo date( "Y-m-d", strtotime( $tag["tag"]["data_criacao"] ) ) ?></p>
<p><strong>Criada Por:</strong> <?php echo date( "Y-m-d", strtotime( $tag["user"]["name"] ) ) ?></p>
<!-- h4 class="section-title">Categorias</h4>
<hr />
<?php if (isset($tag["categories"]) && !empty($tag["categories"])): ?>
	<ol>
		<?php foreach ($tag["categories"] as $key => $value): ?>
			<li><?php echo $value["tag_category"]; ?><a class="pull-right" href="index.php?mod=tags_list&act=remover_categoria&id=<?php echo $value["id"]; ?>"><i class="icon icon-remove-sign"></i></a></li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>Nenhuma categoria definida.</p>
<?php endif ?>
<p>Adicionar nova categoria:</p>
<form class="form-horizontal" action="index.php?mod=tags_list&act=add_tag_category" method="post">
	<input type="hidden" name="tag_id" value="<?php echo $_GET["id"] ?>" />
	<div class="form-group">
		<div class="col-lg-10">
			<select name="category_id" class="form-control" required="required">
				<option value="">...</option>
				<?php foreach ($tag_categories as $key => $value): ?>
					<option value="<?php echo $value["id"] ?>"><?php echo $value["tag_category"]; ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<div class="coll-lg-2">
			<button type="submit" class="btn btn-primary"><i class="icon icon-plus"></i></button>
		</div>
		
	</div>
</form -->
