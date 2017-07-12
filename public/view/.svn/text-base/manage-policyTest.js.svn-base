define(function(require) {
    var tpl = require('text!manage-policyTest.html');
    require('css!../js/plugin/sidemenu/content-sidemenu.css');
    var sidemenu = require('sidemenu');
    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            $(container).append(tpl);
            sidemenu.init($('.content-side-menu'), '');
        },
        destroy: function() {
            $(this.container).empty();
            //console.log('destroy manage-policyTest page');
        }
    }
});