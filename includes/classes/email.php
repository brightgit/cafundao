<?php 

class Email
{


	function __construct()
	{
		$this->header = '<html>
							<head>
								<title>Virtual Data Room</title>
							</head>
							<body style="font-family:sans-serif; background-color:#EDEDED;">
								<!-- Container -->
								<div>
									<!-- Header -->
									<table style="background-color:white; margin: 100px auto auto auto; width:630px; text-align:center; border:1px solid #E5E5E5;" cellpadding="0" cellspacing="0"><!-- Title -->
				<tr>
					<td style="padding:20px 0px;"><h1 style="font-size:30px; margin:0px; padding:0px; color:#666; text-transform:uppercase;">Virtual Data Room</h1></td>
				</tr>';

		$this->footer = '<!-- Logo -->
						<tr>
							<td style="border-top:1px solid #E5E5E5; padding:30px 0px;"><img src="http://virtualdataroom.pt/images/logos/bright-digidoc.png" alt="Bright & Digidoc" /></td>
						</tr>
					</table>

				</div>
			</body>
		</html>';


	}

	function send_email( $from_a, $to, $subject, $message, $bcc_a = FALSE ) {
		if ($bcc_a) {
			if (is_array($bcc_a)) {
				$bcc = implode(",", $bcc_a);
			}else{
				$bcc = $bcc_a;
			}
		}


		$message_a = $this->header.$this->load_subject( $subject ).$this->content( $message ).$this->footer;


		$headers  = "From: <" . $from_a . ">\r\n" .
		  "X-Mailer: php\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		if ($bcc) {
			$headers .= "Bcc: " . $bcc . "\r\n";
		}


		return mail( $to , $subject, $message_a, $headers);


	}

	function load_subject( $subject ) {
		return '
				<!-- Subject -->
				<tr>
					<td style="border-top:1px solid #E5E5E5; padding:10px 0px; text-align:left;"><h2 style="font-size:20px; margin:0px 0px 0px 20px; padding:0px; color:#666;">'.$subject.'</h2></td>
				</tr>';
	}

	function content( $content ) {
		return '<tr>
					<td style="text-align:left; padding:10px;">'.$content.'</td>
				</tr>';
	}


}

 ?>