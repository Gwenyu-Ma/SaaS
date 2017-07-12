define(function(require) {
    var tpl = require('text!manage-policyTest-zdsj.html');
    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            $(container).append(tpl);
        },
        destroy: function() {
            $(this.container).empty();
            console.log('destroy manage-policyTest-zdsj page');
        }
    };
});