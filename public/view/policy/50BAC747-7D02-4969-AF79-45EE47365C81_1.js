define(function(require) {
    var tpl = require('text!policy/50BAC747-7D02-4969-AF79-45EE47365C81_1.html');
    require('datetimepicker');
    require('css!datetimepicker');
    require('slimscroll');
    //var mustache = require('mustache');
    //var xmlTpl = require('text!policy/D49170C0-B076-4795-B079-0F97560485AF_1.xml');

    var op = {
        view:null,
        init: function(container, policy) {
            // 绑定事件
            this.view = container;
            this.bindEvent(container);
            this.validationEvent(container);
            // 初始化策略内容
            if (policy) {
                // 策略初始化赋值
                //console.log(policy);
                this.toHtml(container, policy);
            }      
        },        
        bindEvent: function(container) {

            container.on('change','[name=Authentication]',function(){
                var that = $(this);
                var checked = that.prop('checked');
                var dd = that.closest('dd').next('dd');
                if(checked){
                    dd.find('input').prop('disabled',false);
                }else{
                    dd.find('input').prop('disabled',true);
                }
                
            })

            /*时间控件*/
            container.find('.js_time').datetimepicker({
                datepicker: false,
                format: 'H:i',
                step: 1
            });

        },
        valida:function(){
            op.view.find('[validation]').trigger('blur');
            if(op.view.find('.error').length){
                return false;
            }
            return true;
        },
        validationEvent:function(container){
            /*错误验证事件*/
            var rule = {
                intNum:function(obj){
                    var val = obj.val();
                    return /^\d+$/.test(val);
                },
                ip:function(obj){
                    var val = obj.val();
                    return /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(val);
                }
            }

            container.on('blur','[validation]',function(){
                var that = $(this),
                    funcs = that.attr('validation').split(/\s+/),
                    len = funcs.length,
                    num = 0;
                if((funcs.indexOf('require')<0) && that.val() == ''){
                    that.removeClass('error');
                    return true;
                }
                for(var i=0;i<len;i++){
                    if(rule[funcs[i]].call(null,that)){
                        num++;
                    }else{
                        break;
                    }
                }
                if(num == len){
                    that.removeClass('error');
                }else{
                    that.addClass('error');
                }
                
            }) 
        },
        toJson: function(container) {
            
            var config = {};

            // config.subproductlist = {};
            // config.subproductlist.subproduct = [];
            // var subproducts = container.find('.subproduct dd');
            // for(var i =0;i<subproducts.length;i++){
            //     var subproduct = {};
            //     var sub_name = $(subproducts[i]).attr('id');
            //     var val = container.find('[name='+sub_name+']:checked').val();
            //     subproduct['@value'] = sub_name;
            //     subproduct['@attributes'] = { state : Number(val)};
            //     config.subproductlist.subproduct.push(subproduct);
            // }
            config.connect = {}
            config.connect.item = {}
            config.connect.item['@attributes'] = { name : 'rechttps'};
            config.connect.item.type = {};
            config.connect.item.type['@value'] = 'rshttp';
            config.connect.item.path = {};
            config.connect.item.path['@value'] = 'https://rsup16.rising.com.cn/rsv16/';

            config.netconfig = {};
            config.netconfig.nettype = {}
            config.netconfig.nettype['@value'] = Number(container.find('[name=nettype]:checked').val());
            config.netconfig.proxy = {};
            config.netconfig.proxy.ip = {};
            config.netconfig.proxy.ip['@value'] = container.find('[name=ip]').val();
            config.netconfig.proxy.port = {};
            config.netconfig.proxy.port['@value'] = container.find('[name=port]').val();
            config.netconfig.proxy.Authentication = {};
            config.netconfig.proxy.Authentication['@value'] = Number(container.find('[name=Authentication]').prop('checked'));
            config.netconfig.proxy.username = {};
            config.netconfig.proxy.username['@value'] = container.find('[name=username]').val()||'';
            config.netconfig.proxy.password = {};
            config.netconfig.proxy.password['@value'] = container.find('[name=password]').val()||''; 

            config.netconfig.update = {};
            switch (container.find(':radio[name=policy_time]:checked').val()) {
                case "2": //手动
                    config.netconfig.update['@attributes'] = {time:'2'};
                    break;
                case "0": //每天
                    config.netconfig.update['@attributes'] = {time:'0|'+container.find('[name=policy_timeTxt]').val()};
                    break;
                case "1": //每周                    
                    var bitOr = 0;
                    container.find(':checkbox[name=policy_timeWeek]:checked').each(function(i, item) {
                        bitOr = bitOr | item.value;
                    });
                    config.netconfig.update['@attributes'] = {time:'1|'+bitOr+'|'+container.find('[name=policy_timeTxt2]').val()};
                    break;
            };

            config.netconfig.update.type = {};
            config.netconfig.update.type['@value'] = container.find('[name=component]:checked').attr('libtype');
            config.netconfig.update.MaxDelay = {};
            config.netconfig.update.MaxDelay['@value'] = 0;
            config.netconfig.update.virslib = {};
            config.netconfig.update.virslib['@value'] = 1;


            var json = {
                config : config
            }
            return json;
            
        },
        toHtml: function(container, json) {
            
            var config = json.config;
            if(!config){
                return ;
            }

            // var subproductlist = config.subproductlist;
            // var subproduct = subproductlist.subproduct;
            // for(var i=0;i<subproduct.length;i++){
            //     var sub = subproduct[i];
            //     var $sub = container.find('.subproduct dd[id='+sub['@value']+']');
            //     $sub.find('[name='+sub['@value']+'][value='+sub['@attributes']['state']+']').prop('checked',true);
            // }

            var netconfig = config.netconfig;
            var nettype = netconfig.nettype;
            container.find('[name=nettype][value='+nettype['@value']+']').prop('checked',true);
            var proxy = netconfig.proxy;
            container.find('[name=ip]').val(proxy.ip['@value']);
            container.find('[name=port]').val(proxy.port['@value']);
            container.find('[name=username]').val(proxy.username['@value']);
            container.find('[name=password]').val(proxy.password['@value']);
            var $auth = container.find('[name=Authentication]');
            if(proxy.Authentication['@value']==1){
                $auth.prop('checked',true).trigger('change');
            }else{
                $auth.prop('checked',false).trigger('change');
            }

            var update = netconfig.update;
            var updateVal = update['@attributes']['time'].split('|');
            container.find('[name=policy_time][value='+updateVal[0]+']').prop('checked',true);
            switch(updateVal[0]){
                case '0':
                    container.find('[name=policy_timeTxt]').val(updateVal[1]);
                    break;
                case '1':
                    container.find('[name=policy_timeWeek]').each(function(i,item){
                        (item.value | Number(updateVal[1])) == Number(updateVal[1]) && (item.checked = true) || (item.checked = false);
                    })
                    container.find('[name=policy_timeTxt2]').val(updateVal[2]);
                    break;
                case '2':
                    break;
            }
            container.find('[name=component][libtype='+update.type['@value']+']').prop('checked',true);

            

        }
    };

    return {
        container:'#policyContent',
        render: function(container, policy) {
            container.append(tpl);
            op.init(container, policy);
            return {
                toJson: op.toJson,
                valida: op.valida
            };
        }
    };
});