#!/usr/bin/php -q
<?php
ini_set("display_errors", 0);

//include email parser
require_once('rfc822_addresses.php');
require_once('mime_parser.php');

$connection = mysql_connect("95.154.220.60", "vdr_hugo", "Hugo#$12");
$database = mysql_select_db("vdr_vdr");
mysql_query("SET NAMES UTF8"); //fix encoding

# read email in from stdin
$fd = fopen("php://stdin", "r");
$email = "";
while (!feof($fd)) {
    $email .= fread($fd, 1024);
}
fclose($fd);

//$email = quoted_printable_decode( $email );
//$query = "insert into teste values (null, '".mysql_real_escape_string($email)."')";
//mysql_query($query);




//create the email parser class
$mime=new mime_parser_class;
$mime->ignore_syntax_errors = 1;
$parameters=array(
	'Data'=>$email,
);
	
$mime->Decode($parameters, $decoded);

//---------------------- EMAIL HEADER -----------------------//

//get the name and email of the sender
$fromName = $decoded[0]['ExtractedAddresses']['from:'][0]['name'];
$fromEmail = $decoded[0]['ExtractedAddresses']['from:'][0]['address'];

//get the name and email of the recipient
$toEmail = $decoded[0]['ExtractedAddresses']['to:'][0]['address'];
$toName = $decoded[0]['ExtractedAddresses']['to:'][0]['name'];

//get the subject
$subject = $decoded[0]['Headers']['subject:'];


$removeChars = array('<','>');

//get the message id
$messageID = str_replace($removeChars,'',$decoded[0]['Headers']['message-id:']);

//get the reply id
$replyToID = str_replace($removeChars,'',$decoded[0]['Headers']['in-reply-to:']);


//---------------------- BODY -----------------------//

//get the message body
if(substr($decoded[0]['Headers']['content-type:'],0,strlen('text/plain')) == 'text/plain' && isset($decoded[0]['Body'])){
	
	$body = $decoded[0]['Body'];

} elseif(substr($decoded[0]['Parts'][0]['Headers']['content-type:'],0,strlen('text/plain')) == 'text/plain' && isset($decoded[0]['Parts'][0]['Body'])) {
	
	$body = $decoded[0]['Parts'][0]['Body'];

} elseif(substr($decoded[0]['Parts'][0]['Parts'][0]['Headers']['content-type:'],0,strlen('text/plain')) == 'text/plain' && isset($decoded[0]['Parts'][0]['Parts'][0]['Body'])) {
	
	$body = $decoded[0]['Parts'][0]['Parts'][0]['Body'];

}

//loop through email parts
foreach($decoded[0]['Parts'] as $part){
	
	//check for attachments
	if($part['FileDisposition'] == 'attachment'){
		
		//$part["FileName"] = utf8_encode( $part["FileName"] );


		//format file name (change spaces to underscore then remove anything that isn't a letter, number or underscore)
		$part['FileName'] = url_title ( $part['FileName'] );
		$filename = $part["FileName"];
		
		//write the data to the file
		$fp = fopen("files/" . $filename, 'w');
		$written = fwrite($fp,$part['Body']);
		fclose($fp);
		
		$conn = ftp_connect( "95.154.220.60" );
		$login = ftp_login( $conn, "upload@virtualdataroom.pt", "Hugo#$12" );
		$upload = ftp_put($conn, "virtualdataroom_upload/unprocessed/".$filename, "files/".$filename, FTP_BINARY);
		ftp_close( $conn );

		
		
		//add file to attachments array
		$attachments[] = $part['FileName'];

	}

}

$base_path = "http://95.154.220.60/~vdr/pipe";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";


//$attachments_string = var_export($attachments,true);
foreach ($attachments as $key => $attachment) {
	//$attachments[$key] = utf8_encode( $attachment );
	$attachments_string .= "<a href='".$base_path."/files/".$attachment."'>".$attachment."</a><br/>";
}


$subject = quoted_printable_decode( $subject );

$subject = utf8_encode($subject);
$subject = str_replace("=?ISO-8859-1?Q?", "", $subject);
$subject = str_replace("?", "", $subject);

$attachments_string = utf8_encode($attachments_string);
$body = utf8_encode($body);



//compor mensagem de feedback para testar dados
$notify = "Novo email: <br/><br/>From: " . $fromName . " - " . $fromEmail . "<br/>To: " . $toName . " - " . $toEmail . "<br/>Subject: " . $subject . "<br/>Attachments: <br/>" . $attachments_string . "<br/>Body: ".$body;


$aux = implode(",", $attachments);
$aux = trim( $aux );
if (!empty($aux)) {
	$query = "INSERT into emails (id, `from`, from_email, `to`, to_name, subject, body, attachments, processed, data_criacao) values (null, '".$fromName."', '".$fromEmail."', '".$toEmail."', '".$toName."', '".$subject."', '".$body."', '".implode(",", $attachments)."', 0, NULL)";
	mysql_query( $query ) or die( mysql_error() );
}



function url_title( $str ){
	
        $str = strtolower($str);
        if (strlen($str) > 40)
            $str = substr($str, 0, 40);

		$str = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($str));
		
		return str_replace(" ", "-", $str);
}

//mail("hugo.silva@bright.pt", "New email arrived", $notify, $headers);
//mail("franco.silva@bright.pt", "New email arrived", $notify, $headers);
return NULL;