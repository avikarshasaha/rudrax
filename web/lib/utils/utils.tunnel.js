/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

utils.define('utils.tunnel', function(tunnel) {

    tunnel.token = window.token || (document.location.search.split('token=')[1] || '0').split('&')[0];
    tunnel.counter = 0;
    tunnel._onMessage_ = function(resp) {
    	try{
    		this.counter = resp.counter;
    		for(var i in resp.data){
    			this.counter = Math.max(this.counter,resp.data[i].id);
    			resp.data[i].data = utils.parse(resp.data[i].eData);
    			console.log('tunnel.onMessage',this.counter,resp.data[i].data);
    		}
    		this.time = resp.time;
    	} catch(e){
    		console.error('error while processing lpoll response',e)
    	} finally {
    		tunnel.longPoll('utils.tunnel._onMessage_');    		
    	}
    };
    tunnel._onOpen_ = function(data) {
        console.log('tunnel is open',data);
        tunnel.longPoll('utils.tunnel._onMessage_');
    };
    tunnel._onSent_ = function(data) {
        console.log('tunnel is open',data);
        tunnel.longPoll('utils.tunnel._onMessage_');
    };
    tunnel.longPoll = function(_cb_,handler,data) {
        $.ajax({
            url : 'tunnel.php?@='+(handler || 'getLPollData'),
            type    : 'POST',
            dataType: 'jsonp',
            jsonpCallback: _cb_,
            data: 'token=' + this.token + '&counter='
                    + this.counter + '&data='+utils.stringify(data || {})
        });
    };
    
    tunnel.send = function(eName, eData){
    	 return tunnel.longPoll('utils.tunnel._onMessage_','sendData',eData);
    };
    
    $(document).ready(function(){
    	console.log('tunnel.starting...');
    	tunnel.longPoll('utils.tunnel._onOpen_','handshake');
    });
});
