<?php
ob_start("ob_gzhandler");
session_start();
require_once(realpath('../config/config.php'));
include_once(LIB . '/Factory.php');
include_once(CONTROLLER . '/Controller.php');
include_once(MODEL . '/Model.php');

$lib = new Factory();
$lib->setDirectory(LIB);
$model = new Factory();
$model->setDirectory(MODEL);
$lib->debug(true);
$router = $lib->make('Router');
$router->rule();

$_SERVER['MYSQL'] = $lib->make('MySQL'); 
$_SERVER['MYSQL']->setAdapter(HOST, ACCOUNT, PASSWORD, DATABASE);

$_SERVER['LIB'] = $lib;
$_SERVER['MODEL'] = $model;

if(!isset($_SERVER['LAYOUT'])) {
	$_SERVER['LAYOUT'] = '/html41.php';
}
require_once(LAYOUT . $_SERVER['LAYOUT']);

?>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
<?php
$router->js_controller();
?>
</script>