<?php
#if ( $REQUEST_URL == $URL_OF_CURRENT_PAGE ) die ("Direct access not premitted");

require_once("includes/core_admin.php");

error_reporting(E_ALL);
ini_set('display_errors', 1 );  
//require_once('./inc/tools.class.php');
//session_start();
$id = $_GET["id"];


$core = new Core_admin;
$prefix = $core->settings->upload_folder;
$prefix = "C:/xampp/htdocs/virtualdataroom_upload";
$prefix = "/home/vdr/virtualdataroom_upload/";

if(true){

	$sql = "SELECT id, title, category,file from documents where id = $id";
	$query = mysql_query($sql);
	$row = mysql_fetch_object($query);

	//$perms = Tools::get_permissions_array_cats($_SESSION[$key]);
	//if(in_array($row->category, $perms, true)){
	if(true){
		//$watermark_png_o = get_client_info( $row->id );
		$extension = pathinfo( $row->file, PATHINFO_EXTENSION );
		$watermark_png_o = new stdClass();
		$watermark_png_o->watermark_path = "confidential.png";

		//echo $prefix.'/'.$row->file;
		if($extension == 'pdf' && FALSE ){ //REmover para watermark
			placeWatermark( $prefix.'/'.$row->file, $prefix."/".$watermark_png_o->watermark_path , 50, 75, 100,TRUE, $row);
		}
		else if( ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png') ){

			if ( isset($_GET["full"]) ) {
				placeImgWatermark( $prefix.'/'.$row->file, $prefix."/".$watermark_png_o->watermark_path, $extension, $row, true );
			}else{
				placeImgWatermark( $prefix.'/'.$row->file, $prefix."/".$watermark_png_o->watermark_path, $extension, $row, false );
			}

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

	$filename = $file->title.'.'.$ext;
	$file_path = $prefix.'/'.$file->file;

	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private', false); // required for certain browsers 

	header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($file_path));

	readfile($file_path);

}

function placeImgWatermark($file,$image,$extension, $file_id, $full = false){
	


	$wm_path = $image;
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

	if ( !$full ) {	//Temos que mostrar thumbnail
		//destino dos thumbs
		$a = explode("/", $file);
		$just_file = $a[ count($a)-1 ];
		unset( $a[ count( $a )-1 ] );

		$thumb_path = implode("/", $a)."/thumbs/".$just_file.'.jpg';

		if ( file_exists($thumb_path) ) {	//Não precisamos de fazer nada
		}else{
			$sizes = getimagesize( $file );

	        $img_width = $sizes[0];
	        $img_height = $sizes[1];
	        $max_width = 200;
	        $max_height = 200;

	        if( $img_width > $max_width ) {

	            $final_height = ( $max_width * $img_height ) / $img_width;
	            $final_width = $max_width;
	        }else{
	            $final_height = $img_height;
	            $final_width = $img_width;
	        }

	        if( $final_height > $max_height ) {
	            $final_width = ( $img_width * $max_height ) / $img_height;
	            $final_height = $max_height;
	        }


	        $return['width'] = $final_width;
	        $return['margin_left'] = ($max_width - $return['width'])/2;
	        if( $return['margin_left'] < 0 ){
	            $return['margin_left'] = 0;
	        }else{
	            $return['margin_left'] = round($return['margin_left']);
	        }

	        $return['height'] = $final_height;
	        $return['margin_top'] = ( $max_height - $return['height'] ) / 2;
	        if( $return['margin_top'] < 0 ){
	            $return['margin_top'] = 0;
	        }else{
	            $return['margin_top'] = round($return['margin_top']);
	        }




			//echo $sizes[0].'.';
			//echo $sizes[1];
			$thumb = imagecreatetruecolor( $return["width"], $return["height"] );
			imagecopyresized( $thumb, $image, 0, 0, 0, 0, $return["width"], $return["height"], $sizes[0], $sizes[1] );
			//header('Content-Type: image/jpeg');
			imagejpeg( $thumb, $thumb_path );
			$_GET["full"] = "true";
		}
		placeImgWatermark( $thumb_path, $wm_path, "jpg", $file_id, true);

	}
	//echo $thumb_path;
	//echo "<hr />";
	//echo $file;
	//echo '<hr />';
	//echo $image;
	//echo '<hr />';


	$sx = imagesx($stamp);
	$sy = imagesy($stamp);




	imagecopy($image, $stamp, floor((imagesx($image) - $sx) / 2), floor((imagesy($image) - $sy) / 2), 0, 0, imagesx($stamp), imagesy($stamp));
	//imagecopyresized($image, $stamp, floor((imagesx($image) - $sx) / 2), floor((imagesy($image) - $sy) / 2), 0, 0, 10, 10, imagesx($stamp), imagesy($stamp));

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
	echo 'here';
	die();
	exit();

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
