<?php 
	//alias for easy access 
	$process = $this->mod_data->process_info;
	$user = $this->mod_data->user_info;
	$can_edit = $this->mod_data->can_edit;
?>

<div class="wrapper extended scrollable" style="opacity:1;">
	<nav class="user-menu">&nbsp;</nav>
	<ol class="breadcrumb breadcrumb-nav">
		<li><a href="."><i class="icon-home"></i></a></li>
		<li class="group">
			<a href="#">Processos</a>
		</li>
		<li class="group active">
			<a data-toggle="dropdown" href="#">Início de processo</a>
		</li>
	</ol>

	<div class="panel panel-default panel-block panel-title-block">

		<div class="panel-heading">
			<div>
				<i class="icon-list-ul"></i>
				<h1>
					<span>Ficha informativa</span>
					<small>
						Ao submeter o formulário dará início ao processo de revisão de CCC.
					</small>
				</h1>
			</div>
			<div class="pull-right panel-title-block-actions">
				<a href="index.php?mod=clientes_list&amp;id=<?php echo $_GET["id"]; ?>">
					<i class="icon-arrow-left"></i>
					<span>Voltar</span>
				</a>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="col-sm-12">
			
			<div class="panel panel-default panel-block seamless-inputs">
				<div class="list-group">
					<div class="list-group-item" id="twelve-grid-system">
						<h4 class="section-title">
							Ficha Informativa - Conta Corrente Caucionada
						</h4>
						<form class="form-horizontal" role="form" action="<?php echo base_url("?mod=process&act=update&process_id=" . $process->process_id) ?>" method="post">
							<h4 class="section-title">1 - Identificação da Mutuária - Pessoa Colectiva / Empresa</h4>
							<?php if ($can_edit): ?>
								<div class="pull-right"><button type="button" class="btn-edit-process btn btn-small btn-primary"><i class="icon icon-unlock-alt"></i> &nbsp; Alterar</button></div>
							<?php endif ?>

							<?php if(!$can_edit): ?>
							<div class="form-group">
								<div class="alert alert-dismissable alert-info fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-remove"></i></button>
									<span class="title"><i class="icon-info-sign"></i> ATENÇÃO</span>
									Os dados referentes a este processo não podem ser editados. Ou o processo já se encontra em avaliação, ou não tem permissão para o fazer.
								</div>
							</div>	
							<?php endif; ?>

							<div class="form-group">
								
								<input type="hidden" name="client_id" value="<?php echo $process->cliente_id ?>" />
								<label class="col-lg-2 control-label">Nome</label>
								<div class="col-lg-4">
									<input readonly="readonly"  data-editable="0" name="process_client_nome" class="form-control" readonly="readonly" value="<?php echo $process->cliente_nome ?>">
								</div>
								<label class="col-lg-2 control-label">Nº Cliente</label>
								<div class="col-lg-4">
									<input readonly="readonly"  data-editable="0" name="process_client_numero_cliente" readonly="readonly" class="form-control" value="<?php echo $process->cliente_numero ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Balcão</label>
								<div class="col-lg-2">
									<input readonly="readonly"  data-editable="0" name="process_client_balcao" class="form-control" readonly="readonly" value="<?php echo $process->cliente_balcao ?>">
								</div>
								<label class="col-lg-2 control-label">Telemóvel</label>
								<div class="col-lg-2">
									<input readonly="readonly"  data-editable="0" name="process_client_telemovel" class="form-control" readonly="readonly" value="<?php echo $process->cliente_telemovel ?>">
								</div>
								<label class="col-lg-2 control-label">NIPC / Matric. Nº</label>
								<div class="col-lg-2">
									<input readonly="readonly"  data-editable="0" name="process_client_nipc" class="form-control" readonly="readonly" value="<?php echo $process->cliente_nipc ?>">
								</div>
							</div>

							<h4 class="section-title">2 - A Mutuária solicita e contrata com a CAIXA um empréstimo / crédito</h4>

							<div class="form-group">
								<label class="col-lg-2 control-label">2.1 Tipo de crédito</label>
								<div class="col-lg-4">
									<input readonly="readonly"  name="process_tipo_credito" readonly="readonly" type="text" class="form-control" value="<?php echo $process->tipo_credito ?>">
								</div>
							</div>

							<h5><strong>Preencher os campos 2.2 no caso de renovação de CCC;</strong></h5>

							<div class="form-group">
								<label class="col-lg-2 control-label">2.2. CCC Nº</label>
								<div class="col-lg-2">
									<input readonly="readonly"  class="form-control" readonly="readonly" name="process_ccc_id" value="<?php echo $process->ccc_num ?>">
								</div>
								<label class="col-lg-2 control-label">2.2.1. Data de Vencimento</label>
								<div class="col-lg-2">
									<input readonly="readonly"  name="process_data_vencimento" readonly="readonly" class="form-control" value="<?php echo $process->data_vencimento ?>">
								</div>
								<label class="col-lg-2 control-label">2.2.2. Data do último movimento</label>
								<div class="col-lg-2">
									<input readonly="readonly"  name="process_data_ultimo_movimento" readonly="readonly" class="form-control" value="<?php echo $process->data_ultimo_movimento ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">2.2.3 Periodicidade de pagamento de juros</label>
								<div class="col-lg-4">
									<input readonly="readonly"  name="process_periodicidade_pagamento_juros" readonly="readonly" class="form-control" value="<?php echo $process->periodicidade_pagamento_juros ?>">
								</div>
								<label class="col-lg-2 control-label">2.2.4 Prazo</label>
								<div class="col-lg-4">
									<input readonly="readonly"  name="process_prazo" class="form-control" readonly="readonly" value="<?php echo $process->prazo ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">
									2.3 Montante (Euro)
								</label>
								<div class="col-lg-2">
									<input readonly="readonly"  class="form-control" name="process_montante" readonly="readonly" value="<?php echo $process->montante ?>">
								</div>
								<label class="col-lg-2 control-label">Extenso</label>
								<div class="col-lg-6">
									<input readonly="readonly"  class="form-control" name="process_montante_extenso" readonly="readonly" value="<?php echo $process->montante_extenso ?>">
								</div>
							</div>

							<?php if ($process->montante > 50000): ?>
							<div class="form-group">
								<div class="alert alert-dismissable alert-info fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-remove"></i></button>
									<span class="title"><i class="icon-info-sign"></i> ATENÇÃO</span>
									O processo será encaminhado para o departamento de análise de risco, uma vez que o montante ultrapassa os 50,000&euro;.
								</div>
							</div>	
							<?php endif ?>
							

							<div class="form-group">
								<label class="col-lg-2 control-label">2.4 Finalidade</label>
								<div class="col-lg-10">
									<input readonly="readonly"  name="process_finalidade" class="form-control" readonly="readonly" value="<?php echo $process->finalidade ?>">
								</div>
							</div>

							<h4 class="section-title">3 - Responsabilidades</h4>

							<div class="form-group">

								<div class="col-lg-4">
									<label class="col-lg-12 control-label">3.1 Responsabilidades na Caixa</label>
								</div>
								<div class="col-lg-4">
									<table class="table table-condensed">
										<tr>
											<th>Efectiva</th>
											<th>Extrapatrimonial</th>
										</tr>
										<tr>
											<td><input readonly="readonly"  class="form-control" value=""></td>
											<td><input readonly="readonly"  class="form-control" value=""></td>
										</tr>
									</table>
								</div>
								<div class="col-lg-4">
									<label class="col-lg-6 control-label">3.2. Responsabilidade Global</label>
									<div class="col-lg-6">
										<input readonly="readonly"  name="process_responsabilidade_global" class="form-control" value="">
									</div>
								</div>
								
							</div>

							<div class="form-group">
								<label class="col-lg-12 control-label">3.3. Responsabilidades da Centralização de Riscos do Banco de Portugal</label>

								<table class="table">
									<tr>
										<th>Mutuária</th>
										<th>Prest. Mensal</th>
										<th>Crédito Efectivo</th>
										<th>Crédito Ptenc.</th>
										<th>Crédito Reneg.</th>
										<th>Crédito Venc.</th>
										<th>Crédito Abat.</th>
									</tr>
									<tr>
										<th>SICAM</th>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
									</tr>
									<tr>
										<th>TOT. BANCA</th>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
										<td><input readonly="readonly"  class="form-control" type="text" /></td>
									</tr>
								</table>
							</div>

							<h4 class="section-title">4 - Conta Depósito</h4>

							<div class="form-group">
								<label class="col-lg-4 control-label">4. Conta de Depósito à Ordem associada ao empréstimo:</label>
								<div class="col-lg-4">
									<input readonly="readonly"  name="process_conta_deposito_ordem_associado" class="form-control">
								</div>

								<label class="col-lg-2 control-label">4.1. Saldo Médio</label>

								<div class="col-lg-2">
									<input readonly="readonly"  name="process_saldo_medio" class="form-control" value="">
								</div>
							</div>

							<br />

							<div class="form-group">

								<div class="col-lg-4">
									<table class="table table-condensed">
										<caption>Outros Saldos Méd. DO (Mutuário) 100%</caption>
										<tr>
											<th>Nº Conta</th>
											<th>Saldo Médio</th>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td>Saldo médio a 100%</td>
											<td>0,00&euro;</td>
										</tr>
									</table>
								</div>

								<div class="col-lg-4">
									<table class="table table-condensed">
										<caption>Saldos Médios DO (Fiadores) 50%</caption>
										<tr>
											<th>Nº Conta</th>
											<th>Saldo Médio</th>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td>Saldo médio a 50%</td>
											<td>0,00&euro;</td>
										</tr>
									</table>

								</div>
								
								<div class="col-lg-4">
									<table class="table table-condensed">
										<caption>Saldos DP/PP (Mutuário e Fiadores) 25%</caption>
										<tr>
											<th>Nº Conta</th>
											<th>Saldo Médio</th>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
											<td><input readonly="readonly"  type="text" class="form-control" /></td>
										</tr>
										<tr>
											<td>Saldo médio a 25%</td>
											<td>0,00&euro;</td>
										</tr>
										<tr>
											<td>Saldo Médio Total</td>
											<td>0,00&euro;</td>
										</tr>
									</table>
								</div>

							</div>

							<h4 class="section-title">5 - Taxas de Juro e Bonificações</h4>

							<div class="form-group">
								
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-4 control-label">Bonificação Saldo Médio de Conta Ordem</label>
										<div class="col-lg-2">N,NNN%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">N,NNN%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Associado do Crédto Agrícola com 500&euro;</label>
										<div class="col-lg-2">0,500%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Cliente da Caixa Agrícola há mais de 5 anos</label>
										<div class="col-lg-2">0,250%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Domiciliação de mais de 4 Salários</label>
										<div class="col-lg-2">1,000%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Domiciliação de Salários (-4)</label>
										<div class="col-lg-1">Nº Salários</div>
										<div class="col-lg-1"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Seguros de Vida dos sócios</label>
										<div class="col-lg-2">0,750%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Seguro não Vida de bens da empresa</label>
										<div class="col-lg-2">0,500%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label">Boa análise de risco</label>
										<div class="col-lg-2">1,000%</div>
										<div class="col-lg-2"><input readonly="readonly"  class="form-control" /></div>
										<div class="col-lg-2">0,000%</div>
									</div>
									<br />
									<br />
									<div class="form-group">
										<label class="col-lg-4 control-label">Bonificação máxima cumulativa <br /> <strong>Mod: 050</strong></label>
										<div class="col-lg-2"><strong>5,500%</strong></div>
									</div>
								</div>

								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-6 control-label">Taxa Fixa</label>
										<div class="col-lg-6"><input readonly="readonly"  type="text" class="form-control" /></div>
									</div>
									<div class="form-group taxa-variavel"><strong>Taxa variável</strong></div>
									<div class="form-group">
										<label class="col-lg-6 control-label">Indexante</label>
										<div class="col-lg-6"><input readonly="readonly"  type="text" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="col-lg-6 control-label">Valor Atual</label>
										<div class="col-lg-6"><input readonly="readonly"  type="text" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="col-lg-6 control-label">Spread</label>
										<div class="col-lg-6"><input readonly="readonly"  type="text" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="col-lg-6 control-label">Bonificação</label>
										<div class="col-lg-6">0,000%</div>
									</div>
									<div class="form-group">
										<label class="col-lg-6 control-label">Spread Líquido</label>
										<div class="col-lg-6">0,000%</div>
									</div>
								</div>
							</div>						

							<h2>Informações</h2>

							<h4 class="section-title">6 - Informações sobre a actividade e património</h4>

							<div class="form-group">
								<div class="col-lg-12"><textarea readonly="readonly" name="process_informacoes_actividade" rows="10" class="form-control auto-resize"><?php echo $process->informacoes_actividade ?></textarea></div>
							</div>

							<h4 class="section-title">7 - Parecer do balcão</h4>

							<div class="form-group">
								<div class="col-lg-12"><textarea readonly="readonly" name="process_informacoes_parecer_balcao" rows="10" class="form-control auto-resize"><?php echo $process->informacoes_parecer_balcao ?></textarea></div>
							</div>

							<h4 class="section-title">8 - Observações</h4>

							<div class="form-group">
								<div class="col-lg-12"><textarea readonly="readonly" name="process_observacoes" rows="10" class="form-control auto-resize"><?php echo $process->observacoes ?></textarea></div>
							</div>

							<div class="form-group">
								<div class="col-lg-12">
									<div class="alert alert-dismissable alert-info fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-remove"></i></button>
										<span class="title"><i class="icon-info-sign"></i> INFO</span>
										A assinatura, local e data, serão automaticamente adicionados após decisão final.
									</div>
								</div>
							</div>

							<?php if($can_edit): ?>
							<div class="form-group">
								<div class="col-lg-12">
									<div class="pull-right"><input readonly="readonly" disabled="disabled" type="submit" class="btn-confirm-changes btn btn-success" value="Actualizar" /></div>
								</div>
							</div>
							<?php endif; ?>

						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>