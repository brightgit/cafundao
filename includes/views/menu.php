<?php 
require_once( base_path("includes/modules/users/users.mod.php") );
$u = new Users();
$this_user = $u->get_user_by_id( $_SESSION["user_bo"] );
 ?>

<nav class="main-menu" data-step="2" data-intro="Navegação Virtual Data Room" data-position="right">

	<ul>
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

	<ul class="logout">
		<li>
			<a href="index.php?mod=login&amp;logout=true">
				<i class="icon-off nav-icon"></i>
				<span class="nav-text">
					Log Out
				</span>
			</a>
		</li>  
	</ul>

</nav>