<?php 
$ac = new Access_control;
$login = $ac->get_login( $_GET["id"] );

$num_logins = $ac->num_logins_by_user( $login["user_id"] );
$num_visualizacoes = $ac->num_views_por_login( $login["id"] );
$num_total_visualizacoes = $ac->num_views_por_user( $login["user_id"] );

 ?>
<h4>Informação do login</h4>
<?php if (isset($login["date_out"])):


	$diff = abs(strtotime($login["date_out"]) - strtotime($login["date_in"]));

	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	$hours = floor(($diff)/ (60*60));
	$mins = floor(($diff - $hours*60*60)/ (60));
	$secs = floor( ($diff - $hours*60*60 - $mins*60) );

	printf("<p><strong>Duração:</strong> %d Horas, %d Minutos, %d Segundos</p>\n", $hours, $mins, $secs);
	
else: ?>
	<p>Logout não detectado.</p>
<?php endif ?>

<p>Utilizador com <strong><?php echo $num_logins; ?></strong> logins distintos.</p>
<p>Visualizou <strong><?php echo $num_visualizacoes; ?></strong> documentos nesta sessão de um total de <strong><?php echo $num_total_visualizacoes; ?></strong> visualizações.</p>


