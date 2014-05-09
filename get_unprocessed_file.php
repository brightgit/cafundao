<?php

error_reporting(E_ALL);
ini_set('display_errors', 1 );  
require_once("includes/core_admin.php");
//require_once('./inc/tools.class.php');
session_start();
//$id = $_GET["id"];

$core = new Core_admin;
$prefix = $core->settings->upload_folder;
$prefix = "/home/vdr/virtualdataroom_upload/unprocessed/";

if(true){

	//$sql = "SELECT id, title, category,file from documents where id = $id";
	//$query = mysql_query($sql);
	//$row = mysql_fetch_object($query);
	$row->file = $_GET["name"];

	//$perms = Tools::get_permissions_array_cats($_SESSION[$key]);
	//if(in_array($row->category, $perms, true)){
	if(true){
		//$watermark_png_o = get_client_info( $row->id );
		$extension = pathinfo( $row->file, PATHINFO_EXTENSION );
		$watermark_png_o = new stdClass();
		$watermark_png_o->watermark_path = "confidential.png";
		
		
		//echo $prefix.'/'.$row->file;
		if($extension == 'pdf' && FALSE ){ //Remover para watermark
			//download($row, $prefix);
			placeWatermark( $prefix.'/'.$row->file, $prefix."/".$watermark_png_o->watermark_path , 50, 75, 100,TRUE, $row);
		}
		else if( ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png') ){
			placeImgWatermark( $prefix.'/'.$row->file, $prefix."/".$watermark_png_o->watermark_path, $extension, $row );
		}else{
			download($row, $prefix);
			//header("Location: http://" . $_SERVER['HTTP_HOST'] . "/upd/" . $row->file, true, 301);
		}
	}
	else{
		//header("Location: http://" . $_SERVER['HTTP_HOST'], true, 301);
	}

	//	
}else{
	//header("Location: http://" . $_SERVER['HTTP_HOST'], true, 301);
}




function download( $file, $prefix ){
	
	
	$ext_a = explode(".", $file->file);
	$ext = $ext_a[ (count($ext_a)-1) ];

	$filename = $file->file.'.'.$ext;
	$file_path = $prefix.$file->file;
	
	
	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private', false); // required for certain browsers 

	header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($file_path));

	readfile($file_path);

}

function placeImgWatermark($file,$image,$extension, $file_id){

	$stamp = imagecreatefrompng($image); 
	$extension = pathinfo($file, PATHINFO_EXTENSION);
	
	switch ($extension) {
		case 'jpg':
			$image = imagecreatefromjpeg($file);
			break;
		case 'jpeg':
			$image = imagecreatefromjpeg($file);
			break;
		case 'gif':
			$image = imagecreatefromgif($file);
			break;
		case 'png':
			$image = imagecreatefrompng($file);
			break;
		
		default:
			break;
	}
	$sx = imagesx($stamp);
	$sy = imagesy($stamp);

	imagecopy( $image, $stamp, floor( ( imagesx($image) - $sx ) / 2 ), floor( ( imagesy( $image ) - $sy ) / 2 ), 0, 0, imagesx($stamp), imagesy($stamp) );

	if ( (isset($_GET["act"]) && $_GET["act"] == 'd' ) ) {
		//header('Content-Type: image/jpeg');
		$ext_a = explode(".", $file_id->file);
		$ext = $ext_a[ (count($ext_a)-1) ];
		//echo $file->title;
		//echo '<hr />';
		$filename = $file_id->title.'.'.$ext;
		$file_path = $prefix.'/'.$file_id->file;

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers 
		//header('Content-Type: application/pdf');

		header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: ' . filesize($image));

		imagejpeg($image);

	}else{
		header('Content-Type: image/jpeg');
		imagejpeg($image);

	}
}

function placeWatermark($file, $image, $xxx, $yyy, $op, $outdir, $file_id) {

	$ext_a = explode(".", $file_id->file);
	$ext = $ext_a[ (count($ext_a)-1) ];
	echo $file->title;
	echo '<hr />';
	$filename = $file_id->title.'.'.$ext;
	$file_path = $prefix.'/'.$file_id->file;


	//die($filename);

    require_once('pdf/fpdf/fpdf.php');
    require_once('pdf/fpdi/fpdi.php');

    //$file = "../up/confidential-png.png";

    // Created Watermark Image
    $pdf = new FPDI();
    if (file_exists($file)){
        $pagecount = $pdf->setSourceFile($file);
    } else {
        return FALSE;
    }
    for($i = 1; $i <= $pagecount; $i++){
        $tpl = $pdf->importPage($i);
        $pdf->addPage();
        $pdf->useTemplate($tpl, 1, 1, 0, 0, TRUE);
        //Put the watermark
        $pdf->Image($image, $xxx, $yyy, 0, 0, 'png');    
    }
    
    if ($outdir === TRUE){
		if (isset($_GET["act"]) && $_GET["act"] == "d" )  { //Vamos fazer download
        	return $pdf->Output( $filename, "D" );
		}else{
			return $pdf->Output();
		}
    } else {
        return $pdf;
    }
}
