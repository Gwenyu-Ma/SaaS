<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>ht</title>
    <link href="{$pub}/views/static/css/semantic.min.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <div id="app">
        <div class="column thirteen wide">
            <div class="ui form" style="margin-top: 150px;">
                <div class="ui middle aligned four column centered grid">
                    <div class="column">
                        <div class="ui fluid piled segment">
                            <h3 class="ui header teal ribbon label"><i class="cloud icon"></i>安全云后台管理系统</h3>
                            <div class="field">
                                <label>用户名</label>
                                <div class="ui left icon input">
                                    <i class="user icon"></i>
                                    <input type="text" name="username" id="username" placeholder="用户名" id="username" />
                                </div>
                            </div>
                            <div class="field">
                                <label>密码</label>
                                <div class="ui left icon input">
                                    <i class="lock icon"></i>
                                    <input type="password" name="password" id="password" placeholder="密码" id="password"/>
                                </div>
                            </div>
                            <!-- <div class="inline field">
                                <div class="ui checkbox">
                                    <input type="checkbox" id="rem" class="hidden">
                                    <label for="rem">记住密码</label>
                                </div>
                            </div> -->
                            <div class="ui error message" id="msg_info"></div>
                            <div class="ui blue submit labeled icon button" id="submit_login_info"><i class="signup icon"></i>登录</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{$pub}/views/static/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="{$pub}/views/static/css/semantic.min.js"></script>
    <script type="text/javascript">
        $(function() {
           $("#submit_login_info").bind("click",Login.button_click);
        });

        var Login = function(){
            return {
                button_click:function(){
                    var username,password;
                    username = $.trim($("#username").val());
                    password = $.trim($("#password").val());
                    if(username == ''){
                        Login.error_info('用户名不能为空');
                        Login.input_focus('username');
                        return false;
                    }
                    if(password == ''){
                        Login.error_info('密码不能为空');
                        Login.input_focus('password');
                        return false;
                    }

                    Login.login_submit( username,password )
                },

                error_info:function( msg ){
                    $("#msg_info").html('<span>'+msg+'</span>');
                    $("#msg_info").css("display","block");
                },

                input_focus:function( id ){
                    $("#"+id).focus();
                },

                login_submit:function( username,password ){
                    var url = '/index.php/Index/postLogin';
            		var params = {
            		    username:username,
            			password:password
            		};
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: params,
                        dataType:"json",
                        success:function(jsonResult){
                            var resultObj,result;
                            resultObj = eval(jsonResult);
                            result = resultObj;

                            if(parseInt(result.r.code) == 0){
                                location.href = '/Home/index';
                            }else{
                                Login.error_info( result.r.msg );
                                return false;
                            }
                        }
                    });
                }

            }
        }();
        document.onkeydown = function(evt){
            var evt = window.event?window.event:evt;
            if (evt.keyCode == 13) {
                var username,password;
                username = $.trim($("#username").val());
                password = $.trim($("#password").val());
                if(username != '' && password != ''){
                  Login.login_submit(username,password);
                }
            }
        }
    </script>
</body>

</html>
