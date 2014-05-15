<?php 
$home = new Home();
//$latest_views = $home->get_latest_views();
$latest_views = $home->get_latest_aprovals();
$latest_accesses = $home->get_latest_accesses();
$stats = $home->get_stats();

require_once( base_path( "includes/modules/users/users.mod.php" ) );
$u = new Users();
$this_user = $u->get_user_by_id( $_SESSION["user_bo"] );

?>
<section class="wrapper scrollable" style="opacity: 1;">
	<section class="title-bar">
		<div id="dashboard-title">
			<img src="<?php echo base_url("images/logos/credito-agricola.png") ?>" alt="Crédito Agrícola" />
		</div>
	</section>
	<div class="special-logo pull-right">
        <div class="clearfix">
            <h5 class="text-white" onclick="window.location='index.php'">
                <span class="title text-right">
                    Virtual Data Room
                </span>
                <span class="subtitle">
                    Powered By Bright & Digidoc
                </span>
            </h5>
        </div>
	</div>

	<nav class="quick-launch-bar">
		<ul class="ui-sortable">
		<li><a href="<?php echo base_url("index.php?mod=home") ?>"><i class="icon-home nav-icon"></i><span class="nav-text">Dashboard</span></a></li>
		<!-- Disabled but working -->
		<!-- li><a href="<?php echo base_url("index.php?mod=inbox_list") ?>"><i class="icon-inbox nav-icon"></i><span class="nav-text">Inbox</span></a></li -->
		<li><a href="<?php echo base_url("index.php?mod=clientes_list") ?>"><i class="icon-file-alt nav-icon"></i><span class="nav-text">Processos</span></a></li>
		<!-- li><a href="<?php echo base_url("index.php?mod=pesquisa_avancada") ?>"><i class="icon-gears nav-icon"></i><span class="nav-text">Pesquisa Avançada</span></a></li -->
		<!-- li><a href="<?php echo base_url("index.php?mod=tags_list") ?>"><i class="icon-tags nav-icon"></i><span class="nav-text">Tags</span></a></li -->
		<?php if ($this_user["p_users"]): ?>
			<li><a href="<?php echo base_url("index.php?mod=users_list") ?>"><i class="icon-group nav-icon"></i><span class="nav-text">Utilizadores</span></a></li>
		<?php endif ?>
		<?php if ($this_user["p_users"]): ?>
			<li><a href="<?php echo base_url("index.php?mod=workflow_view") ?>"><i class="icon-code-fork nav-icon"></i><span class="nav-text">Método de decisão</span></a></li>
		<?php endif ?>
		<?php if ($this_user["p_users"]): ?>
			<li><a href="<?php echo base_url("index.php?mod=access_control_list") ?>"><i class="icon-signin nav-icon"></i><span class="nav-text">Controlo de acessos</span></a></li>
		<?php endif ?>
		<!--li><a href="<?php echo base_url("index.php?mod=settings") ?>"><i class="icon-gears nav-icon"></i><span class="nav-text">Definições</span></a></li -->
		<!-- li><a href="<?php echo base_url("index.php?mod=clients") ?>"><i class="icon-user nav-icon"></i><span class="nav-text">Clientes</span></a></li -->
		<!-- li><a href="<?php echo base_url("index.php?mod=users") ?>"><i class="icon-comment nav-icon"></i><span class="nav-text">Comentários</span></a></li-->
		</ul>
	</nav>

	<section class="widget-group col-lg-12">

		<div id="task-completion-widget" class="vdr-widget task-completion lit">
			<div class="panel panel-danger front">
				<div class="panel-heading">
					<i class="icon-cogs"></i>
					<span>Por processar</span>
				</div>
				<ul class="list-group">
					<li class="sub-list">
						<ul>
							<li class="list-group-item">
								<a href="<?php echo base_url("index.php?mod=inbox_list") ?>" class="black-link">
									<span class="title-text">
										Processos<br /><br />
									</span>
									<span class="processed-value">
										<?php
										//Processos por processar
										$processos_por_processar = $home->get_processos_por_processar(  );
										$processos_totais = $home->get_processos_totais( );

										$ficheiros_total = $home->get_total_ficheiros();
										//$ficheiros_por_processar = $home->get_ficheiros_a_processar();
										$percentagem = 100-ceil(($processos_por_processar * 100) / $processos_totais);
										?>
										<?php echo $processos_por_processar ?>
									</span>
								</a>
							</li>
							<li class="list-group-item">
								<span class="title-text">
									Análises em falta<br />
								</span>
								<span class="processed-value">
									<?php 
									$analises_em_falta = $home->get_analises_em_falta();
									echo $analises_em_falta;
									 ?>

								</span>
							</li>
							<li class="list-group-item">
								<span class="title-text">
									Tags<br /><br />
								</span>
								<span class="processed-value">
									<?php echo $stats["tags"]["unprocessed"] ?>
								</span>
							</li>
						</ul>
					</li>
					<li class="widget-progress-bar">
						<div class="form-group">
							<label>Processamento inbox:</label>
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $percentagem; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentagem ?>%;">
									<span class="sr-only">40</span>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<!-- ultimas visualizacoes -->
		<div id="messages-widget" class="vdr-widget messages">
			<div class="panel panel-default back">
				<div class="panel-heading">
					<i class="icon-cog"></i>
					<span>Settings</span>
					<div class="toggle-widget-setup">
						<i class="icon-ok"></i>
						<span>DONE</span>
					</div>
				</div>
				<ul class="list-group">
					<li class="list-group-item">
						<div class="form-group">
							<label>Filter by Location</label>
							<div>
								<select class="select2">
									<option selected="" value="Any">Any</option>
									<option value="Europe">Europe</option>
									<option value="Asia">Asia</option>
									<option value="North America">North America</option>
									<option value="Other">Other</option>
								</select>
							</div>
						</div>
					</li>
					<li class="list-group-item">
						<div class="form-group">
							<label>Filter by Time</label>
							<div>
								<select class="select2">
									<option>Any</option>
									<option>Last Hour</option>
									<option>Today</option>
									<option selected="">This Week</option>
									<option>This Month</option>
									<option>This Year</option>
								</select>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="panel panel-warning front">
				<div class="panel-heading">
					<i class="icon-eye-open"></i>
					<span>Últimas avaliações</span>
					<!--i class="icon-cog toggle-widget-setup"></i-->
				</div>
				<div>
					<ul class="list-group pending">
						<?php foreach ($latest_views as $aproval): ?>
						<li class="list-group-item">
							<!--i><img src="images/user-icons/user1.jpg" alt="User Icon"></i-->
							<div class="text-holder">
								<span class="title-text">
									<?php echo $aproval->name ?>
								</span>
								<span class="description-text votes-thumbs">
									<!-- a href="<?php echo base_url("index.php?mod=files&id=" . $aproval->id)  ?>"><?php echo $aproval->document_name ?></a -->
									<?php echo $aproval->num_sim; ?> <i style="color:#aece4e" class="icon-thumbs-up-alt"></i>
									<?php echo $aproval->num_nao; ?> <i style="color:#f4c84f" class="icon-thumbs-down-alt"></i>
									de <?php echo $aproval->num; ?> votos

								</span>
							</div>
							<span class="stat-value">

								<?php echo tools::time_ago( $aproval->data_avaliacao ) ?>
							</span>
						</li>							
					<?php endforeach ?>
				</ul>
			</div>
		</div>
	</div>
	<!-- /ultimas visualizacoes -->

	<!-- ultimos acessos -->
	<div id="messages-widget" class="vdr-widget messages">
		<div class="panel panel-default back">
			<div class="panel-heading">
				<i class="icon-cog"></i>
				<span>Settings</span>
				<div class="toggle-widget-setup">
					<i class="icon-ok"></i>
					<span>DONE</span>
				</div>
			</div>
			<ul class="list-group">
				<li class="list-group-item">
					<div class="form-group">
						<label>Filter by Location</label>
						<div>
							<select class="select2">
								<option selected="" value="Any">Any</option>
								<option value="Europe">Europe</option>
								<option value="Asia">Asia</option>
								<option value="North America">North America</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="form-group">
						<label>Filter by Time</label>
						<div>
							<select class="select2">
								<option>Any</option>
								<option>Last Hour</option>
								<option>Today</option>
								<option selected="">This Week</option>
								<option>This Month</option>
								<option>This Year</option>
							</select>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="panel panel-success front">
			<div class="panel-heading">
				<i class="icon-signin"></i>
				<span>Últimos acessos</span>
				<!--i class="icon-cog toggle-widget-setup"></i-->
			</div>
			<div>
				<ul class="list-group pending">
					<?php foreach ($latest_accesses as $access): ?>
					<li class="list-group-item">
						<!--i><img src="images/user-icons/user1.jpg" alt="User Icon"></i-->
						<div class="text-holder">
							<span class="title-text">
								<?php echo $access->name ?>
							</span>
							<span class="description-text">
								IP: <?php echo $access->ip_address; ?>
							</span>
						</div>
						<span class="stat-value">
							<?php echo tools::time_ago($access->date_in) ?>
						</span>
					</li>							
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>
<!-- /ultimos acessos -->

<!-- estatisticas -->
<div id="general-stats-widget" class="vdr-widget general-stats">
	<div class="panel panel-default back">
		<div class="panel-heading">
			<i class="icon-cog"></i>
			<span>Settings</span>
			<div class="toggle-widget-setup">
				<i class="icon-ok"></i>
				<span>DONE</span>
			</div>
		</div>
		<ul class="list-group">
			<li class="list-group-item">
				<div class="form-group">
					<label>Filter by Location</label>
					<div>
						<select class="select2">
							<option value="Any">Any</option>
							<option selected="" value="Europe">Europe</option>
							<option value="Asia">Asia</option>
							<option value="North America">North America</option>
							<option value="Other">Other</option>
						</select>
					</div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="form-group">
					<label>Filter by Time</label>
					<div>
						<select class="select2">
							<option>Any</option>
							<option>Last Hour</option>
							<option>Today</option>
							<option>This Week</option>
							<option>This Month</option>
							<option selected="">This Year</option>
						</select>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div class="panel panel-info front">
		<div class="panel-heading">
			<i class="icon-bar-chart"></i>
			<span>Estatísticas</span>
			<!--i class="icon-cog toggle-widget-setup"></i-->
		</div>
		<ul class="list-group">
			<li class="list-group-item">
				<div class="text-holder">
					<span class="title-text">
						<?php echo $stats["documents"]["total"] ?>
					</span>
					<span class="description-text">
						Documentos
					</span>
				</div>
				<span class="stat-value">
					<?php if($stats["documents"]["percentage"] > 100): ?>
						+ <?php echo $stats["documents"]["percentage"] ?> %
						<i class="icon-sort-up"></i>
					<?php else: ?>
						- <?php echo $stats["documents"]["percentage"] ?> %
						<i class="icon-sort-down"></i>
					<?php endif; ?>
				</span>
			</li>
			<li class="list-group-item">
				<div class="text-holder">
					<span class="title-text">
						<?php echo $stats["folders"]["total"] ?>
					</span>
					<span class="description-text">
						Categorias
					</span>
				</div>
				<span class="stat-value">
				<?php if($stats["folders"]["percentage"] > 100): ?>
					+ <?php echo $stats["folders"]["percentage"] ?> %
					<i class="icon-sort-up"></i>
				<?php elseif($stats["folders"]["percentage"] == 0): ?>
					0 % <i class="icon-sort"></i>
				<?php else: ?>
					- <?php echo $stats["folders"]["percentage"] ?> %
					<i class="icon-sort-down"></i>
				<?php endif; ?>
				</span>
			</li>
			<li class="list-group-item">
				<div class="text-holder">
					<span class="title-text">
						<?php echo $stats["users"]["total"] ?>
					</span>
					<span class="description-text">
						Utilizadores
					</span>
				</div>
				<span class="stat-value">
				<?php if($stats["users"]["percentage"] > 100): ?>
					+ <?php echo $stats["users"]["percentage"] ?> %
					<i class="icon-sort-up"></i>
				<?php elseif($stats["users"]["percentage"] == 0): ?>
					0 % <i class="icon-sort"></i>
				<?php else: ?>
					- <?php echo $stats["users"]["percentage"] ?> %
					<i class="icon-sort-down"></i>
				<?php endif; ?>
				</span>
			</li>
			<li class="list-group-item">
				<div class="text-holder">
					<span class="title-text">
						<?php echo $stats["clients"]["total"] ?>
					</span>
					<span class="description-text">
						Cliente(s)
					</span>
				</div>
				<span class="stat-value">
				<?php if($stats["clients"]["percentage"] > 100): ?>
					+ <?php echo $stats["clients"]["percentage"] ?> %
					<i class="icon-sort-up"></i>
				<?php elseif($stats["clients"]["percentage"] == 0): ?>
					0 % <i class="icon-sort"></i>
				<?php else: ?>
					- <?php echo $stats["clients"]["percentage"] ?> %
					<i class="icon-sort-down"></i>
				<?php endif; ?>
				</span>
			</li>
			<li class="list-group-item">
				<div class="text-holder">
					<span class="title-text">
						<?php echo $stats["comments"]["total"] ?>
					</span>
					<span class="description-text">
						Comentários
					</span>
				</div>
				<span class="stat-value">
				<?php if($stats["comments"]["percentage"] > 100): ?>
					+ <?php echo $stats["comments"]["percentage"] ?> %
					<i class="icon-sort-up"></i>
				<?php elseif($stats["comments"]["percentage"] == 0): ?>
					0 % <i class="icon-sort"></i>
				<?php else: ?>
					- <?php echo $stats["comments"]["percentage"] ?> %
					<i class="icon-sort-down"></i>
				<?php endif; ?>
				</span>
			</li>
		</ul>
	</div>
</div>
<!-- /estatisticas -->

</section>

<section>
