<?php
/*
 * @category Sample
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */
$model = new AbstractUserNotification($TunnelDB);

set_time_limit (600);
date_default_timezone_set('Europe/Berlin');

define("IDLE_TIME", 3); // 3 seconds idle


$recipientUid = (int)$_REQUEST["token"];
$displayedNotificationNum = (int)$_REQUEST["displayedNotificationNum"];
$secCount = 0;

do {
    sleep(IDLE_TIME);
    $updatedNotificationNum = $model->fetchNumberByRecipientUid($recipientUid);
} while ($updatedNotificationNum == $displayedNotificationNum);

header("HTTP/1.0 200");
printf ('%s({"time" : "%s", "updatedNotificationNum" : "%d"});'
    , $_REQUEST["callback"], date('d/m H:i:s'), $updatedNotificationNum);
// Clean up memory and stuff like that.
flush();
gc_collect_cycles();
