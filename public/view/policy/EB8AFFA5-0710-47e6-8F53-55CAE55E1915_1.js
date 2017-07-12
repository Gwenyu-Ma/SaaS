define(function(require) {
    var tpl = require('text!policy/EB8AFFA5-0710-47e6-8F53-55CAE55E1915_1.html');
    require('selectric');
    require('css!selectric');
    require('datetimepicker');
    require('css!datetimepicker');
    require('slimscroll');
    //var mustache = require('mustache');
    //var xmlTpl = require('text!policy/D49170C0-B076-4795-B079-0F97560485AF_1.xml');

    var op = {
        view:null,
        init: function(container, policy) {
            this.view = container;
            this.validationEvent(container);
            // 绑定事件
            this.bindEvent(container);
            // 初始化策略内容
            if (policy) {
                // 策略初始化赋值
                //console.log(policy);
                this.toHtml(container, policy);
            }
             $('#autoGroup_list').slimScroll({ height: '200px' });              
        },
        autoGroupList:function(container){
            var list = RsCore.cache.group.list;
            if(list){
                var _html=[];
                for(var i in list){
                    _html.push('<li><label class="radio"><input type="radio" id="'+i+'" gName="'+list[i]+'" name="autoGroupID">'+list[i]+'</label></li>');
                }
                container.find('#autoGroup_list').html(_html.join(''));                          
            }
        },
        ipRule:function(opt){
            var config = {
                symbol:0,
                value:''
            }
            config = $.extend(config,opt);
            var syb  = config.symbol,
                val1 = config.value?config.value.split('-')[0]:'';
                val2 = config.value&&config.value.split('-')[1]||'';
            var html ='<div class="autoGroupRule" type="ip">'+
                    '<a href="javascript:;" class="js_rule_del"><i class="fa fa-trash"></i></a>'+
                    '<span class="js_and"> 且 </span>'+
                    '<strong>IP匹配规则</strong>  '+
                    '<span><select name="">'+
                    '<option value="0" '+(syb==0?'selected':'')+'>等于</option>'+
                    '<option value="1" '+(syb==1?'selected':'')+'>不等于</option>'+
                    '<option value="2" '+(syb==2?'selected':'')+'>包含于</option>'+
                    '<option value="3" '+(syb==3?'selected':'')+'>不包含于</option>'+
                    '</select></span>  '+
                    '<input type="text" class="input-medium" value="'+val1+'"/>'+
                    '<span class="js_hide '+(syb>1?'':'hide')+'"> ~ <input type="text" class="input-medium" value="'+val2+'" validation="ip"></span>'+
                    '</div>';  
            var $html = $(html);
            $html.find('select').selectric({
                inheritOriginalWidth: true
            });
            return $html;              
        },
        osRule:function(opt){
            var config = {
                symbol:0,
                value:''
            }
            config = $.extend(config,opt);
            var syb  = config.symbol,
                val1 = config.value?config.value:'';
            var html ='<div class="autoGroupRule" type="os">'+
                    '<a href="javascript:;" class="js_rule_del"><i class="fa fa-trash"></i></a>'+
                    '<span class="js_and"> 且 </span>'+
                    '<strong>操作系统规则</strong>  '+
                    '<span><select name="">'+
                    '<option value="0" '+(syb==0?'selected':'')+'>包含</option>'+
                    '<option value="1" '+(syb==1?'selected':'')+'>不包含于</option>'+
                    '</select></span>  '+
                    '<input type="text" class="input-medium" value="'+val1+'" />'+
                    '</div>';
            var $html = $(html);
            $html.find('select').selectric({
                inheritOriginalWidth: true
            });
            return $html;   
        },
        computerRule:function(opt){
            var config = {
                symbol:0,
                value:''
            }
            config = $.extend(config,opt);
            var syb  = config.symbol,
                val1 = config.value?config.value:'';
            var html ='<div class="autoGroupRule" type="compotername">'+
                    '<a href="javascript:;" class="js_rule_del"><i class="fa fa-trash"></i></a>'+
                    '<span class="js_and"> 且 </span>'+
                    '<strong>计算机名称规则</strong>  '+
                    '<span><select name="">'+
                    '<option value="0" '+(syb==0?'selected':'')+'>包含</option>'+
                    '<option value="1" '+(syb==1?'selected':'')+'>不包含于</option>'+
                    '</select></span>  '+
                    '<input type="text" class="input-medium" value="'+val1+'" />'+
                    '</div>';
            var $html = $(html);
            $html.find('select').selectric({
                inheritOriginalWidth: true
            });
            return $html;   
        },
        ruleBox:function(id,name,_html){
            var html = '<li class="autoGroupRuleLi" id="'+id+'">'+
                    '<div class="autoGroupTit">'+
                    '<div class="autoGroup_opt">'+
                    '<a href="javascript:;" class="js_rules_del">删除</a>  '+
                    '<a href="javascript:;" class="js_rules_up">上移</a>  '+
                    '<a href="javascript:;" class="js_rules_down">下移</a>  '+
                    '</div>'+
                    '<h3 class="autoGroup_Name">'+name+'</h3>'+
                    '</div>'+
                    '<div class="autoGroupBod">'+
                    '</div>'+
                    '<div class="autoGroupFooter">'+
                    '<a href="javascript:;" class="js_rule_ip"><i class="fa fa-plus"></i>IP匹配规则</a>'+
                    '<a href="javascript:;" class="js_rule_sys"><i class="fa fa-plus"></i>操作系统规则</a>'+
                    '<a href="javascript:;" class="js_rule_name"><i class="fa fa-plus"></i>计算机名称规则</a>'+
                    '</div>'+
                    '</li>';
            var $html = $(html);
            if(_html){
                $html.find('.autoGroupBod').append(_html);
            }else{
                $html.find('.autoGroupBod').append(op.ipRule());
            }
            $('.autoGroupUL').append($html);
        },
        bindEvent: function(container) {
           
            container.on('click','.js_rule_del',function(){
                var obj = $(this).closest('.autoGroupRule');
                obj.fadeOut(400,function(){
                    obj.remove();
                });
            })            

            container.on('click','#autoGroup_addRule',function(){
                op.autoGroupList(container);
                container.find('#autoGroup_modal').modal();
            })

            container.on('click','#autoGroup_reAuto',function(){
                RsCore.ajax('Group/autoAll',function(data){
                    RsCore.msg.success('重新入组成功 !');
                })
            })

            container.on('click','#autoGroup_chioce',function(){
                var obj = container.find('[name=autoGroupID]:checked');
                var id = obj.attr('id')
                var name = obj.attr('gName');
                if(obj.length){
                    container.find('#autoGroup_modal').modal('hide');
                    op.ruleBox(id,name);
                    container.find('.js_no_data').hide();
                }else{
                    RsCore.msg.warn('请选择分组 !');
                }
                
            })

            container.on('click','.js_rule_ip',function(){
                var obj = $(this).closest('.autoGroupRuleLi').find('.autoGroupBod');
                obj.append(op.ipRule());
            })
            container.on('click','.js_rule_sys',function(){
                var obj = $(this).closest('.autoGroupRuleLi').find('.autoGroupBod');
                obj.append(op.osRule());
            })
            container.on('click','.js_rule_name',function(){
                var obj = $(this).closest('.autoGroupRuleLi').find('.autoGroupBod');
                obj.append(op.computerRule());
            })

            container.on('click','.js_rules_del',function(){
                var obj = $(this).closest('.autoGroupRuleLi');
                obj.fadeOut(400,function(){
                    obj.remove();
                    if(container.find('.autoGroupRuleLi').length<1){
                        container.find('.js_no_data').show();
                    }
                });
            })

            container.on('click','.js_rules_up',function(){
                var obj = $(this).closest('.autoGroupRuleLi');
                var prev = obj.prev();
                if(prev.hasClass('autoGroupRuleLi')){
                    obj.fadeOut(400,function(){
                        obj.remove();
                        prev.before(obj);
                        obj.fadeIn();
                    });
                }
                
            })

            container.on('click','.js_rules_down',function(){
                var obj = $(this).closest('.autoGroupRuleLi');
                var next = obj.next();
                if(next.hasClass('autoGroupRuleLi')){
                    obj.fadeOut(400,function(){
                        obj.remove();
                        next.after(obj);
                        obj.fadeIn();
                    });
                }
                
            })

            container.on('change','.autoGroupRule[type=ip] select',function(){
                var that = $(this);
                if(that.val()>1){
                    that.closest('.autoGroupRule').find('.js_hide').removeClass('hide');
                }else{
                    that.closest('.autoGroupRule').find('.js_hide').addClass('hide');
                }
            })

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
            function getGroup(obj){
                var target = obj.find('.autoGroupRule');
                var group = {};
                group.groupid = Number(obj.attr('id'));
                group.rule=[];
                for(var i =0;i<target.length;i++){
                    group.rule.push(getRule($(target[i])));
                }
                return group;
            }   

            function getRule(obj){
                var rule = {};
                rule.type = obj.attr('type');
                rule.symbol = getSymbol(rule.type,obj.find('select option:selected').val());
                var ipts = obj.find('input[type=text]');
                var value = [];
                for(var i=0;i<ipts.length;i++){
                    var val = $(ipts[i]).val();
                    val&&value.push(val);
                }
                rule.value = value.join('-');
                return rule;
            }

            function getSymbol(type,val){
                var symbol  = ['equal','notequal','in','notin'],
                    symbol2 = ['has','nothas'];
                if(type=='ip'){
                    return symbol[val];
                }else{
                    return symbol2[val];
                }

            }

            var lis = container.find('.autoGroupRuleLi');
            var result = [];
            for(var i=0;i<lis.length;i++){
                result.push(getGroup($(lis[i])));
            }

            return result;
        },

       
        toHtml: function(container, json) {
            var opt = {
                'ip':op.ipRule,
                'os':op.osRule,
                'computername':op.computerRule
            }
            var symbol  = ['equal','notequal','in','notin'],
                symbol2 = ['has','nothas'];
            for(var i=0;i<json.length;i++){
                var group = json[i];
                var groupid = group.groupid;
                var name = RsCore.cache.group.list[groupid];
                var rules = group.rule;
                var _html = [];
                for(var j =0;j<rules.length;j++){
                    var rule = rules[j];
                    if(rule.type=='ip'){
                        rule.symbol = symbol.indexOf(rule.symbol);
                    }else{
                        rule.symbol = symbol2.indexOf(rule.symbol);
                    }
                    _html.push(opt[rule.type](rule));
                }
                op.ruleBox(groupid,name,_html)
            }
            if(json.length){
                container.find('.js_no_data').hide();
            }else{
                container.find('.js_no_data').show();
            }

        }
    };

    return {
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