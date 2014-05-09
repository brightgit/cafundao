<?php

error_reporting(E_ALL);

require('fpdf.php');

class PDF extends FPDF {

	// Better table
	function drawtable($from, $to, $cc, $subject) {

		$date = date('d/m/Y');

		//linha #1
		$this->Cell(40, 20, '', 'LRT');
		$this->Image('logo.jpg', 16, 14, 30);
		//aqui font maior, com texto centrado
		$this->SetFont('Arial', 'B', 14);
		$this->SetFillColor(217, 217, 217);
		$this->Cell(110, 10, utf8_decode('Bombeiros Voluntários de Cacilhas'), 'LRT', 0, 'C', true);
		//fim da formatacao
		$this->SetFont('Arial', '', 10);
		$this->SetFillColor(255, 255, 255);

		//minuciosidades da coluna da direita
		$this->Cell(40, 20, '', 'TR', 0, 'C', false);
		$this->SetXY(168, 10);
		$this->Write(10, 'Secretaria do');
		$this->SetXY(170, 14);
		$this->Write(10, 'Comando');
		$this->SetXY(172, 22);
		$this->Write(10, 'Modelo');
		$this->SetXY(171, 27);
		$this->Write(10, '1.6/2010');
		$this->SetXY(171, 38);
		$this->Write(10, 'Revisao 0');

		//mover cursor manualmente (tudo em trial & error)
		$this->SetXY(50, 20);
		$this->SetFont('Arial', 'B', 14);
		$this->SetFillColor(217, 217, 217);
		$this->Cell(110, 10, 'Comando', 'LR', 0, 'C', true);
		$this->SetFont('Arial', '', 14);

		$this->Ln();
		$this->Cell(40, 20, '', 'LRB');
		//aqui font maior, com texto centrado
		$this->SetFont('Arial', 'B', 20);
		$this->Cell(110, 20, utf8_decode('Comunicação Interna'), 'LRTB', 0, 'C');
		//fim da formatacao
		$this->SetFont('Arial', '', 10);
		$this->Cell(40, 20, '', 'LRB', 0, 'C');
		$this->SetFont('Arial', '', 14);

		$this->Ln();
		$this->SetFillColor(217, 217, 217);
		$this->SetFont('Arial', 'B', 14);
		$this->Cell(95, 10, 'Parecer', 'LRTB', 0, 'C', true);
		$this->Cell(95, 10, 'Despacho', 'LRTB', 0, 'C', true);
		$this->SetFillColor(255, 255, 255);
		$this->SetFont('Arial', '', 14);

		$this->Ln();
		$this->Cell(95, 50, '', 'LRTB');
		$this->Cell(95, 50, '', 'LRTB');

		$this->SetFont('Arial', 'B', 12);
		$this->Ln(60);
		$this->Cell(95, 10, 'Data: ' . $date, '');
		$this->Ln();
		$this->Cell(95, 10, 'De: ' . $from, '');
		$this->Ln();
		$this->Cell(95, 10, 'Para: ' . $to, '');
		$this->Ln();
		$this->Cell(95, 10, 'C/c: ' . $cc, '');
		$this->Ln();
		$this->Cell(95, 10, 'Assunto: ', '');
		$this->Ln();
		$this->SetFont('Arial', '', 11);
		$this->Write(5, $subject);
	}

}

$from = $_POST['from'];
$to = $_POST['to'];
$cc = $_POST['cc'];
$subject = strip_tags($_POST['message']);

$pdf = new PDF();
$pdf->SetFont('Arial', '', 14);
$pdf->AddPage();
$pdf->drawtable($from, $to, $cc, $subject);
$filename = 'com_' . substr(md5(rand(0, 100)), 0, 5) . '_' . date('ymd') . '.pdf';
$act = $_POST['act'];

//mensagem de output para envio de email
$output = '<html><head><title>Gest&atilde;o documental - Bombeiros Volunt&aacute;rios de Cacilhas</title><style>
						</style>
					</head>
					<body style="background-color:#efefef;">
						<div style="width:600px; margin: 40px auto 0 auto; font-family: Verdana, sans-serif; font-size:11px; color:#45454545; border:1px solid #338593; padding:10px; border-radius:10px; text-align: center; box-shadow: 0 0 15px #D2D2D2; background-color:#ffffff;">
							<p>Pedido efectuado com sucesso. Comunica&ccedil;&atilde;o enviada para a secretaria</p>
							<p>
								<a href="' . $_SERVER['HTTP_REFERER'] . '">Voltar</a>
							</p>
						</div>
					</body>
				</html>';

//que fazer com o pdf?
switch ($act) {
	case 'send':

		//enviar via e-mail
		$sendpdf = sendpdf($pdf, $filename);
		if ($sendpdf) echo $output;
		break;

	case 'save':
		$pdf->Output($filename, 'D');
		break;

	case 'savesend':
		$pdf->Output($filename, 'D');
		if ($sendpdf) echo $output;
		$sendpdf = sendpdf($pdf, $filename);
		break;

	default:
		break;
}

function sendpdf($pdf, $filename) {
	// email stuff (change data below)
	$to = "franco.silva@bright.pt";
	$from = "bvcacilhas@bvcacilhas.pt";
	$subject = utf8_encode("Comunicação interna");
	$message = '<table width="625" align="center" cellpadding="0" cellspacing="0" bgcolor="#fff" border="0" style=""><tr><td valign="top" bgcolor="#FFFFFF" style="font-size:11px; padding-top:0px; padding-bottom:8px;"><img src="http://server9.brightminds.pt/~bvcacilh/inc/img/frontend/maillogo.gif" width="600" height="101" /></td></tr><tr><td width="600" valign="top" bgcolor="#FFFFFF" style="font-size:12px; padding-top:0px; padding-bottom:8px;">
				<p>Documento em anexo: <br /><b><i>' . $filename . '</i></b></p>
				</td></tr></table>';

	// a random hash will be necessary to send mixed content
	$separator = md5(time());

	// carriage return type (we use a PHP end of line constant)
	$eol = PHP_EOL;

	// encode data (puts attachment in proper format)
	$pdfdoc = $pdf->Output("", "S");
	$attachment = chunk_split(base64_encode($pdfdoc));

	// encode data (puts attachment in proper format)
	$pdfdoc = $pdf->Output("", "S");
	$attachment = chunk_split(base64_encode($pdfdoc));

	// main header (multipart mandatory)
	$headers = "From: " . $from . $eol;
	$headers .= "MIME-Version: 1.0" . $eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
	$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
	$headers .= "This is a MIME encoded message." . $eol . $eol;

	// message
	$headers .= "--" . $separator . $eol;
	$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
	$headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
	$headers .= $message . $eol . $eol;

	// attachment
	$headers .= "--" . $separator . $eol;
	$headers .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
	$headers .= "Content-Transfer-Encoding: base64" . $eol;
	$headers .= "Content-Disposition: attachment" . $eol . $eol;
	$headers .= $attachment . $eol . $eol;
	$headers .= "--" . $separator . "--";

	// send message
	return mail($to, $subject, "", $headers);
}

?>
