<?php 
$clientes_o = new Subscritores;
$clientes = $clientes_o->get_clientes();
$attrs = $clientes_o->get_attrs();

 ?>

<section class="sidebar extended" style="max-height: none; opacity: 1;">
    <script>
        if ($.cookie('vdrSidebar') == 'retracted') {
            $('.sidebar').removeClass('extended').addClass('retracted');
            $('.wrapper').removeClass('retracted').addClass('extended');
        }
        if ($.cookie('vdrSidebar') == 'extended') {
            $('.wrapper').removeClass('extended').addClass('retracted');
            $('.sidebar').removeClass('retracted').addClass('extended');
        }
    </script>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="clearfix">
                <h5>
                    <span class="title">
                        Virtual Data Room
                    </span>
                    <span class="subtitle">
                        Powered By Bright & Digidoc
                    </span>
                </h5>
            </div>
        </div>
        <div class="panel-body">
        <div class="list-group">
        <div class="list-group-item">
			<form action="<?php echo $clientes_o->generate_url( FALSE, FALSE ); ?>" method="get" class="form-horitontal">


	            <div class="title" style="height:30px;">
	                <i class="icon-shopping-cart"></i>
	                <span>
	                    Pesquisa 
	                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="icon-arrow-right"></i></button>
	                </span>
	            </div>
	            <div class="list-group">
					<label class="control-label">Email</label>
					<input type="text" name="pesquisa[newsletter_emails__email]" value="<?php echo $_GET["pesquisa"]["newsletter_emails__email"]; ?>" class="form-control input-sm" />
	            </div>

            </form>

        </div>
        </div>

        </div>
    </div>
</section>



<div class="wrapper retracted scrollable" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
	    <li><a href="."><i class="icon-home"></i></a></li>
	    <li class="group active">
	        <a data-toggle="dropdown" href="#">Subscritores</a>
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
		        <i class="icon-group"></i>
		        <h1>
		            <span>Subscritores</span>
		            <small>
		                Lista de utilizadores que querem receber a newsletter.
		            </small>
		        </h1>
		    </div>
		    <!-- div class="pull-right panel-title-block-actions">
		    	<a href="index.php?mod=grupos_paises">
			    	<i class="icon-flag-alt"></i>
			    	<span>Internacional</span>
		    	</a>
		    </div -->

		</div>
	</div>

	<h2>Subscritores</h2>
	<div class="row">
			<div class="panel panel-default panel-block">
				<div class="list-group">
					<div class="list-group-item">
						<div class="form-group">
							
						<div class="section-title">
							<div class="pull-left pagination-show">
								<span class="pull-left">A mostrar <?php echo $attrs["num"]; ?> (<?php echo ( $attrs["page"]*$attrs["num"]+1 ); ?> / <?php echo ($attrs["page"]+1)*$attrs["num"];  ?>) de <?php echo $attrs["num_total"]; ?> Clientes</span>
							</div>
							<div class="pull-right">
								<ul class="pagination pagination-demo pagination-sm">
								<?php if ( $attrs["page"] != "0" ): ?>
						  			<li><a href="<?php echo $clientes_o->generate_url("page", $attrs["page"]-1); ?>">&laquo;</a></li>
								<?php endif ?>
								<?php 
								$i = $attrs["page"]-5;
								while ( $i < ($attrs["page"]+5) ) {
									if ( $i < 0 || $i > ($attrs["num_total_pages"]-1) ) {
										$i++;
										continue;
									}
									echo '<li'.( ($i == $attrs["page"])?' class="active"':'' ).'><a href="'.$clientes_o->generate_url("page", $i).'">'.($i+1).'</a></li>';
									$i++;
								}
								 ?>
							  <?php if ( ($attrs["page"]) < ($attrs["num_total_pages"]-1)  ): ?>
					  			<li><a href="<?php echo $clientes_o->generate_url("page", $attrs["page"]+1); ?>">&raquo;</a></li>
							  <?php endif ?>
							</ul>
							</ul>
							</div>
							<div class="clearfix"></div>
						</div>



						<div class="table-responsive">
							<table class="table table-bordered table-condensed table-striped ">
								<thead>
									<tr>
										<th></th>
										<th>Email</th>
									</tr>
								</thead>
								<tbody>
									<th>Adicionar</th>
									<td>
										<form action="index.php?mod=subscritores_list" method="post">
											<input type="email" name="email" class="form-control pull-left width-200"  />&nbsp;<button type="submit" class="btn btn-primary">IR</button>
										</form>

									</td>
									<?php foreach ($clientes as $key => $value): ?>

										<?php //var_dump($value); ?>

										<tr>
											<td><a href="index.php?mod=subscritores_list&eliminar=<?php echo $value["id"]; ?>"><i class="icon-remove"></i></a></td>
											<td>
												<?php echo $value["email"] ?>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>












						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


