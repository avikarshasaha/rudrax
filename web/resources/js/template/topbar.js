/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

utils.template.define('topbar',function(topbar){
    topbar.onload = function(){
      console.log("---",this.$div);
      $('.HELPLINK',this.$div).click(function(){
          alert('hi');
      });
    };
})


