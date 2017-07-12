$(function() {
    /*兼容placeholder*/
    $('input[type=text],input[type=password]').placeholder({isUseSpan:true});
    $('input[type=radio],input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_polaris',
        radioClass: 'iradio_polaris',
        increaseArea: '-10%' // optional
    }); 
    
    var param = window.location.hash.slice(1).split('&');
    var hashObj = {};
    for(var i=0;i<param.length;i++){
        var _pa = param[i].split('=');
        hashObj[_pa[0]] = _pa[1];
    }
    if(hashObj['username']){
        $('#txtName').focus().val(hashObj['username']);
        $('#txtPwd').focus();
    }else{
        $('#txtName').focus();
    }
    
    var sw1 = new Swiper('.js_slide_scroll1',{
                loop : true,
                autoplay:2000,
                pagination:'.js_slide_scroll1 .swiper-pagination',
                paginationClickable :true,
                autoplayDisableOnInteraction:false
            });
    var sw2 = new Swiper('.js_slide_scroll2',{
                loop : true,
                autoplay:2000,
                pagination:'.js_slide_scroll2 .swiper-pagination',
                paginationClickable :true,
                autoplayDisableOnInteraction:false
            });

    $('.js_slide_scroll1').show();
        $('.js_slide_scroll2').hide();
        sw2.stopAutoplay();
        sw1.startAutoplay();
    if(hashObj['oType']==2){
        $('.js_slide_scroll1').hide();
        $('.js_slide_scroll2').show();
        sw1.stopAutoplay();
        sw2.startAutoplay();
    }
    $('.swiper-container').hover(function(){
        $('.js_slide_scroll1:visible').length&&sw1.stopAutoplay();
        $('.js_slide_scroll2:visible').length&&sw2.stopAutoplay();
    },function(){
        $('.js_slide_scroll1:visible').length&&sw1.startAutoplay();
        $('.js_slide_scroll2:visible').length&&sw2.startAutoplay();
    });
    /*验证码*/
    var codeSrc = $('#codeImg').attr('src');
    $('form').on('click', '#verify_btn', function() {
        $('#codeImg')[0].src = codeSrc+'?rdm='+Math.floor(Math.random()*10000);
        return false;
    });
    /*忘记密码*/
    $('.js_reset_pwd').on('click',function(){
        var txt = $('#txtName').val();
        var flag = assist.verify.email(txt);
        if(flag){
            window.location = '/findPwdByEmail.html';
        }else{
            window.location = '/findPwdByPhone.html';
        }
        return false;
    });

    /*验证*/
    var formValidate = $('form').validate({
        rules: {
            txtName: {
                required: true
            },
            txtPwd: {
                required: true
            },
            txtCode: {
                required: true
            }
        },
        messages: {
            txtName: {
                required: "请输入账号"
            },
            txtPwd: {
                required: "请输入密码"
            },
            txtCode: {
                required: "请输入验证码"
            }
        },
        errorPlacement: function(error, ele) {
            error.appendTo(ele.parent());
        }
    });

    /*提交*/
    $(document).on('keyup',function(e){
        var e = window.event || e;
        var keycode = e.keyCode||e.which||e.charCode;
        if(keycode==13){
            $('.js_submit').trigger('click');
        }
    });
    $('.js_submit').on('click', function() {
        var that = $(this);
        if(that.hasClass('disabled')){
            return false;
        }
        if (!formValidate.form()) {
            return false;
        }
        var params = {
            useridname: $.trim($('#txtName').val()),
            userpwd: $.trim($('#txtPwd').val()),
            code: $.trim($('#txtCode').val())
        };
        $.ajax({
            url: '/index.php/Index/postlogin',
            type: "POST",
            dataType: "json",
            data: params,
            beforeSend:function(){
                that.addClass('disabled');
                $('.js_submit').button('loading');
            },
            success: loginAct, 
            error: function(data) {
//                console.log(e);
                var da = data.r;
                $('.js_submit').button('reset');
                assist.dialog({
                    title: '提示',
                    content: '系统异常:' + da.msg
                });
            },
            complete:function(){
                that.removeClass('disabled');
                
            }
        });
    });

    /*登录事件*/
    function loginAct(data) {
        var d = data.r;
        switch (d.code) {            
            case 0://成功
                /*
                assist.dialog({
                    title: '成功',
                    content: d.msg
                });
                */
                var locals = data.data.location;
                var locations = [locals.m,locals.a];
                window.location.href = '/'+locations.join('/');
                break;                
            case 14://激活
                $('.js_submit').button('reset');
                $('#verify_btn').trigger('click');
                var box=assist.dialog({
                        title:'提示',
                        content:'<div>账号尚未激活，如果没有收到邮件，请<a href="javascript:;" class="js_repeat_email" style="color:#fff;">重新发送</a></div>'
                    },{
                        autoclose:false,
                        escapContent:false
                    });
                $('.js_repeat_email').off('click').on('click',function(){
                    $.ajax({
                        url: '/index.php/Index/getmail',
                        type: "POST",
                        dataType: "json",
                        data: {'email':$('#txtName').val()},
                        success:function(data){
                            box.remove();
                            assist.dialog({
                                title:'成功',
                                content:'邮件发送成功'
                            });
                        },
                        error:function(){
                            box.remove();
                            assist.dialog({
                                title:'失败',
                                content:'邮件发送失败'
                            });
                        }
                    });
                });
                break;          
            case 2://超时
                $('.js_submit').button('reset');
                assist.dialog({
                    title: '提示',
                    content: d.msg
                });
                $('#verify_btn').trigger('click');
                break;
            case 3://显示验证码
                $('.js_submit').button('reset');
                $('#txtCode').parents('.control-group').removeClass('hidden');
                break;
            case 11://密码错误
                $('.js_submit').button('reset');
                showErrLabel(formValidate,'txtName','');
                showErrLabel(formValidate,'txtPwd','用户名或密码错误');
                $('#verify_btn').trigger('click');
                break;
            case 10://用户名错误
                $('.js_submit').button('reset');
                showErrLabel(formValidate,'txtName',d.msg);
                $('#verify_btn').trigger('click');
                break;
            case 13://验证码错误
                $('.js_submit').button('reset');
                if( $('#txtCode').parents('.control-group').hasClass('hidden')){
                    $('#txtCode').parents('.control-group').removeClass('hidden');
                }else{
                    showErrLabel(formValidate,'txtCode','验证码错误');
                }                
                $('#verify_btn').trigger('click');
                break;
            default:
                $('.js_submit').button('reset');
                assist.dialog({
                    title: '提示',
                    content: d.msg
                });
                $('#verify_btn').trigger('click');
                break;
        }
    }
});
