<?php
$email = '
<!DOCTYPE html>
<html>
<head>
	<title>Nova Análise de risco</title>
</head>
<body style="margin:0; padding:0; text-align:center; font-family:Lucida Grande;">
<div style="text-align:center; margin:10px auto 10px auto; padding:0;">
	<table cellpadding="0" cellspacing="0" width="630" style="width:630px; margin:0 auto; padding:0px 0; border: 1px solid #aaa; border-bottom:0;">
		<tr>
			<td style="text-align:center;"><img src="http://localhost/cafundao/images/logos/credito-agricola_news.jpg" alt="Cr&eacute;dito Agr&iacute;cola Fund&atilde;o" style="float:left;" width="630" height="72" /></td>
		</tr>
	</table>

	<table border="0" cellpadding="0" cellspacing="0" width="600" style="width:600px; margin:0 auto; border-left:1px solid #aaa; border-right:1px solid #aaa; padding:10px;">
		<tr>
			<td style="text-align:left; font-size:16px; color:#333; font-weight:bold; margin-top:;">CA Fund&atilde;o - Nova análise de risco</td>
		</tr>
		<tr>
			<td style="text-align:left; font-size:12px; color:#333; margin-top:;">
				<p>Caro(a) Sr.(a) {name},</p>
				<p>O processo '.$row_email_risco["ccc_num"].' do cliente '.$row_email_risco["name"].' necessita da sua análise de risco.</p>
				<p>Por favor proceda ao login <a href="http://virtualdataroom.pt/cafundao/index.php">na aplicação</a> de forma a processar o pedido.</p>

			</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" width="600" style="width:600px; margin:0 auto; padding:10px 0 0 0; border: 1px solid #aaa; border-bottom:0;">
		<tr>
			<td style="text-align:left; border-bottom:1px solid #aaa; padding-bottom:10px;">
			<p style="text-align:left; font-size:12px; margin-bottom:2px; color:#333; margin-top:2px; padding-left:20px; font-weight:bold;">CA - Fund&atilde;o</p>
			<p style="text-align:left; font-size:12px; margin-bottom:2px; color:#333; margin-top:2px; padding-left:20px;">Rua dos Tr&ecirc;s Lagares</p>
			<p style="text-align:left; font-size:12px; margin-bottom:2px; color:#333; margin-top:2px; padding-left:20px;">6230-421</p>
			<p style="text-align:left; font-size:12px; margin-bottom:2px; color:#333; margin-top:2px; padding-left:20px;">Fund&atilde;o</p>
			</td>
			<td style="text-align:center; border-bottom:1px solid #aaa;"><img src="http://localhost/cafundao/images/logos/bright-digidoc.png" alt="Bright & Digidoc" width="150" height="25" /></td>
		</tr>
	</table>

</div>
</body>
</html>';