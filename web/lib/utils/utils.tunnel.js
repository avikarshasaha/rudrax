/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

utils.define('utils.tunnel', function(tunnel) {

    tunnel.token = (document.location.search.split('token=')[1] || '0').split('&')[0];
    tunnel.counter = 0;
    tunnel._onMessage_ = function(data) {
        this.counter = data.updatedNotificationNum;
        this.time = data.time;
        console.log('tunnel.onMessage',data);
        tunnel.longPoll('utils.tunnel._onMessage_');
    };
    tunnel._onOpen_ = function(data) {
        console.log('tunnel is open',data);
        tunnel.longPoll('utils.tunnel._onMessage_');
    };
    tunnel.longPoll = function(_cb_,step) {
        $.ajax({
            url : 'tunnel.php?@='+(step || 'longpoll'),
            type    : 'POST',
            dataType: 'jsonp',
            jsonpCallback: _cb_,
            data: 'token=' + this.token + '&displayedNotificationNum='
                    + this.counter
        });
    };

    $(document).ready(function(){
    	console.log('tunnel.starting...');
    	tunnel.longPoll('utils.tunnel._onOpen_','handshake');
    });
});
