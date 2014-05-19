/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
utils.selectNamespace('test.widgets', function(widgets) {
    widgets.init = function() {

//        $('.myBox').append(utils.custom.tag({
//            tagType :  'inputbox', fieldType : 'strike', formatType : 'amount'
//        })).append(utils.custom.tag({
//            tagType :  'inputbox', fieldType : 'strike',  formatType : 'amount'
//        }));

        utils.custom.grid.get({
            structure: [{
                    tagType: 'inputbox', fieldType: 'strike1', formatType: 'amount'
                }, {
                    tagType: 'inputbox', fieldType: 'strike2', formatType: 'amount'
                }],
            data: [{strike1: '1m',strike2 :'2m'}, { strike1: '3m',strike2 :'4m' }],
            bulkType : true,
            parent: $('.myBox')
        });
    };
    $(document).ready(function() {
        widgets.init();
    });
});

