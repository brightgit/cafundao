<?php 
/**
* 
*/
class Tools
{
	
	function __construct()
	{
		# code...
	}

	//Transforms folder name into accpetable email
	public function foldify( $folder_name ) {
		$folder_name = tools::url_title( $folder_name );
		return $folder_name;
	}

	//adiciona mensagens a apresentar ao user em session
    public function notify_add($message, $type = "info"){

        $_SESSION["notify"]["messages"][] = array("message" => $message, "type" => $type, "already_displayed" => "false");
    }

    //lista as mensagens a apresentar ao user para serem usadas via script
    public function notify_list(){

        if(!empty($_SESSION["notify"]["messages"])){


            echo "<ul id=\"notify-messages\">";
            foreach ($_SESSION["notify"]["messages"] as $message) {
                echo "<li data-type=\"".$message["type"]."\">" . $message["message"] . "</li>";
            }
            echo "</ul>";
        }

        //limpar o queue de notifications
        self::notify_empty();

    }

    public function notify_empty(){
        $_SESSION["notify"]["messages"] = array();
    }

	public function upload_image_from_post( $post_name, $new_name, $folder ){
		echo "dafsd";
		$aux_foler = $folder.'/';
		$folder = "client_files/".$folder.'/';

		//Get extension
		$aux = explode(".", $_FILES[$post_name]["name"]);
		$ext = $aux[ ( count($aux)-1 ) ];
		$ext = strtolower($ext);

		if ( file_exists($folder.$new_name.".".$ext ) ) {
			$i = 1;
			while (file_exists( $folder.$new_name."(".$i.").".$ext )) {
				$i++;
			}
			$new_name = $new_name."(".$i.")";

		}else{
			$file_name = $new_name.".".$ext;
		}


		if ( move_uploaded_file( $_FILES[$post_name]["tmp_name"], $folder.$new_name.'.'.$ext) ) {
			return $aux_foler.$new_name.'.'.$ext;
		}else{
			return FALSE;
		}

	}

	function time_ago ($oldTime, $newTime = "") {
		
		if(empty($newTime))
			$newTime = date("Y-m-d H:m:s");

		$timeCalc = strtotime($newTime) - strtotime($oldTime);
		if ($timeCalc > (60*60*24)) {$timeCalc = round($timeCalc/60/60/24) . " dia(s) atr&aacute;s";}
		else if ($timeCalc > (60*60)) {$timeCalc = round($timeCalc/60/60) . " hora(s) atr&aacute;s";}
		else if ($timeCalc > 60) {$timeCalc = round($timeCalc/60) . " minuto(s) atr&aacute;s";}
		else if ($timeCalc > 0) {$timeCalc .= " seconds ago";}
		return $timeCalc;
	}

	function url_title( $str ){
		
	        $str = strtolower($str);
	        if (strlen($str) > 40)
	            $str = substr($str, 0, 40);

			$str = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($str));
			
			return str_replace(" ", "-", $str);
	}

}
 ?>