<?php
ob_start("ob_gzhandler");
session_start();
require_once(realpath('../config/config.php'));
include_once(LIB . '/Factory.php');
include_once(CONTROLLER . '/Controller.php');
include_once(MODEL . '/Model.php');

$lib = new Factory();
$lib->setDirectory(LIB);
$router = $lib->make('Router');
$router->rule();

$router->controller();
?>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
<?php
$router->js_controller();
?>
</script>