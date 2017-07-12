define(function(require) {
    var tpl = require('text!report.html');
    require('css!sidemenu');
    var sidemenu = require('sidemenu');
    require('slimscroll');
    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            var view = $(container);
            view.append(tpl);
            sidemenu.init(view.find('.side-menu'));

            function setScroll() {
                view.find('.side-menu-scroll').slimScroll({
                    height: 'auto'
                });
            };
            var resizeId;
            $(window).on('resize', function() {
                clearTimeout(resizeId);
                resizeId = setTimeout(function() {
                    setScroll();
                }, 300);
            });
        },
        destroy: function() {
            $(this.container).off().empty();
        }
    }
});