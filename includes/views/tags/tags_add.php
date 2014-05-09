<?php 
$categoria = Categorias_add::load_categoria();


$pais = Categoria::get_parents();
if (isset($categoria["id"])) {
	$label = "A editar categoria";
}else{
	$label = "A adicionar categoria";
}

 ?>
<div class="wrapper extended scrollable" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="."><i class="icon-home"></i></a></li>
	    <li class="group">
	        <a href="index.php?mod=categorias_list">Categorias</a>
	    </li>
	    <li class="group active">
	        <a data-toggle="dropdown" href="#"><?php echo $label; ?></a>
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
		            <span><?php echo $label; ?></span>
		            <small>
		                Preencha o seguinte formulário para adicionar ou editar a categoria.
		            </small>
		        </h1>
		    </div>
		    <div class="pull-right panel-title-block-actions">
		    	<a href="index.php?mod=categorias_list">
			    	<i class="icon-arrow-left"></i>
			    	<span>Voltar</span>
		    	</a>
		    </div>

		</div>
	</div>

 	<div class="row">
 		<div class="col-sm-12">
			<div class="panel panel-default panel-block panel-title-block">
				<div class="panel-heading">

		<form action="index.php?mod=categorias_add" method="post" class="form-horizontal user-profile">
			<?php if (isset($categoria["id"])): ?>
				<input type="hidden" name="id" value="<?php echo $categoria["id"] ?>" />
			<?php endif ?>

			<div class="form-group">
				<label class="col-sm-2 control-label">Categoria</label>
				<div class="col-sm-10">
					<input type="text" value="<?php echo $categoria["categoria_pt"] ?>" name="categoria_pt" class="form-control" required="required" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Categoria <span class="label label-info">en</span> </label>
				<div class="col-sm-10">
					<input type="text" value="<?php echo $categoria["categoria_en"] ?>" name="categoria_en" class="form-control" required="required" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">url</label>
				<div class="col-sm-10">
					<input type="text" value="<?php echo $categoria["url_pt"] ?>" name="url_pt" class="form-control" required="required" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">url <span class="label label-info">en</span> </label>
				<div class="col-sm-10">
					<input type="text" value="<?php echo $categoria["url_en"] ?>" name="url_en" class="form-control" required="required" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Categoria Pai</label>
				<div class="col-sm-4">

					<select name="parent" class="form-control">
						<option value="0">Nenhum</option>
						<?php foreach ($pais as $key => $value): ?>
							<option value="<?php echo $value["id"] ?>" <?php echo ( $value["id"] == $categoria["parent"])? ' selected="selected"':''; ?>><?php echo $value["categoria_pt"] ?></option>
						<?php endforeach ?>
					</select>

				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Activo</label>
				<div class="col-sm-10 checkbox">
					<label> <input type="radio" <?php echo ( $categoria["active"] == 1 )?' checked="checked"':''; ?> name="active" value="1" /> Sim </label>
					<label> <input type="radio" <?php echo ( $categoria["active"] == 0 )?' checked="checked"':''; ?> name="active" value="0" /> Não </label>
				</div>
			</div>
			<h4>SEO</h4>
			<div class="form-group">
				<label class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="title_seo_pt" value="<?php echo $categoria["title_seo_pt"] ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Title <span class="label label-info">en</span> </label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="title_seo_en" value="<?php echo $categoria["title_seo_en"] ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<textarea class="form-control" name="description_seo_pt"><?php echo $categoria["description_seo_pt"]; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Description <span class="label label-info">en</span> </label>
				<div class="col-sm-10">
					<textarea class="form-control" name="description_seo_en"><?php echo $categoria["description_seo_en"]; ?></textarea>
				</div>
			</div>


			<div class="form-group">
				<label class="col-sm-2 control-label">Keywords</label>
				<div class="col-sm-10">
					<textarea class="form-control" name="keywords_seo_pt"><?php echo $categoria["keywords_seo_pt"]; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Keywords <span class="label label-info">en</span> </label>
				<div class="col-sm-10">
					<textarea class="form-control" name="keywords_seo_en"><?php echo $categoria["keywords_seo_en"]; ?></textarea>
				</div>
			</div>

			<?php if (isset($categoria["id"])): ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">Produtos em destaque</label>
					<div class="col-sm-10">
						<select name="destaques_categoria" class="form-control" multiple="multiple">
							<?php $destaques = categorias_add::get_destaques_categoria( $categoria["id"] ); ?>
							<?php foreach ($destaques as $key => $value): ?>
								<option value="<?php echo $value["id"] ?>"><?php echo $value["nome_pt"]; ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			<?php endif ?>


			<div class="form-action">
				<div class="col-sm-10 pull-right">
					<?php if (isset( $_GET["id"] )): ?>
						<input type="submit" name="submit" class="btn btn-primary" value="Editar" />
					<?php else: ?>
						<input type="submit" name="submit" class="btn btn-primary" value="Adicionar" />
					<?php endif ?>
				</div>
			</div>

		</form>

 		</div>
 	</div>

 		</div>
 	</div>
 </div>