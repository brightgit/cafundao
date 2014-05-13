<?php


class Core_admin {

	public $mod;
	public $mod_file;
	public $mod_data; //info da página actual
	public $lang;
	public $settings;

	//on load core
	function __construct() {

		//error settings
		error_reporting(E_ALL ^E_NOTICE ^E_STRICT ^E_WARNING);
		//error_reporting(E_ALL ^E_STRICT);
		ini_set("display_errors", 1);

		//database
		$this->load_database();
		
		session_start();
		
		self::load_general_functions();

		//file based settings TODO
		$this->load_settings();


		include("includes/tools.php");

		//carrega o módulo a visualizar (página)
		$this->set_mod();
		$this->load_module();

		//verifica se foram enviados dados via post
		//$this->check_submits();	

		//set lang	
		$this->set_lang();

		//definir os headers
		header("Content-type: text/html; charset=utf-8");
	}

	function load_settings(){
		$settings = new Settings;
		$this->settings = $settings;
	}

	/**

	START dummy functions - a saber que têm de ser alteradas / copiadas para fazer o que é suposto.

	**/
	function load_database(){
		$database = new Database();
		$this->database = $database;
	}

	function set_mod(){
		$this->mod = (isset($_GET["mod"])) ? $_GET["mod"] : "home";
	}

	function set_lang() {

		if (!array_key_exists("lang_bo", $_SESSION)) $this->lang = $_SESSION['lang_bo'] = "pt"; //default lang

		@$lang = $_GET['lang'];
		$allowed = array("en","pt");
		
		if(isset($lang)){

			if (!in_array($lang,$allowed))
				return false;
			else
				$this->lang = $_SESSION['lang_bo'] = $lang;		
		}
		else{
			$this->lang = $_SESSION["lang_bo"];
		}
		
	}

	function is_logged_in() {

		if ( isset($_SESSION["user_bo"]) && $_SESSION["user_bo"] ) {
			return $_SESSION["user_bo"];
		}else{
			return false;
		}
	}

	function load_module(){

		
		include "includes/modules_list.php";


		if ( isset($modules[ $this->mod ]) ) {

			if ( ( isset($modules[ $this->mod ]["login"]) && $modules[ $this->mod ]["login"] === TRUE ) && !$this->is_logged_in() ) {
				$this->mod = "login";	//Force login
			}

		}else{
			die("Module <b>".$this->mod."</b> not defined in modules listing");
		}

		//carregar o ficheiro 
		$file = base_path("includes/modules/".$modules[ $this->mod ]["file"].".mod.php");


		//nem sempre há necessidade de carregar o módulo, pode-se excluir caso haja necessidade
		if(true){
			if(file_exists($file)){

				//incluir o ficheiro onde está definido
				require_once($file);

				//instanciar a classe e passa-la para o core
				$this->mod_data = new $this->mod;
			}else{
				die("Module <b>".$this->mod."</b> not found in <b>" . $file . "</b>");
			}
			
		}
		
	}


	//define functions de utilização transversal (root, friendly-url, etc)
	function load_general_functions(){

		function var_bump($var){
			echo '<div style="position: fixed; right:10px; top:10px; padding:15px; background: rgba(0,0,0,0.8); color:#fff; width: 300px; font-size: 11px; font-family: Consolas; line-height: 15px; border-radius: 3px; height: 300px; overflow-y: scroll;">'; var_dump($var); echo '</div>';
		}

		function redirect($uri = '', $method = 'location', $http_response_code = 302)
		{
			if ( ! preg_match('#^https?://#i', $uri))
			{
				$uri = base_url($uri);
			}

			switch($method)
			{
				case 'refresh'	: header("Refresh:0;url=".$uri);
					break;
				default			: header("Location: ".$uri, TRUE, $http_response_code);
					break;
			}
			exit;
		}

		function die_sql( $sql = "" ){
			// echo '<div style="margin-left:200px;">';
			// echo '<hr />';
			// echo mysql_error();
			// echo '<br />';
			// echo $sql;
			// echo '<hr />';
			// echo "</div>";
			// die();

		}


		function base_url($url = false){
			if($_SERVER["HTTP_HOST"] == "localhost")
				$host = "http://localhost/cafundao";
			else
				$host = "http://www.virtualdataroom.pt/cafundao";
			return $host."/".$url;
		}

		//definir o caminho absoluto
		function base_path($url = false){

			if($_SERVER["HTTP_HOST"] == "localhost"){
				$path_osx = "/Users/bright/Documents/htdocs/cafundao";
				$path_win = "C:/xampp/htdocs/cafundao";
				$path = is_dir($path_osx) ? $path_osx : $path_win;
			}
			else
				$path = "/home/vdr/public_html/cafundao";
			return $path."/".$url;
		}
		function base_path2($url = false){

			if($_SERVER["HTTP_HOST"] == "localhost"){
				$path_osx = "/Users/bright/Documents/htdocs/virtualdataroom/cafundao";
				$path_win = "C:/xampp/htdocs/cafundao";
				$path = is_dir($path_osx) ? $path_osx : $path_win;
			}
			else
				$path = "/home/vdr/public_html";
			return $path."/".$url;
		}

	}

	function show() {

		$this->setLang();
		switch ($_SESSION['lang']) {
			case "pt": include("includes/lang/pt.php"); break;
			case "en": include("includes/lang/en.php"); break;
		}
		
		$mod = htmlspecialchars($_GET['mod']);
		if (!$mod) $mod = "index";

		$this->autoLoadAdmin();
		$this->autoLoad();
		
		$mod = tools::getGet("mod");		
		if (!$mod || !array_key_exists($mod, $this->modules)) $mod = key($this->modules);
		$act = tools::getGet("act");		
		if (!$act || !array_key_exists($act, $this->modules[$mod])) $act = key($this->modules[$mod]);
		
		if ($this->modules[$mod][$act]['login'] == true && !$loggedIn) {
			$mod = key($this->modules);
			$act = key($this->modules[$mod]);
			$modFile = $this->modules[$mod][$act]['file'];	
		}
		else $modFile = $this->modules[$mod][$act]['file'];
		
		$class = $act;
		if (array_key_exists('class', $this->modules[$mod][$act])) $class = $this->modules[$mod][$act]['class'];
		$out = $this->getMod($modFile, $class);
		
		return $out;
	}

	// Include module

	public function autoLoadAdmin() {
		foreach ($this->adminModules as $mod) {
			foreach ($mod as $file) {
				if ($file['autoload'] == true) {
					$modFile = $file['file'];
					if (file_exists("admin/inc/modules/$modFile")) {
						require_once("admin/inc/modules/$modFile");
					}
				}
			}
		} reset($this->modules);
	}
	
	public function autoLoad() {
		foreach ($this->modules as $mod) {
			foreach ($mod as $file) {
				if ($file['autoload'] == true) {
					$modFile = $file['file'];
					if (file_exists("includes/modules/$modFile")) {
						require_once("includes/modules/$modFile");
					}
				}
			}
		} reset($this->modules);
	}

	static function getMod($modFile, $className, $params = false) {

		if (file_exists("includes/modules/$modFile")) {

			require_once("includes/modules/$modFile");
			eval ("\$mod = new {$className}(\$params);");
			return $mod->show($params);
		}
		else return false;
	}
	
	private function detectLang() {
		$server = $_SERVER['SERVER_NAME'];
		

		if (substr($server, -3) == ".es") $_SESSION['lang'] = 'es';
		elseif (substr($server, -3) == ".pt") $_SESSION['lang'] = 'pt';
		
		else $_SESSION['lang'] = 'pt'; //default lang
	}

	function load_view($file, $data = false){

		if(file_exists($file)){
			if($data){
					//criar um object temporario
				$temp_object = new stdClass();
				foreach ($data as $key => $value) {
					$temp_object->$key = $value;
				}
				//passar o core na própria data - deve haver melhor forma de fazer isto. não é grave porque php passa por referência
				//$temp_object->core = $this; não necessário para este caso
				//limpar $data colocando os valores do object temporario
				unset($data);
				$data = $temp_object;
			}

			ob_start();
			include($file);
			$output = ob_get_clean();
			return $output;
		}

		else echo "File <b>".$file."</b> not found";

	}

}

/**
* database
*/
class Database
{

	public $host;
	public $user;
	public $database;
	public $password;
	public $mysqli;
	static $force_online = true;
	
	function __construct()
	{

		if(self::$force_online != true){
			//definir dados da ligação
			switch ($_SERVER["HTTP_HOST"]) {
				case 'localhost':

				$this->host = "localhost";
				$this->user = "root";
				$this->password = "";
				$this->database = "virtualdataroom";
				break;
				
				default:
				
				break;
			}
		}
		else{
				$this->host = "95.154.220.60";
				$this->user = "vdr_hugo";
				$this->password = "Hugo#$12";
				$this->database = "vdr_vdr";
		}


		//go
		$connection = $this->connect();
		$this->mysqli = $connection;

		return $connection;
	}

	//alterar para mysql_connect
	function connect(){

		//$connection = new mysqli($this->host, $this->user, $this->password);		
		$connection = mysql_pconnect($this->host, $this->user, $this->password) or die( mysql_error() );
		$database = mysql_select_db($this->database);
		mysql_query("SET NAMES UTF8"); //fix encoding
		
		return ($connection !== false) ? $connection:false;
	}
}

/**
* Settings
*/
class Settings
{
	//environment settings (pré-database)
	public $client_name;
	public $upload_folder;
	public $ftp_host;
	public $ftp_user;
	public $ftp_password;
	public $domain;

	function __construct()
	{
		$this->client_name = "Virtual Data Room";
		$this->ftp_upload_folder = "virtualdataroom_upload";
		$this->upload_folder = base_path2("../virtualdataroom_upload");
		$this->ftp_host = "95.154.220.60";
		$this->ftp_user = "upload@virtualdataroom.pt";
		$this->ftp_password = "Hugo#$12";
		$this->server_prefix = "/home/vdr";
		$this->domain[] = "virtualdataroom.pt";
	}

	function display($setting){
		if(isset($this->$setting)){
			return $this->$setting;
		}
	}

	function get_doamin(){
		return $this->domain;
	}


}

?>