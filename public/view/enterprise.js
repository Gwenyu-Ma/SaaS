define(function(require) {
    var tpl = require('text!enterprise.html');
    var mustache = require('mustache');
    return {
        container: undefined,
        render: function(container, paramStr) {
            this.container = container;
            var view = $(container);
            var html = '';
            var OID = '';
            RsCore
                .ajaxSync('Organization/getOrganization',
                    {},
                    function(data){
                        OID = data.OID;
                        if(data.OType == 1){
                            data.type='企业';
                        }else{
                            data.type = '家庭';
                        }
                        data.OName = data.OName ? data.OName:'瑞星安全云';
                        html = mustache.render(tpl,data);
                        view.append(html);
                    })
            view.on('click','.js_back',function(){
                window.history.back();
            })
            view.on('click','.js_sure',function(){
                RsCore
                    .ajax('Organization/updateOrganization',{
                            id:OID,
                            oName:view.find('[name=OName]').val()||'瑞星安全云',
                            contact:view.find('[name=Contact]').val()||'',
                            tel:view.find('[name=Tel]').val()||'',
                            addr:view.find('[name=Addr]').val()||'',
                            zipCode:view.find('[name=ZipCode]').val()||''
                        },
                        function(data){
                            RsCore.msg.success('组信息修改成功');
                        })
            })
        },
        destroy: function() {
            $(this.container).off().empty();
            console.log('destroy enterprise page');
        }
    }
});