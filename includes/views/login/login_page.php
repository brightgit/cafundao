<form action="?mod=login" method="post" class="form-horizontal">
	<div class="login-page">
		<div class="wrapper scrollable animated fadeInDown">


				<div class="panel panel-default">
					<div class="panel-heading">
						<h1 class="vdr-title"><img src="images/creditoagricola.jpg" /></h1>
					</div>

						<ul class="list-group">
							<!-- li class="list-group-item">
								<span class="welcome-text">
									Administração <?php echo $this->settings->display("client_name") ?>
								</span>
							</li -->
							<li class="list-group-item toggle-login">
								<div class="form-group">
									<label for="email">Username</label>
									<input type="email" class="form-control input-lg" id="email" name="username" placeholder="Email" />
								</div>
								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" class="form-control input-lg" id="password" name="password" placeholder="Password">
								</div>
								<div class="text-right">
									<a onclick="$('.toggle-login').toggleClass('hide');" href="Javascript:void(0);">Esqueci-me da password.</a>
								</div>
							</li>
							<li class="list-group-item hide toggle-login">
								<div class="form-group" id="recover-target">
									<label for="email_recover">Qual o seu email?</label>
									<input type="email" class="form-control input-lg" id="email_recover" name="email_recover" placeholder="Qual o seu email?">
								</div>
								<div class="text-right">
									<a onclick="$('.toggle-login').toggleClass('hide');" href="Javascript:void(0);">Voltar</a>
								</div>
							</li>
						</ul>
					<div class="panel-footer toggle-login">
						<button class="btn btn-lg btn-success" name="submit" type="submit">Efectuar Login</button>
					</div>

					<div class="panel-footer hide toggle-login">
						<button class="btn btn-lg btn-success" name="submit" onclick="return password_recover();" type="button">Recuperar Password</button>
					</div>

					<div class="panel-footer">
						<img class="both-logos" src="<?php echo base_url("images/logos/bright-digidoc.png"); ?>" alt="Bright & Digidoc"/>
					</div>


				</div>
		</div>
	</div>
</form>