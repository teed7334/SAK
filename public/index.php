<?php
ob_start();
session_start();
require_once(realpath('../config/config.php'));
include_once(LIB . '/Factory.php');
include_once(CONTROLLER . '/Controller.php');
include_once(MODEL . '/Model.php');

date_default_timezone_set(TIME_ZONE);

$lib = new Factory();
$lib->setDirectory(LIB);
$router = $lib->make('Router');
$router->rule();

$router->controller();
?>
<script>
<?php
$router->js_controller();
?>
</script>
<?php 
ob_end_flush();
?>