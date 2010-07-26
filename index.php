<?php

define("ROOT_PATH", dirname(__FILE__)."/");
define("ROOT_URL", "/");

define("SYS_PATH", ROOT_PATH . "system/");

define("SITE_PATH", ROOT_PATH . "sites/");
define("SITE_URL", ROOT_URL . "sites/");

error_reporting(E_ALL);
ini_set('display_errors', true);

// Twig template engine. So awesome.
require_once SYS_PATH . 'third-party/twig/lib/Twig/Autoloader.php';
require_once SYS_PATH . 'third-party/yaml/lib/sfYaml.php';
require_once SYS_PATH . "tintype.php";

$system = new Tintype;
$system->render_page();

?>