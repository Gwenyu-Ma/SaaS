/*自定义验证判断*/
$.validator.addMethod("email", function(value, element) {
	return this.optional(element) || /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(value);
}, "邮箱格式错误");

$.validator.addMethod("phone", function(value, element) {
	return this.optional(element) || /^1\d{10}$/.test(value);
}, "手机号格式错误");

$.validator.addMethod("phoneOremail", function(value, element) {
	return this.optional(element) || /^1\d{10}$/.test(value) || /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(value);
}, "账号格式错误");
$.validator.addMethod("passwordStr",function(value,element){
    var num=0;
    function matchAZ(val){
        return val.match(/[A-Z]+/);
    }
    function matchaz(val){ 
        return val.match(/[0-9a-z]+/);
    }
    function matchSign(val){
        return val.match(/[~!@#$%^&*(),./?<>;:'"]+/);
    }
    matchAZ(value) && num++;
    matchaz(value) && num++;
    matchSign(value) && num++;
    return this.optional(element) || num > 1;
})
$.validator.addMethod('nospace',function(value,element){
    return this.optional(element) || /^[^\s]*$/.test(value);
})