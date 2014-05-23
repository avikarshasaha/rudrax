/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
utils.selectNamespace('product', function(product) {
	//utils.files.setResourcePath('resources');
    product.init = function(){
    	product.t1 =   utils.template.load({
    		name : 'topbar',
    		parent : $('.topbar'),
    		on_bar_click : function(){
    			alert('u clicked on topbar one');
    		}
    	});
    	product.t2 =  utils.template.load({
    		name : 'topbar',
    		parent : $('.topbar'),
    		on_bar_click : function(){
    			alert('u clicked on topbar two');
    		}
    	});
        
        utils.template.load({name : 'bottombar',parent : $('.bottombar')});
    };
    $(document).ready(function(){
       // product.init();
    });
});

