/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

utils.template.define('topbar',function(topbar){
	//
    topbar.onload = function(){
      $('.HELPLINK',this.$div).click(function(){
          alert('hi');
      });
      topbar.$div.click(function(){
          if(topbar.on_bar_click) 
        	  topbar.on_bar_click();
      });
    };
    topbar.foo = function(){
    	//console.log('print..');
    };
});


