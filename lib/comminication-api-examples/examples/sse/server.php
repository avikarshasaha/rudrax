<?php
/*
 * @category Sample
* @package Test Suit
* @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
* @license GNU
*/
define("BASE_PATH", dirname(__FILE__) );
define("APP_PATH", realpath(BASE_PATH . "/../../"));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Model/UserNotification.php";;
$model = new Model_UserNotification(new Lib_Db(APP_PATH . "/App/config.php"));

set_time_limit (600);
date_default_timezone_set('Europe/Berlin');
define("PROC_ID", 'Notifications');
define("IDLE_TIME", 3); // 3 seconds idle

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

$recipientUid = (int)$_REQUEST["recipientUid"];

function send($updatedNotificationNum)
{
	printf ("id: %s\n\n", PROC_ID);
	printf ('data: {"time" : "%s", "updatedNotificationNum" : "%d"}' . "\n\n"
			,date('d/m H:i:s') , $updatedNotificationNum);
			ob_flush();
			flush();
}

while (true) {
	send($model->fetchNumberByRecipientUid($recipientUid));
	sleep(IDLE_TIME);
}
gc_collect_cycles();
