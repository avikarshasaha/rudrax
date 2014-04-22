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

$recipientUid = 1; 
$displayedNotificationNum = (int)$model->fetchNumberByRecipientUid($recipientUid);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Long-polling Demo</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>
<body>
<p>Recipient id: <?= $recipientUid ?></p>
<p>Notifications: <input id="notificationNum" size="4" name="some" value="<?= $displayedNotificationNum ?>" /></p>
<p>Last event arrived at: <input id="time" size="12" name="some" value="0" /></p>

<script type="text/javascript">

(function( $ ) {

var UID = <?= $recipientUid ?>;

$.NotifierLongPolling = (function() {
    var _stateNode = $('#notificationNum'), _timeNode = $('#time');
    return {
        onMessage : function(data) {
            _stateNode.val(data.updatedNotificationNum);
            _timeNode.val(data.time);
            setTimeout($.NotifierLongPolling.send, 3000);
        },
        send : function() {           
            $.ajax({
                    'url': 'server.php',
                    'type': 'POST',
                    'dataType': 'jsonp',
                    'jsonpCallback': '$.NotifierLongPolling.onMessage',
                    'data': 'recipientUid=' + UID + '&displayedNotificationNum='
                        + _stateNode.val()
            });
        }
    }
}());

// Document is ready
$(document).bind('ready.app', function() {
   setTimeout($.NotifierLongPolling.send, 40); 
});

})( jQuery );


</script>
</body>
</html>