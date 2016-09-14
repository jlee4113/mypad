define(['pad','zillow','google','homes'],function(pad,z,g,homes){
    var me = pad.home;
    if (!me) me = {}; pad.home = me;
    me.init = function(){
        $('#home-submit').click(function(e){
            me.getHomeInfo();
        });
    };
    me.createDivs = function(data) {
        var returnVal,array=[],
            title = data.title;
        if (!title) title = 'General Info';
        returnVal = '<div class="panel panel-default">'+
                        '<div class="panel-heading">'+
                            '<h3 class="panel-title">'+title+'</h3>'+
                                '<div class="panel-body">'+
                                    '<ul class="list-group">';
        for (var prop in data) {
            if (prop == 'title') continue;
            if (typeof(data[prop]) !== 'object') {
                returnVal +=
                    '<li class="list-group-item">' +
                        '<div class="row">' +
                            '<div class="col-md-6">'+prop+'</div>' +
                            '<div class="col-md-6">'+data[prop]+'</div>' +
                        '</div>';
                    '</li>';
            } else {
                data[prop].title = prop;
                array.push(data[prop]);
            }
        }
        returnVal += '</ul></div></div></div>';
        for (var i=0; i<array.length; i++) {
            returnVal += me.createDivs(array[i]);
        }
        return returnVal;
    };
    me.getHomeInfo = function(){
        var ls = localStorage;
        var props = pad.helper.getInput('#home-info'),
            address = props.address,
            cityState = props.city+' '+props.state;
        z.getInfo(address,cityState,function(res){
            if(!res) res = "No Response";
            homes.process(res);
            var zest = $(me.createDivs(homes.data[0])),
                res = '';
            for (var i=0; i<zest.length; i++) {
                pad.routes.showResults(zest[i],true);
            }
        });
    };
    return me;
    //changing
});