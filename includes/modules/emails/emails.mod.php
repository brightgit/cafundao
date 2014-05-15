<?php 
/**
* 
*/
class Email
{
	
	function __construct()
	{
		# code...
	}

	function send_email( $to, $subject, $email ) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: <'.$to.'>' . "\r\n";
		$headers .= 'From: CA - Fundão <no-reply@virtualdataroom.pt>' . "\r\n";
		$headers .= 'Bcc: hugo.silva@bright.pt' . "\r\n";

		mail( $to , $subject, $email, $headers);

	}
}
 ?>