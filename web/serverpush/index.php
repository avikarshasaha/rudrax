<?php
/*
 * @category Sample
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */
define("BASE_PATH", dirname(__FILE__) );
define("APP_PATH", realpath(BASE_PATH . "/../"));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Model/UserNotification.php";;
$model = new Model_UserNotification(new Lib_Db(APP_PATH . "/App/config.php"));

define('RECIPIENT_UID', 1);
define('EVENT_ID', 1);

if ($_SERVER['REQUEST_METHOD'] =='POST' && isset ($_POST['action'])) {
    switch ($_POST['action']) {
        case "send":
            $model->add(RECIPIENT_UID, EVENT_ID);
            break;
        case "cleanup":
            $model->removeAll(RECIPIENT_UID);
            break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Index Demo</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>
<body>
    <p>Examples</p>
    <dl>
        <dt><a href="./longpolling/">Long-polling</a></dt>
        <dt><a href="./sse/">Server-Sent Events</a></dt>
    </dl>
    <fieldset>
        <legend>Invokation tool for Long-polling/SSE examples</legend>
        <p>Status: <var id="status"></var></p>
        <button name="send">Send new notification to user <?= RECIPIENT_UID ?></button>
        <button name="cleanup">Clean up user notifications</button>
    </fieldset>

<script type="text/javascript">

(function( $ ) {


App = (function() {
    var _sendBtn = $('button[name=send]'),
        _cleanupBtn = $('button[name=cleanup]'),
        _status = $('#status'),
        _handler = {
            onSend : function(e) {
                e.preventDefault();
                $.post('index.php', {'action' : 'send'}, function(){
                    _status.html('New notification added');
                });
            },
            onCleanup : function(e) {
                e.preventDefault();
                $.post('index.php', {'action' : 'cleanup'}, function(){
                    _status.html('Notifications cleaned up');
                });
            }
        };
    return {
        init : function(data) {
            _sendBtn.bind('click', this,  _handler.onSend);
            _cleanupBtn.bind('click', this,  _handler.onCleanup);
        }
    }
}());

// Document is ready
$(document).bind('ready.app', App.init);

})( jQuery );


</script>
</body>
</html>