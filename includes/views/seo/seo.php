<?php $settings = Settings::get_settings(); ?>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h2>Definições</h2>

			<form action="index.php?mod=settings" method="post" class="form-vertical">

				<?php foreach ($settings as $key => $value): ?>
					<div class="form-group">
						<label class="col-sm-12 control-label"><?php echo $value["display_name"]; ?></label>
						<div class="col-sm-12">
							<input type="text" value="<?php echo $value["value"] ?>" name="<?php echo $value["name"]; ?>" class="form-control" required="required" />
						</div>
					</div>
				<?php endforeach ?>

				<div class="form-actions">
					
					<input type="submit" name="submit" value="Alterar Definições" class="btn btn-primary" />

				</div>

			</form>

		</div>

	</div>
</div>