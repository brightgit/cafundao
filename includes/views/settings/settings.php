<?php $settings = Settings::get_settings(); ?>
<div class="wrapper extended scrollable" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="."><i class="icon-home"></i></a></li>
	    <li class="group active">
	        <a data-toggle="dropdown" href="#">Definições</a>
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
		        <i class="icon-gears"></i>
		        <h1>
		            <span>Definições</span>
		            <small>
		                Definições gerais do website
		            </small>
		        </h1>
		    </div>
		</div>
	</div>




	<div class="panel panel-default panel-block">
		<div class="list-group">
			<div class="list-group-item user-profile">
				<form action="index.php?mod=settings" method="post" class="form-vertical tab-content">

					<?php foreach ($settings as $key => $value): ?>
						<?php 
						switch ($value["name"]) {
							case 'min_entrega_gratuita':	//Number
								echo "<h4>Entregas</h4>";
							case 'default_value_portugal':	//Number
							case 'default_value_internacional':	//Number
								?>
									<div class="form-group">
										<label class="col-sm-12 control-label"><?php echo $value["display_name"]; ?></label>
										<div class="col-sm-2">
											<input type="number" step=".01" value="<?php echo $value["value"] ?>" name="<?php echo $value["name"]; ?>" class="form-control" required="required" />
										</div>
									</div>
									<div class="clearfix"></div>
								<?php
								break;
							
							default:	//Texto
							if ($value["name"] == "description_seo_def") {
								echo '<div class="clearfix"></div>';
								echo "<h4>SEO</h4>";
							}
							?>

								<div class="form-group">
									<label class="col-sm-12 control-label"><?php echo $value["display_name"]; ?></label>
									<div class="col-sm-12">
										<input type="text" value="<?php echo $value["value"] ?>" name="<?php echo $value["name"]; ?>" class="form-control" required="required" />
									</div>
								</div>
								<div class="clearfix"></div>
							<?php
								break;
						}
						 ?>
					<?php endforeach ?>

					</div>
					<div clearfix="clearfix"></div>
					<div class="list-group-item">

					<div class="form-actions">
						
						<input type="submit" name="submit" value="Alterar Definições" class="btn btn-primary" />

					</div>

				</form>
			<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>