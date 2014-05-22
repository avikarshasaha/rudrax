/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

utils.template.define('bottombar',function(bottombar){
	bottombar.onload = function(){
      $('.HELPLINK',this.$div).click(function(){
          alert('hi');
      });
    };
    bottombar.foo = function(){
    	//console.log('print..');
    };
});


