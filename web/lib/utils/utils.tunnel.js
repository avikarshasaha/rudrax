/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

utils.define('utils.tunnel', function(tunnel) {
    tunnel.token = 0;
    tunnel.counter = 0;
    tunnel.onMessage = function(data) {
        this.counter = data.updatedNotificationNum;
        this.time = data.time;
        console.log('tunnel.onMessage',data);
        tunnel.longPoll();
    };
    tunnel.longPoll = function() {
        $.ajax({
            url : 'tunnel/longpoll.php',
            type    : 'POST',
            dataType: 'jsonp',
            jsonpCallback: 'utils.tunnel.onMessage',
            data: 'recipientUid=' + this.token + '&displayedNotificationNum='
                    + this.counter
        });
    };

    $(document).ready(function(){
        console.log('tunnel.start');
         tunnel.longPoll();
    });
});
