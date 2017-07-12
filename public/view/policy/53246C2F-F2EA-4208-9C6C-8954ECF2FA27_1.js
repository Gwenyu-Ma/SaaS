define(function(require) {
    var tpl = require('text!policy/53246C2F-F2EA-4208-9C6C-8954ECF2FA27_1.html');
    require('table');
    require('css!table');
    //var mustache = require('mustache');
    //var xmlTpl = require('text!policy/D49170C0-B076-4795-B079-0F97560485AF_1.xml');

    var op = {
        view:null,
        ruleList: [{
            "ID": "{057F52B5-3E20-41C3-9E01-97CA75B4A006}",
            "Name": "Adobe Flash Player远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{47B91BDA-4F47-4D50-873D-51A24FD8F488}",
            "Name": "浏览器攻击：Windows ANI动态光标远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{CC3F625B-8163-4179-8DC5-36D1F0964FDF}",
            "Name": "浏览器攻击：Microsoft Office 远程代码执行漏洞 II",
            "bInuse": "1"
        }, {
            "ID": "{02D5F181-C256-4DF5-85FF-D4BE047871FB}",
            "Name": "浏览器攻击：RealPlayer远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{0045B70F-A1B8-44BF-BC1A-0F363AF310DA}",
            "Name": "浏览器攻击：RealPlayer 远程执行代码漏洞-变种II",
            "bInuse": "1"
        }, {
            "ID": "{FAD02235-563A-4AF2-B78F-6B7F9D70A6A3}",
            "Name": "浏览器攻击：暴风影音II ActiveX远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{B38C7840-7082-46C6-B39A-59AB98A4CFAC}",
            "Name": "浏览器攻击：迅雷看看 ActiveX远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{69858E60-400A-4C94-8A79-4E49EB198CF9}",
            "Name": "浏览器攻击：联众世界ActiveX 远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{680A81EE-CBEA-4613-B2A5-7A0FBB2BF619}",
            "Name": "浏览器攻击：Microsoft MDAC 远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{BE70EAA7-5CFF-4B0B-9A52-8523F11F0E7A}",
            "Name": "浏览器攻击：Microsoft MDAC 远程代码执行漏洞 -变种II",
            "bInuse": "1"
        }, {
            "ID": "{93D67BC9-4D16-453B-97FB-C540E72A957E}",
            "Name": "浏览器攻击：新浪ActiveX远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{8E1F9884-6179-4F27-AB9E-9868C925952D}",
            "Name": "浏览器攻击：PPLIVE ActiveX远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{44CE7DAC-0199-49BC-A6C3-9D10C7343597}",
            "Name": "浏览器攻击：百度搜霸ActiveX 远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{ADE3EEC5-8BC3-4EE6-9980-0D32BC2060E9}",
            "Name": "浏览器攻击：Qvod播放器 ActiveX 远处代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{F6222637-5A1D-4CB8-B14B-37F5BD1DACB7}",
            "Name": "浏览器攻击：Opera浏览器远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{D56CD9C0-DD56-41ED-93EB-3EB217E63CBB}",
            "Name": "浏览器攻击：JetAudio播发器ActiveX远处代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{C72A0D37-F7DD-4C9A-A342-CD5A3601BA8E}",
            "Name": "浏览器攻击：Windows  Media Player远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{F3E4CE22-17F4-4661-8658-ED73A756CD01}",
            "Name": "浏览器攻击：微软Works 文件转换器远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{50293A8A-6F33-4302-A726-E1ECF3A2F371}",
            "Name": "浏览器攻击：MPEG-2视频远程代码执行漏洞III",
            "bInuse": "1"
        }, {
            "ID": "{03750E7C-563B-4113-ACD8-35686D4AE980}",
            "Name": "浏览器攻击：Yahoo! ActiveX远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{825C33FD-6AA7-4BDC-8CC4-953A73E40914}",
            "Name": "浏览器攻击：超星阅读器ActiveX远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{CDC1FE21-9476-4D91-9C1C-0BE9E79A1B3A}",
            "Name": "浏览器攻击：Windows Media Encoder 9 ActiveX远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{00D5DAD8-3401-48E9-BE34-0A76DC64F1B2}",
            "Name": "浏览器攻击：Windows Media Encoder 9 ActiveX远程执行代码漏洞  变种II",
            "bInuse": "1"
        }, {
            "ID": "{5D9417F9-03A1-4C86-A59F-6B613D927B4B}",
            "Name": "浏览器攻击：Windows Media Encoder 9 ActiveX远程执行代码漏洞  变种III",
            "bInuse": "1"
        }, {
            "ID": "{4DBCB58F-134F-47D1-A95F-74615F76E638}",
            "Name": "浏览器攻击：JPEG图片 (GDI+)远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{B058A9F3-4ED0-4473-99BA-8DC6D3DE02F1}",
            "Name": "浏览器攻击：大规模爆发性网马群 I",
            "bInuse": "1"
        }, {
            "ID": "{BA4FAF06-41FB-41F1-80AB-7F1A9B343E85}",
            "Name": "浏览器攻击：大规模爆发性网马群 II",
            "bInuse": "1"
        }, {
            "ID": "{1CC54BAC-3442-4916-9A7A-0159A6A73E2A}",
            "Name": "浏览器攻击：大规模爆发性网马群 III",
            "bInuse": "1"
        }, {
            "ID": "{57ED89C9-A7FF-4C47-A33F-DFE2B824114B}",
            "Name": "浏览器攻击：大规模爆发性网马群 IV",
            "bInuse": "1"
        }, {
            "ID": "{EE69C949-A190-4EB3-A8C3-CD4B99666626}",
            "Name": "浏览器攻击：大规模爆发性网马群 V",
            "bInuse": "1"
        }, {
            "ID": "{FBCF3330-F1EE-4742-A470-D882B11D6139}",
            "Name": "浏览器攻击：大规模爆发性网马群 VI",
            "bInuse": "1"
        }, {
            "ID": "{3A8111C1-289D-4CA4-8F67-A3B6C3F73F73}",
            "Name": "浏览器攻击：大规模爆发性网马群 VII",
            "bInuse": "1"
        }, {
            "ID": "{CCBF3CEB-51A8-4370-9B24-C309EBE9BA27}",
            "Name": "浏览器攻击：大规模爆发性网马群 VIII",
            "bInuse": "1"
        }, {
            "ID": "{A77A2012-FABE-4666-AACB-AE783A85424A}",
            "Name": "浏览器攻击：大规模爆发性网马群 IX",
            "bInuse": "1"
        }, {
            "ID": "{7FCB67BF-AF21-4581-95B7-1570571C42DA}",
            "Name": "浏览器攻击：大规模爆发性网马群 X",
            "bInuse": "1"
        }, {
            "ID": "{9DF441DA-672A-4D04-BA00-2114E93F21EA}",
            "Name": "浏览器攻击：大规模爆发性网马群 XI",
            "bInuse": "1"
        }, {
            "ID": "{D7488E68-76F5-4FE6-8BCA-179A71983EF7}",
            "Name": "浏览器攻击：大规模爆发性网马群 XII",
            "bInuse": "1"
        }, {
            "ID": "{45763441-A05C-403C-B910-DF8DAFF5DAF2}",
            "Name": "浏览器攻击：大规模爆发性网马群 XIII",
            "bInuse": "1"
        }, {
            "ID": "{6E3BE8E6-6C28-41AB-A78A-8447BC27A5A8}",
            "Name": "浏览器攻击：微软GDI+远程代码执行漏洞(MS08-052)",
            "bInuse": "1"
        }, {
            "ID": "{0ACBA7DD-AA40-46A8-9E7A-532C5818BF4A}",
            "Name": "浏览器攻击：微软GDI+远程代码执行漏洞(MS08-021)",
            "bInuse": "1"
        }, {
            "ID": "{077F4889-B77F-45A5-AEEE-6562EC7B4BCA}",
            "Name": "浏览器攻击：Adobe Flash Player远程代码执行漏洞-变种II",
            "bInuse": "1"
        }, {
            "ID": "{CCBF3CEB-51A8-4370-9B24-C309EBE9BA27}",
            "Name": "浏览器攻击：IE7.0 初始化内存远程执行代码漏洞(MS09-002)",
            "bInuse": "1"
        }, {
            "ID": "{997CB239-ABE9-4F57-848F-8F10F3C33877}",
            "Name": "浏览器攻击：DirectShow 远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{D8F77733-8684-4723-9D0C-4D6177153688}",
            "Name": "浏览器攻击：MPEG-2视频远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{7994A783-1CBB-48C2-9928-0EDAD45913BD}",
            "Name": "浏览器攻击：MPEG-2视频远程代码执行漏洞II",
            "bInuse": "1"
        }, {
            "ID": "{95D87BDA-F0E1-4E9B-9BD3-E546D03430DA}",
            "Name": "远程溢出：SMB共享服务攻击",
            "bInuse": "1"
        }, {
            "ID": "{E5D4F304-B4BB-4A89-B88D-42D378B9D6BD}",
            "Name": "远程溢出： Serv-U SITE CHMOD 命令超长文件名漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{C5959B23-FFC9-4AB5-9821-5290D236904E}",
            "Name": "远程溢出：Serv-U MDTM 命令漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{1929AC3F-6F95-4E97-87AB-2F8D12D0335F}",
            "Name": "远程溢出：Microsoft IIS .IDA / .IDQ ISAPI漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{D88A09AB-D097-4EBD-87FB-058D7E0D1221}",
            "Name": "远程溢出：Real Server 漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{51BEA12A-94D9-4196-9DB6-1EAC9C441D4F}",
            "Name": "远程溢出：IIS 5.0 .printer 漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{D20E1C54-BF48-44B4-9346-80399C74F48B}",
            "Name": "远程溢出：Flashget FTP PWD漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{E8DD8333-49E1-46CF-8E2D-CFE3696403A5}",
            "Name": "远程溢出：Apache Tomcat Unicode目录遍历漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{04900DAF-F3DB-4DF4-B9DB-2BD66E639E9B}",
            "Name": "远程溢出：TFTP Server for Windows ST 漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{E84AD5B3-E1D4-43AF-B1AE-1C8EA71B2BFC}",
            "Name": "远程溢出：RPC消息队列服务漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{8A4CECCC-EAA1-4FCA-9D3C-080A85B9EDDD}",
            "Name": "远程溢出：Windows LSA服务漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{93781480-58A9-4C0B-92C8-1834726C5F13}",
            "Name": "远程溢出：Workstation服务漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{501A1990-FCA1-4D72-8E7D-308DD38A88D9}",
            "Name": "远程溢出：Quick TFTP漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{3941A4DA-1D82-44A8-B2EB-7EEB6F519BC9}",
            "Name": "远程溢出：Quicktime 播放器漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{7DF03D54-7681-439B-BDEB-458EAFAA2F08}",
            "Name": "远程溢出：IIS 网站内容进行访问绕过漏洞",
            "bInuse": "1"
        }, {
            "ID": "{0A96642E-3373-4EFE-8EFB-FD045F83F001}",
            "Name": "远程溢出：SMB共享服务攻击（MS08-067）",
            "bInuse": "1"
        }, {
            "ID": "{3051AE79-38D2-4B9C-8A6F-76F3C68248A4}",
            "Name": "远程溢出：微软RPC DCOM接口超长主机名漏洞",
            "bInuse": "1"
        }, {
            "ID": "{ADE353C6-64D6-4831-A15C-FE59BBA27365}",
            "Name": "远程溢出：SMB共享服务攻击（MS08-067）变种",
            "bInuse": "1"
        }, {
            "ID": "{8BA3EEAB-73EE-4AF7-A3F4-C02BD7CFEEFB}",
            "Name": "远程溢出：Microsoft IIS FTP漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{3B56DCA3-7514-493D-8DC1-BF48F07E948A}",
            "Name": "蠕虫传播：2003蠕虫王攻击",
            "bInuse": "1"
        }, {
            "ID": "{47024A38-AE7F-4E95-A39F-6148DA854F87}",
            "Name": "蠕虫传播：Master Paradise 蠕虫木马(默认端口)",
            "bInuse": "1"
        }, {
            "ID": "{AA507606-BE58-48DE-A339-D1E60D986698}",
            "Name": "蠕虫传播：红色代码蠕虫攻击",
            "bInuse": "1"
        }, {
            "ID": "{576E189A-3CA7-4DBF-9665-9ABCF8DA182D}",
            "Name": "僵尸网络：傀儡僵尸",
            "bInuse": "1"
        }, {
            "ID": "{036384A5-E8EF-47BD-A6F5-8F314623A8AA}",
            "Name": "僵尸网络：Netbot Attacker",
            "bInuse": "1"
        }, {
            "ID": "{722D0477-A045-4900-8A9E-E3410BA8B635}",
            "Name": "僵尸网络：风云压力测试",
            "bInuse": "1"
        }, {
            "ID": "{D3A16FE9-56D0-4462-A5FF-F6626227E49A}",
            "Name": "木马后门：灰鸽子(命令者)",
            "bInuse": "1"
        }, {
            "ID": "{16DF145A-7108-40EE-BFB1-77EABE13E3EA}",
            "Name": "木马后门：灰鸽子(凤凰ABC)",
            "bInuse": "1"
        }, {
            "ID": "{7B1B6886-DCA1-45C7-BACB-726ABC6208B5}",
            "Name": "木马后门：PCSHARE2008",
            "bInuse": "1"
        }, {
            "ID": "{0A1BF819-C266-4E18-8BFD-7345B17BFE77}",
            "Name": "木马后门：幻影远程控制",
            "bInuse": "1"
        }, {
            "ID": "{70F0F07B-239F-4E6B-A553-7365909CEFCF}",
            "Name": "木马后门：黑洞远程控制",
            "bInuse": "1"
        }, {
            "ID": "{031513DC-4E28-4377-8F97-17D6A2ABA944}",
            "Name": "木马后门：灰鸽子",
            "bInuse": "1"
        }, {
            "ID": "{A948935B-60CF-45FC-8525-80E692CF8A5E}",
            "Name": "浏览器攻击：Real Player远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{4698CC40-F0C9-4439-930A-7D1518652D94}",
            "Name": "浏览器攻击：中国游戏中心游戏大厅ActiveX 远程执行代码漏洞",
            "bInuse": "1"
        }, {
            "ID": "{53F163D7-2291-471B-80AD-5311DCCF982C}",
            "Name": "浏览器攻击：Microsoft Office 远程代码执行漏洞",
            "bInuse": "1"
        }, {
            "ID": "{76BB5E58-A146-4276-A93E-B9C9117E4933}",
            "Name": "浏览器攻击：大规模爆发性网马群 XI",
            "bInuse": "1"
        }, {
            "ID": "{9D22EEBF-E190-45E4-86F7-67E5879FFCE1}",
            "Name": "木马后门：黑鹰远控",
            "bInuse": "1"
        }, {
            "ID": "{CB04C38B-877D-48B3-B7CF-038932199101}",
            "Name": "木马后门：Gh0st RAT远程控制",
            "bInuse": "1"
        }, {
            "ID": "{455C86F0-154A-452F-B548-949732C46047}",
            "Name": "木马后门：SRAT远程控制",
            "bInuse": "1"
        }, {
            "ID": "{07A0736B-51E7-4322-84D4-3824F6B8B85A}",
            "Name": "木马后门：上兴远程控制",
            "bInuse": "1"
        }, {
            "ID": "{2162A6BD-38F5-451B-81E1-993EE5552177}",
            "Name": "木马后门：DRAT远程控制",
            "bInuse": "1"
        }, {
            "ID": "{68C740F1-D6B1-4590-9BB2-D95A4B5D9411}",
            "Name": "远程溢出：WINS 漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{036C845E-1800-40B4-A5C0-1AD7A023E693}",
            "Name": "远程溢出：SMB 漏洞攻击",
            "bInuse": "1"
        }, {
            "ID": "{48B41BF4-375B-4056-8188-B808B9999AB5}",
            "Name": "远程溢出：SMB 漏洞攻击II",
            "bInuse": "1"
        }],
        init: function(container, policy) {
            this.view = container;
            // 绑定事件
            this.bindEvent(container);
            this.validationEvent(container);
            // 初始化策略内容
            if (policy) {
                // 策略初始化赋值
                //console.log(policy);
                this.toHtml(container, policy);
            }else{
                this._initRsIpTable(container);
            }
        },
        bindEvent: function(container) {
            // 锁定图标
            container.on('click', 'i.lock', function() {
                $(this).toggleClass('enableLock');
                return false;
            });


            //添加白名单
            container.on('click', '#whiteList_add', function() {
                var url = container.find('#whiteList_txt').val();
                var exist = false;
                if ($.trim(url) == '') {
                    container.find('#whiteList_txt').tooltip({
                        title: '内容不能为空',
                        trigger: 'manual'
                    }).tooltip('show');
                    return false;
                }else{
                    container.find('#whiteList_txt').tooltip('hide');
                }
                container.find('#WhiteUrlList tr').find('>td:first').each(function() {
                    if ($(this).attr('title') == url) {
                        exist = true;
                        container.find('#whiteList_txt').tooltip({
                            title: '已存在',
                            trigger: 'manual'
                        }).tooltip('show');
                    }
                });
                !exist && container.find('#WhiteUrlList').append('<tr><td title="' + url + '">' + url + '<td width="50"><a href="javascript:;" class="whiteList_btnRemove">删除</a>');
            })
            //添加广告规则
            container.on('click', '#adUrlList_add', function() {
                var url = container.find('#adUrlList_txt').val();
                var exist = false;
                if ($.trim(url) == '') {
                    container.find('#adUrlList_txt').tooltip({
                        title: '内容不能为空',
                        trigger: 'manual'
                    }).tooltip('show');
                    return false;
                }
                container.find('#adUrlList tr').find('>td:first').each(function() {
                    if ($(this).attr('title') == url) {
                        exist = true;
                        container.find('#adUrlList_txt').tooltip({
                            title: '已存在',
                            trigger: 'manual'
                        }).tooltip('show');
                    }
                });
                !exist && container.find('#adUrlList').append('<tr><td title="' + url + '">' + url + '<td width="50"><a href="javascript:;" class="whiteList_btnRemove">删除</a>');
            })

            //删除白名单
            container.on('click', '.whiteList_btnRemove', function() {
                if (!$(this).prop('disabled')) {
                    $(this).closest('tr').remove();
                }
            })

            //是否开启白名单
            container.on('change', '#whiteList_WhiteUrlStatus', function() {
                var that = $(this);
                var dd = that.closest('dt').next('dd');
                if (that.prop('checked')) {
                    dd.find('input,button,.whiteList_btnRemove').prop('disabled', false);
                    dd.find('.whiteList_btnRemove').css('visibility', 'visible');
                } else {
                    dd.find('input,button,.whiteList_btnRemove').prop('disabled', true);
                    dd.find('.whiteList_btnRemove').css('visibility', 'hidden');
                }
            })

        },
        _initRsIpTable: function(container, data) {
            //防黑客攻击
            var data = data || this.ruleList;
            var total_RsIpRule = 0,
                RsIpRule_noOpen = 0;
            container.find('#RsIpRuleList').bootstrapTable({
                columns: [{
                    field: 'Name',
                    title: '防护项',
                    align: 'left',
                    sortable: false
                }, {
                    field: 'bInuse',
                    title: '操作',
                    align: 'center',
                    sortable: false,
                    formatter: function(value, row, index) {
                        total_RsIpRule++;
                        if (value == 1) {
                            return '<input type="checkbox" objid="' + row.ID + '" objname="' + row.Name + '" checked>';
                        } else {
                            RsIpRule_noOpen++;
                            return '<input type="checkbox"  objid="' + row.ID + '" objname="' + row.Name + '">';
                        }
                    }
                }],
                data: data,
                height: 200
            });

            container.find('#js_totle_RsIpRuleList').text(total_RsIpRule);
            container.find('#js_RsIpRuleList_noOpen').text(RsIpRule_noOpen);
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
            var product = {};

            product.NetProtect = {};

            /*公共设置 start*/
            product.NetProtect.FwStatus = {};
            product.NetProtect.FwStatus['@attributes'] = {
                lock: Number(container.find('#FwStatus_lock').hasClass('enableLock'))
            }
            product.NetProtect.FwStatus['@value'] = Number(container.find('#pub_FwStatus').prop('checked'));

            product.NetProtect.WhiteUrlStatus = {};
            product.NetProtect.WhiteUrlStatus['@attributes'] = {
                lock: Number(container.find('#whiteList_lock').hasClass('enableLock'))
            }
            product.NetProtect.WhiteUrlStatus['@value'] = Number(container.find('#whiteList_WhiteUrlStatus').prop('checked'));

            product.NetProtect.AntiEvilUrl = {};
            product.NetProtect.AntiEvilUrl['@attributes'] = {
                lock: Number(container.find('#AntiEvilUrl_lock').hasClass('enableLock'))
            }
            product.NetProtect.AntiEvilUrl.MonStatus = {};
            product.NetProtect.AntiEvilUrl.MonStatus['@attributes'] = {
                lock: Number(container.find('#AntiEvilUrl_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiEvilUrl.MonStatus['@value'] = Number(container.find('#AntiEvilUrl_MonStatus').prop('checked'));
            product.NetProtect.AntiEvilUrl.LogStatus = {};
            product.NetProtect.AntiEvilUrl.LogStatus['@attributes'] = {
                lock: Number(container.find('#AntiEvilUrl_lock_LogStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiEvilUrl.LogStatus['@value'] = Number(container.find('#AntiEvilUrl_LogStatus').prop('checked'));

            product.NetProtect.AntiFishUrl = {};
            product.NetProtect.AntiFishUrl['@attributes'] = {
                lock: Number(container.find('#AntiFishUrl_lock').hasClass('enableLock'))
            }
            product.NetProtect.AntiFishUrl.MonStatus = {};
            product.NetProtect.AntiFishUrl.MonStatus['@attributes'] = {
                lock: Number(container.find('#AntiFishUrl_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiFishUrl.MonStatus['@value'] = Number(container.find('#AntiFishUrl_MonStatus').prop('checked'));
            product.NetProtect.AntiFishUrl.LogStatus = {};
            product.NetProtect.AntiFishUrl.LogStatus['@attributes'] = {
                lock: Number(container.find('#AntiFishUrl_lock_LogStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiFishUrl.LogStatus['@value'] = Number(container.find('#AntiFishUrl_LogStatus').prop('checked'));

            product.NetProtect.AntiEvilDown = {};
            product.NetProtect.AntiEvilDown['@attributes'] = {
                lock: Number(container.find('#AntiEvilDown_lock').hasClass('enableLock'))
            }
            product.NetProtect.AntiEvilDown.MonStatus = {};
            product.NetProtect.AntiEvilDown.MonStatus['@attributes'] = {
                lock: Number(container.find('#AntiEvilDown_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiEvilDown.MonStatus['@value'] = Number(container.find('#AntiEvilDown_MonStatus').prop('checked'));
            product.NetProtect.AntiEvilDown.LogStatus = {};
            product.NetProtect.AntiEvilDown.LogStatus['@attributes'] = {
                lock: Number(container.find('#AntiEvilDown_lock_LogStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiEvilDown.LogStatus['@value'] = Number(container.find('#AntiEvilDown_LogStatus').prop('checked'));

            product.NetProtect.SearchUrlProtect = {};
            product.NetProtect.SearchUrlProtect['@attributes'] = {
                lock: Number(container.find('#SearchUrlProtect_lock').hasClass('enableLock'))
            }
            product.NetProtect.SearchUrlProtect.MonStatus = {};
            product.NetProtect.SearchUrlProtect.MonStatus['@attributes'] = {
                lock: Number(container.find('#SearchUrlProtect_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.SearchUrlProtect.MonStatus['@value'] = Number(container.find('#SearchUrlProtect_MonStatus').prop('checked'));
            product.NetProtect.SearchUrlProtect.LogStatus = {};
            product.NetProtect.SearchUrlProtect.LogStatus['@attributes'] = {
                lock: Number(container.find('#SearchUrlProtect_lock_LogStatus').hasClass('enableLock'))
            }
            product.NetProtect.SearchUrlProtect.LogStatus['@value'] = Number(container.find('#SearchUrlProtect_LogStatus').prop('checked'));

            product.NetProtect.AntiXss = {};
            product.NetProtect.AntiXss['@attributes'] = {
                lock: Number(container.find('#AntiXss_lock').hasClass('enableLock'))
            }
            product.NetProtect.AntiXss.MonStatus = {};
            product.NetProtect.AntiXss.MonStatus['@attributes'] = {
                lock: Number(container.find('#AntiXss_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiXss.MonStatus['@value'] = Number(container.find('#AntiXss_MonStatus').prop('checked'));
            product.NetProtect.AntiXss.LogStatus = {};
            product.NetProtect.AntiXss.LogStatus['@attributes'] = {
                lock: Number(container.find('#AntiXss_lock_LogStatus').hasClass('enableLock'))
            }
            product.NetProtect.AntiXss.LogStatus['@value'] = Number(container.find('#AntiXss_LogStatus').prop('checked'));

            product.NetProtect.AdFilter = {};
            product.NetProtect.AdFilter['@attributes'] = {
                lock: Number(container.find('#AdFilter_lock').hasClass('enableLock'))
            }
            product.NetProtect.AdFilter.MonStatus = {};
            product.NetProtect.AdFilter.MonStatus['@attributes'] = {
                lock: Number(container.find('#AdFilter_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.AdFilter.MonStatus['@value'] = Number(container.find('#AdFilter_MonStatus').prop('checked'));
            product.NetProtect.AdFilter.LogStatus = {};
            product.NetProtect.AdFilter.LogStatus['@attributes'] = {
                lock: Number(container.find('#AdFilter_lock_logStatus').hasClass('enableLock'))
            }
            product.NetProtect.AdFilter.LogStatus['@value'] = Number(container.find('#AdFilter_LogStatus').prop('checked'));
            product.NetProtect.AdFilter.RuleList = {};
            product.NetProtect.AdFilter.RuleList['@attributes'] = { lock : 0}
            product.NetProtect.AdFilter.RuleList.Rule = [];
            container.find('#adUrlList tbody tr').each(function(i,item){
                var Rule = {};
                var td = $(item).find('td:eq(0)').attr('title');
                Rule['@value'] = td;
                product.NetProtect.AdFilter.RuleList.Rule.push(Rule);
            })

            product.NetProtect.RsIpRule = {};
            product.NetProtect.RsIpRule['@attributes'] = {
                lock: Number(container.find('#rsIpList_lock').hasClass('enableLock'))
            }
            product.NetProtect.RsIpRule.MonStatus = {};
            product.NetProtect.RsIpRule.MonStatus['@attributes'] = {
                lock: Number(container.find('#rsIpList_lock_MonStatus').hasClass('enableLock'))
            }
            product.NetProtect.RsIpRule.MonStatus['@value'] = Number(container.find('#rsIpList_MonStatus').prop('checked'));
            product.NetProtect.RsIpRule.LogStatus = {};
            product.NetProtect.RsIpRule.LogStatus['@attributes'] = {
                lock: Number(container.find('#rsIpList_lock_LogStatus').hasClass('enableLock'))
            }
            product.NetProtect.RsIpRule.LogStatus['@value'] = Number(container.find('#rsIpList_LogStatus').prop('checked'));
            product.NetProtect.RsIpRule.AlertStatus = {};
            product.NetProtect.RsIpRule.AlertStatus['@attributes'] = {
                lock: Number(container.find('#rsIpList_lock_AlertStatus').hasClass('enableLock'))
            }
            product.NetProtect.RsIpRule.AlertStatus['@value'] = Number(container.find('#rsIpList_AlertStatus').prop('checked'));
            product.NetProtect.RsIpRule.BreakTimes = {};
            product.NetProtect.RsIpRule.BreakTimes['@attributes'] = {
                lock: Number(container.find('#rsIpList_lock_BreakTimes').hasClass('enableLock'))
            }
            product.NetProtect.RsIpRule.BreakTimes['@value'] = container.find('#rsIpList_BreakTimes').val();
            /*公共设置 end*/

            /*白名单 start*/
            product.WhiteUrlList = {};
            product.WhiteUrlList['@attributes'] = {
                lock: 0
            };
            product.WhiteUrlList.Rule = [];
            var WhiteUrlList = container.find('#WhiteUrlList tr').find('>td:first');
            for (var i = 0; i < WhiteUrlList.length; i++) {
                var Rule = {}
                Rule['@attributes'] = {
                    id: $(WhiteUrlList[i]).attr('title')
                };
                product.WhiteUrlList.Rule.push(Rule);
            }
            /*白名单 end*/

            /*防黑客攻击 start*/
            product.RsIpRuleList = {};
            product.RsIpRuleList['@attributes'] = {
                lock: 0
            };
            product.RsIpRuleList.Rule = [];
            var RsIpRuleList = container.find('#RsIpRuleList input[type=checkbox]');
            for (var i = 0; i < RsIpRuleList.length; i++) {
                var Rule = {}
                Rule['@attributes'] = {
                    id: $(RsIpRuleList[i]).attr('objid'),
                    lock: 0
                };
                Rule.Status = {};
                Rule.Status['@value'] = Number($(RsIpRuleList[i]).prop('checked'));
                Rule.Name = {};
                Rule.Name['@value'] = $(RsIpRuleList[i]).attr('objname');
                product.RsIpRuleList.Rule.push(Rule);
            }
            /*防黑客攻击 end*/
            var json = {
                product: product
            };
            return json;

        },

        toHtml: function(container, json) {
            var opRadio = function(name, value) {
                container.find(':radio[name=' + name + '][value=' + value + ']').prop('checked', true)
            };
            var opCheck = function(id, status) {
                if (status == 1) {
                    return container.find(id).prop('checked', true);
                } else {
                    return container.find(id).prop('checked', false);
                }
            };
            var opLock = function(id, status) {
                if (status == 1) {
                    container.find(id).addClass('enableLock');
                } else {
                    container.find(id).removeClass('enableLock');
                }
            };


            
            var product = json.product;
            if (!product) {
                this._initRsIpTable(container);
                return false;
            }

            var NetProtect = product.NetProtect;
            var FwStatus = NetProtect.FwStatus;
            opLock('#FwStatus_lock', FwStatus['@attributes'].lock);
            opCheck('#pub_FwStatus', FwStatus['@value']);

            var WhiteUrlStatus = NetProtect.WhiteUrlStatus;
            opLock('#whiteList_lock', WhiteUrlStatus['@attributes'].lock);
            opCheck('#whiteList_WhiteUrlStatus', WhiteUrlStatus['@value']);            

            var AntiEvilUrl = NetProtect.AntiEvilUrl;
            opLock('#AntiEvilUrl_lock', AntiEvilUrl['@attributes'].lock);
            opLock('#AntiEvilUrl_lock_MonStatus', AntiEvilUrl.MonStatus['@attributes'].lock);
            opCheck('#AntiEvilUrl_MonStatus', AntiEvilUrl.MonStatus['@value']);
            opLock('#AntiEvilUrl_lock_LogStatus', AntiEvilUrl.LogStatus['@attributes'].lock);
            opCheck('#AntiEvilUrl_LogStatus', AntiEvilUrl.LogStatus['@value']);

            var AntiFishUrl = NetProtect.AntiFishUrl;
            opLock('#AntiFishUrl_lock', AntiFishUrl['@attributes'].lock);
            opLock('#AntiFishUrl_lock_MonStatus', AntiFishUrl.MonStatus['@attributes'].lock);
            opCheck('#AntiFishUrl_MonStatus', AntiFishUrl.MonStatus['@value']);
            opLock('#AntiFishUrl_lock_LogStatus', AntiFishUrl.LogStatus['@attributes'].lock);
            opCheck('#AntiFishUrl_LogStatus', AntiFishUrl.LogStatus['@value']);

            var AntiEvilDown = NetProtect.AntiEvilDown;
            opLock('#AntiEvilDown_lock', AntiEvilDown['@attributes'].lock);
            opLock('#AntiEvilDown_lock_MonStatus', AntiEvilDown.MonStatus['@attributes'].lock);
            opCheck('#AntiEvilDown_MonStatus', AntiEvilDown.MonStatus['@value']);
            opLock('#AntiEvilDown_lock_LogStatus', AntiEvilDown.LogStatus['@attributes'].lock);
            opCheck('#AntiEvilDown_LogStatus', AntiEvilDown.LogStatus['@value']);

            var SearchUrlProtect = NetProtect.SearchUrlProtect;
            opLock('#SearchUrlProtect_lock', SearchUrlProtect['@attributes'].lock);
            opLock('#SearchUrlProtect_lock_MonStatus', SearchUrlProtect.MonStatus['@attributes'].lock);
            opCheck('#SearchUrlProtect_MonStatus', SearchUrlProtect.MonStatus['@value']);
            opLock('#SearchUrlProtect_lock_LogStatus', SearchUrlProtect.LogStatus['@attributes'].lock);
            opCheck('#SearchUrlProtect_LogStatus', SearchUrlProtect.LogStatus['@value']);

            var AntiXss = NetProtect.AntiXss;
            opLock('#AntiXss_lock', AntiXss['@attributes'].lock);
            opLock('#AntiXss_lock_MonStatus', AntiXss.MonStatus['@attributes'].lock);
            opCheck('#AntiXss_MonStatus', AntiXss.MonStatus['@value']);
            opLock('#AntiXss_lock_LogStatus', AntiXss.LogStatus['@attributes'].lock);
            opCheck('#AntiXss_LogStatus', AntiXss.LogStatus['@value']);

            var AdFilter = NetProtect.AdFilter;
            opLock('#AdFilter_lock', AdFilter['@attributes'].lock);
            opLock('#AdFilter_lock_MonStatus', AdFilter.MonStatus['@attributes'].lock);
            opCheck('#AdFilter_MonStatus', AdFilter.MonStatus['@value']);
            opLock('#AdFilter_lock_logStatus', AdFilter.LogStatus['@attributes'].lock);
            opCheck('#AdFilter_LogStatus', AdFilter.LogStatus['@value']);
            var AdRules = AdFilter.RuleList.Rule;
            $(AdRules).each(function(i,item){
                $('#adUrlList').append('<tr><td title="' + item['@value'] + '">' + item['@value'] + '<td width="50"><a href="javascript:;" class="whiteList_btnRemove">删除</a>');
            })

            var RsIpRule = NetProtect.RsIpRule;
            opLock('#rsIpList_lock', RsIpRule['@attributes'].lock);
            opLock('#rsIpList_lock_MonStatus', RsIpRule.MonStatus['@attributes'].lock);
            opCheck('#rsIpList_MonStatus', RsIpRule.MonStatus['@value']);
            opLock('#rsIpList_lock_LogStatus', RsIpRule.LogStatus['@attributes'].lock);
            opCheck('#rsIpList_LogStatus', RsIpRule.LogStatus['@value']);
            opLock('#rsIpList_lock_AlertStatus', RsIpRule.AlertStatus['@attributes'].lock);
            opCheck('#rsIpList_AlertStatus', RsIpRule.AlertStatus['@value']);
            opLock('#rsIpList_lock_BreakTimes', RsIpRule.BreakTimes['@attributes'].lock);
            container.find('#rsIpList_BreakTimes').val(RsIpRule.BreakTimes['@value']);

            var WhiteUrlList = product.WhiteUrlList;
            var WhiteUrlList_rule = WhiteUrlList.Rule;
            for (var i = 0; i < WhiteUrlList_rule.length; i++) {
                var url = WhiteUrlList_rule[i]['@attributes'].id;
                container.find('#WhiteUrlList').append('<tr><td title="' + url + '">' + url + '<td width="50"><a href="javascript:;" class="whiteList_btnRemove">删除</a>');
            }

            var RsIpRuleList = product.RsIpRuleList;
            var RsIpRuleList_rule = RsIpRuleList.Rule;
            var RsIpdata = [];
            for (var i = 0; i < RsIpRuleList_rule.length; i++) {
                var RsIps = RsIpRuleList_rule[i];
                RsIpdata.push({
                    "ID": RsIps['@attributes']['id'],
                    "Name": RsIps.Name['@value'],
                    "bInuse": RsIps.Status['@value']
                });
            }
            this._initRsIpTable(container, RsIpdata);
            container.find('#whiteList_WhiteUrlStatus').trigger('change');

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