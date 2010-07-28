<?php

// BELIEVE ME THIS IS AWESOME OK
class Tintype {
	static $site_dir, $site_name, $template, $data, $render;
	function __construct(){
		# Nest-tastic!
		list($site_name,$template_name,$extra) =
		array_pad(
			array_filter(
				explode("/",
					trim(
						array_shift(
							explode("?",$_SERVER["REQUEST_URI"])
						),"/"
					),2
				)
			),3,false
		);
		if( $site_name ){
			if( self::$site_dir = $this->get_site_dir($site_name) ){
				self::$site_name = $site_name;
				if( file_exists( self::$site_dir . "data.yml" ) ){
					self::$data = sfYaml::load(self::$site_dir . "data.yml");
					if( $template_name ){
						if( file_exists( self::$site_dir . "templates/" . $template_name ) && $template_name != "index.html" ){
							self::$template = $template_name;
						} else {
							self::$template = "template_error.html";
							self::$data = array(
								"error_msg" => "Template &ldquo;{$template_name}&rdquo; does not exist."
							);
						}
					} else {
						self::$data["SITE_URL"] = "/".$site_name."/";
						self::$template = "index.html";
					}
				} else {
					self::$template = "template_error.html";
				}
			} else {
				self::$template = "template_error.html";
				self::$data = array(
					"error_msg" => "Site <code>$site_name</code> does not exist!"
				);
			}
		} else {
			self::$template = "template_error.html";
			self::$data = array(
				"error_msg" => "No site specified!"
			);
		}
	}


	private function get_site_dir($dir){
		if( self::$site_dir )
			return self::$site_dir;

		$site_dir = false;
		if( $handle = opendir(SITE_PATH) ) {
			while(false !== ($file = readdir($handle))){
				if(
					$file != "." &&
					$file != ".." &&
					is_dir( SITE_PATH . $file ) &&
					$file == $dir
				){
					$site_dir = SITE_PATH . $file . "/";
					break;
				}
			}
			closedir($handle);
		}
		if( $site_dir )
			return $site_dir;
		return false;
	}

	private function sluginate( $text, $sep = "-" ){
		return trim( preg_replace("/([^\w])+/i", $sep, strtolower( html_entity_decode( $text ) ) ), $sep );
	}

	public function render_page(){
		global $twig, $twigfs;

		Twig_Autoloader::register();
		$template_dirs = array(SYS_PATH . "templates");

		if( self::$site_dir )
			$template_dirs[] = self::$site_dir . "templates";

		$twigfs->setPaths($template_dirs);

		$template = $twig->loadTemplate(self::$template);

		// Write the output to an HTML file, maybe
		if( self::$template != "index.html" ) {
			if( !empty( $_GET["render"] ) && $_GET["render"] == "yes" ) {
				// Simple MEDIA_URL
				self::$data["MEDIA_URL"] = "../media/";
				self::$data["SITE_URL"] = "./";
				$file_contents = $template->render(self::$data);

				// Open, write, close
				try {
					$handle = fopen( self::$site_dir . "templates-compiled/" . self::$template, "w+" );
					fwrite($handle, $file_contents);
					fclose($handle);
					die( self::$template." successfully recompiled.");
				} catch(Exception $e) {
					die("FOPEN ERROR");
				}

			}
		} else {
			self::$data["templates"] = array();
			if( $handle = opendir( self::$site_dir . "templates" ) ) {
				while(false !== ($file = readdir($handle))){
					if( $file[0] != "." && $file[0] != "_" && $file != "index.html" )
						self::$data["templates"][] = $file;
				}
				closedir($handle);
				asort(self::$data["templates"]);
			}
		}

		// Overwrite any potentially changed variables
		self::$data["SITE_URL"] = "/".self::$site_name."/";
		self::$data["MEDIA_URL"] = SITE_URL . self::$site_name . "/media/";
		$template->display(self::$data);

		exit;
	}
}

?>