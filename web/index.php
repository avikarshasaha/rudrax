
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ob_start();
session_start();
define('APP_PATH', "../app");
define('LIB_PATH', "../lib");
define('R_PATH', "../lib/rudra");
define('VIEW_PATH', "../app/view");
define('HANDLER_PATH', "../app/handler");
define('DEBUG_ENABLED', TRUE);
set_include_path(APP_PATH);


include_once("db.php");
include_once("model/User.php");
$dbCon = connectDb();

function invokeController() {
    $controller;
    global $user;
    $user = new User();
   // if (isset($_GET['d'])) {
        //
   // } else {
        $temp = "index";
        if (isset($_GET['t']))
            $temp = $_GET['t'];
        include_once("controller/TemplateController.php");
        $controller = new TemplateController();
        //$controller->setName($temp);
        $controller->invoke($user,$temp);
    //}
}

function debug($msg) {
    if (DEBUG_ENABLED)
        echo"<script>try{console.log(\"" . $msg . "\");} catch(e){}</script>";
}

invokeController();
//mysql_close($dbCon);
?>