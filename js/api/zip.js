define(['pad'],function(pad){
    var me = pad.api.zip;
    if (!me) me = {}; pad.api.zip = me;
    me.getCityState = function(zip,callback) {
        $.ajax({
            url: 'http://ziptasticapi.com/'+zip,
            method: 'GET',
            success: function(res){
                res = JSON.parse(res);
                var state = res.state,
                    city = res.city;
                if (callback) callback(city,state);
            }
        });
    }
});