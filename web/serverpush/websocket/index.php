<?php
/*
 * @category Sample
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */

$recipientUid = rand(1,1000);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WebSockets Demo</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery.json.min.js"></script>
<script type="text/javascript" src="./js/jquery.realtime.js"></script>
</head>
<body>
    <p>Recipient id: <?= $recipientUid ?></p>
    <p>Notification: <span id="display"></span></p>
    <button id="test">Fire an event</button>

<script>
    realtime = new realtimeComm(window.location.host + ":20001");
    realtime.addListener('/notification/updates', function(response) {
	    $('#display').html('Client #' + response.data.recipientUid + ' broadcast an action #' + response.data.actionId);
    });

    $('#test').bind('click', this, function(e){
        e.preventDefault();

        realtime.send('/notification/presence', {
            'actionId': 1,
            'recipientUid': <?= $recipientUid ?>
        }, function() {});

    });

</script>
</body>
</html>