define(function(require) {
    var tpl = require('text!manage-policy.html');
    require('selectric');
    require('css!selectric');
    var mustache = require('mustache');
    var globalPolicy = [
                    'EB8AFFA5-0710-47e6-8F53-55CAE55E1915_1'
                ];

    return {
        container: undefined,
        render: function(container, paramStr) {
            this.container = container;
            var view = $(container);

            if (!paramStr) return false;
            var html = '',
                params = paramStr.split('&'),
                groupId = params[0],
                policyId = params[1],
                clientId = params[2],
                op;

            var viewType; //当前页面 [1:组策略 2:终端策略 3:策略设置]

            if (groupId) {
                html = mustache.render(tpl, {
                    group: true
                });
                view.append(html);
                viewType = 1;
                if (policyId) {
                    //修改组策略
                    view.find('.content-header>h1').text('修改组策略');
                    var name = RsCore.cache.group.list[groupId];
                    if (name) {
                        view.find('#selGroup').append('<option value="' + groupId + '">' + name + '</option>').prop('disabled',true);
                        RsCore.ajax('PType/getPType', {
                            id: policyId
                        }, function(data) {
                            if (data) {
                                view.find('#selPolicy').append('<option value="' + data.value + '">' + data.name + '</option>').prop('disabled',true).selectric('refresh');
                                RsCore.ajax('Policy/getPolicy', {
                                    grouptype: 1,
                                    objid: groupId,
                                    productid: policyId.split('_')[0],
                                    eid: RsCore.cache.group.eid,
                                    policytype: policyId.split('_')[1]
                                }, function(result) {
                                    if (result) {
                                        view.find('#txtDesp').val(result.description);
                                        require(['policy/' + data.value], function(policy) {
                                            view.find('#policyContent').off().empty();
                                            op = policy.render(view.find('#policyContent'), $.parseJSON(result.policyjson));
                                        }, function(err) {
                                            //requirejs加载模块失败,删除模块加载标记
                                            var failedId = err.requireModules && err.requireModules[0];
                                            requirejs.undef(failedId);
                                        });
                                    } else {
                                        bootbox.alert('当前策略不存在 !');
                                    }
                                });
                            } else {
                                bootbox.alert('对应产品不存在 !');
                            }
                        });
                    } else {
                        bootbox.alert('目标组不存在 !');
                    }

                } else {
                    //新增组策略
                    var name = RsCore.cache.group.list[groupId];
                    if (name) {
                        view.on('change', '#selPolicy', function() {
                            if ($(this).val() != -1) {
                                view.find('#policyContent').off().empty();
                                op = undefined; //清空保存按钮操作
                                var path = 'policy/' + $(this).val();
                                require([path], function(policy) {
                                    //异步调用具体策略模块
                                    op = policy.render(view.find('#policyContent'));

                                }, function(err) {
                                    // requirejs加载模块失败,删除模块加载标记
                                    var failedId = err.requireModules && err.requireModules[0];
                                    requirejs.undef(failedId);
                                    bootbox.alert('模块加载失败 !');
                                });
                            }
                        });
                        view.find('#selGroup').append('<option value="' + groupId + '">' + name + '</option>').prop('disabled',true);
                        RsCore.ajax('Policy/getDisplayProduct', {
                            objid: ''+groupId,
                            eid:''+RsCore.cache.group.eid,
                            groupType:Number(1)
                        }, function(data) {

                            var arr = [];
                            arr.push('<option value="-1">选择产品</option>');
                            if (data) {
                                $.each(data, function(i, obj) {
                                    arr.push('<option value="' + i + '">' + obj.NAME + '</option>');
                                });
                            }
                            view.find('#selPolicy').append(arr.join('')).selectric('refresh').prop('disabled',false).trigger('change');
                        });
                    }
                }
            } else if (clientId) {
                html = mustache.render(tpl, {
                    client: true
                });
                view.append(html);
                viewType = 2;
                if (policyId) {
                    //修改终端策略
                    view.find('.content-header>h1').text('修改终端策略');
                    RsCore.ajax('Group/getcomputerNameinfo', {
                        sguid: clientId
                    }, function(r) {
                        if (r) {
                            r = r[0];
                            view.find('#selClient').append('<option value="' + r.sguid + '">' + r.computername + '</option>').prop('disabled',true).selectric('refresh');
                        } else {
                            bootbox.alert('终端端不存在 !');
                        }
                    });
                    RsCore.ajax('PType/getPType', {
                        id: policyId
                    }, function(data) {
                        if (data) {
                            view.find('#selPolicy').append('<option value="' + data.value + '">' + data.name + '</option>').prop('disabled',true).selectric('refresh');
                            RsCore.ajax('Policy/getPolicy', {
                                grouptype: 2,
                                objid: clientId,
                                productid: policyId.split('_')[0],
                                policytype: policyId.split('_')[1],
                                eid: RsCore.cache.group.eid
                            }, function(result) {
                                if (result) {
                                    view.find('#txtDesp').val(result.description);
                                    require(['policy/' + data.value], function(policy) {
                                        view.find('#policyContent').off().empty();
                                        op = policy.render(view.find('#policyContent'), $.parseJSON(result.policyjson));
                                    }, function(err) {
                                        //requirejs加载模块失败,删除模块加载标记
                                        var failedId = err.requireModules && err.requireModules[0];
                                        requirejs.undef(failedId);
                                    });
                                } else {
                                    bootbox.alert('当前策略不存在 !');
                                }
                            });
                        } else {
                            bootbox.alert('对应产品不存在 !');
                        }
                    });

                } else {
                    //新增终端策略
                    view.on('change', '#selPolicy', function() {
                        if ($(this).val() != -1) {
                            view.find('#policyContent').off().empty();
                            op = undefined; //清空保存按钮操作
                            var path = 'policy/' + $(this).val();
                            require([path], function(policy) {
                                //异步调用具体策略模块
                                op = policy.render(view.find('#policyContent'));

                            }, function(err) {
                                // requirejs加载模块失败,删除模块加载标记
                                var failedId = err.requireModules && err.requireModules[0];
                                requirejs.undef(failedId);
                                bootbox.alert('模块加载失败 !');
                            });
                        }
                    });
                    RsCore.ajax('Group/getcomputerNameinfo', {
                        sguid: clientId
                    }, function(r) {
                        if (r) {
                            r = r[0];
                            view.find('#selClient').append('<option value="' + r.sguid + '">' + r.computername + '</option>').prop('disabled',true).selectric('refresh');
                        } else {
                            bootbox.alert('终端端不存在 !');
                        }
                    });
                    RsCore.ajax('Policy/getDisplayProduct', {
                        objid: clientId,
                        eid:RsCore.cache.group.eid,
                        groupType:2
                    }, function(data) {
                        var arr = [];
                        arr.push('<option value="-1">选择产品</option>');
                        if (data) {
                            $.each(data, function(i, obj) {
                                arr.push('<option value="' + i + '">' + obj.NAME + '</option>');
                            });
                        }
                        view.find('#selPolicy').append(arr.join('')).prop('disabled',false).selectric('refresh').trigger('change');
                    });
                }
            } else if (policyId) {
                if(globalPolicy.indexOf(policyId)>=0){
                    html = mustache.render(tpl, {
                        group: true,
                        global:true
                    });
                    view.append(html);
                    viewType = 3;
                    //组策略设置
                    view.find('.content-header>h1').text('全局策略设置');
                    //view.find('#txtDesp').val(result.description);
                    RsCore.ajax('PType/getPType', {
                        id: policyId
                    }, function(data) {
                        if (data) {
                            if(policyId=='EB8AFFA5-0710-47e6-8F53-55CAE55E1915_1'){
                                RsCore.ajax('/Policy/getAutoGroup',function(result){
                                    result = result || [];
                                    require(['policy/' + policyId], function(policy) {
                                        view.find('#policyContent').off().empty();
                                        op = policy.render(view.find('#policyContent'), result);
                                    }, function(err) {
                                        //requirejs加载模块失败,删除模块加载标记
                                        var failedId = err.requireModules && err.requireModules[0];
                                        requirejs.undef(failedId);
                                    });
                                })    
                            }else{
                                RsCore.ajax('Policy/getPolicy',{
                                    grouptype: 2,
                                    objid: RsCore.cache.group.eid,
                                    productid: policyId.split('_')[0],
                                    policytype: policyId.split('_')[1],
                                    eid: RsCore.cache.group.eid
                                },function(result){
                                    result = result || [];
                                    require(['policy/' + policyId], function(policy) {
                                        view.find('#policyContent').off().empty();
                                        op = policy.render(view.find('#policyContent'), result);
                                    }, function(err) {
                                        //requirejs加载模块失败,删除模块加载标记
                                        var failedId = err.requireModules && err.requireModules[0];
                                        requirejs.undef(failedId);
                                    });
                                });    
                            }
                        } else {
                            bootbox.alert('对应产品不存在 !');
                        }
                    });         
                }else{
                    html = mustache.render(tpl, {
                        group: true
                    });
                    view.append(html);
                    viewType = 3;
                    //组策略设置
                    view.find('.content-header>h1').text('组策略设置');
                    var arr = [];
                    $.each(RsCore.cache.group.list, function(k, v) {
                        if (k != 1)
                            arr.push('<option value="' + k + '">' + v + '</option>');
                    });
                    view.find('#selGroup').append(arr.join());
                    RsCore.ajax('PType/getPType', {
                        id: policyId
                    }, function(data) {
                        if (data) {
                            view.find('#selPolicy').append('<option value="' + data.value + '">' + data.name + '</option>').prop('disabled',true).selectric('refresh');
                            //绑定事件
                            data.value = policyId;
                            view.on('change', '#selGroup', function() {
                                RsCore.ajax('Policy/getPolicy', {
                                    grouptype: 1,
                                    objid: $(this).val(),
                                    productid: policyId.split('_')[0],
                                    policytype: policyId.split('_')[1],
                                    eid: RsCore.cache.group.eid
                                }, function(result) {
                                    if (result) {
                                        view.find('#txtDesp').val(result.description);
                                        require(['policy/' + data.value], function(policy) {
                                            view.find('#policyContent').off().empty();
                                            op = policy.render(view.find('#policyContent'), $.parseJSON(result.policyjson));
                                        }, function(err) {
                                            //requirejs加载模块失败,删除模块加载标记
                                            var failedId = err.requireModules && err.requireModules[0];
                                            requirejs.undef(failedId);
                                        });
                                    } else {
                                        //bootbox.alert('当前组未设置此策略 !');
                                        RsCore.msg.warn('组策略设置', '当前组未设置此策略');
                                        view.find('#txtDesp').val('');
                                        require(['policy/' + data.value], function(policy) {
                                            view.find('#policyContent').off().empty();
                                            op = policy.render(view.find('#policyContent'),{});
                                        }, function(err) {
                                            //requirejs加载模块失败,删除模块加载标记
                                            var failedId = err.requireModules && err.requireModules[0];
                                            requirejs.undef(failedId);
                                        });
                                    }
                                });
                            });
                            view.find('#selGroup').trigger('change');

                        } else {
                            bootbox.alert('对应产品不存在 !');
                        }
                    });
                }
            }

            view.find('select').selectric({
                inheritOriginalWidth: true
            });

            view.on('click', '#btnSavePolicy', function() {
                if (!op) return;
                if(op.valida && !op.valida() ){
                    RsCore.msg.warn('组策略设置', '数据错误');
                    return false;
                }
                $(this).button('loading');
                var json = op.toJson(view.find('#policyContent')),
                    eid = RsCore.cache.group.eid,
                    gid = view.find('#selGroup').val(),
                    cid = view.find('#selClient').val();
                var _type ;
                var _policy = policyId || view.find('#selPolicy').val();
                if(globalPolicy.indexOf(_policy)>=0){
                    _type = 1;
                }else{
                    _type = 0;
                    if (!gid && !cid && !eid) return;
                }
                
                RsCore.ajax('Policy/editPolicy', {
                    'eid': eid ? eid : '', //企业id
                    'objid': cid ? cid : (gid ? gid : (eid ? eid : '')), //企业id/组id/终端sguid
                    'productid': _policy.split('_')[0], //产品id
                    'productname': view.find('#selPolicy option:selected').text() || '', //产品名称
                    'grouptype': cid ? 2 : 1, //组类型(策略类型)
                    'policytype': _policy.split('_')[1], //策略小类型
                    'desp': view.find('#txtDesp').val() || '', //描述
                    'policyinfo': JSON.stringify(json),
                    'type': _type
                }, function(data) {
                    view.find('#btnSavePolicy').button('reset');
                    RsCore.msg.success('策略保存成功 !');
                    if (viewType < 3) window.history.back();
                },function(){
                    view.find('#btnSavePolicy').button('reset');
                });
                //console.log(JSON.stringify(json));
                //$('#txtJSONTest').val(JSON.stringify(json));
            });

            view.on('click', '#btnReturn', function() {
                window.history.back();
            });
        },
        destroy: function() {
            $(this.container).find('select').selectric('destroy');
            $(this.container).find('#policyContent').off().empty();
            $(this.container).off().empty();
            console.log('destroy manage-policy page');
        }
    };
});