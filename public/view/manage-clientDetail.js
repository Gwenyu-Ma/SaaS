define(function(require) {
    var tpl = require('text!manage-clientDetail.html');
    var mustache = require('mustache');
    return {
        container: undefined,
        render: function(container, paramStr) {    
            this.container = container;
            var view = $(container);
            var html = '';
            if (paramStr) {
                var id = paramStr.split('&')[0];
                var groupID = paramStr.split('&')[1];
                var groupName = RsCore.cache.group.list[groupID];
                 RsCore.ajax('ep/getep', {
                    sguid:id
                }, function(data) {
                    data.groupName = groupName;
                    html = mustache.render(tpl,data);
                    view.append(html);
                });
                
            } else {
                return false;
            }
            view.on('click','#back',function(){
                window.history.back();
            })
        },
        destroy: function() {
            $(this.container).off().empty();
            console.log('destroy manage-clientDetail page');
        }
    }
});