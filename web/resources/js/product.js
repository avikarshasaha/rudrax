/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
utils.selectNamespace('product', function(product) {
    product.init = function(){
        utils.template.load({name : 'topbar',parent : $('#topbar')})
    };
    $(document).ready(function(){
        product.init();
    });
});

