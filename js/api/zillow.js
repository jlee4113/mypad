define(['pad'],function(pad){
    var z = pad.api.zillow;
    if (!z) z={}; pad.api.zilllow = z;
    z.zId = 'X1-ZWz1ffgp8hx7gr_adn99';
    z.getBaseUrl = function(){
        var pathArray = location.href.split( '/' ),
            protocol = pathArray[0],
            host = pathArray[2],
            url = protocol + '//' + host;
        return url;
    };
    z.deepSearch = z.getBaseUrl()+'/zillow';
    z.getInfo = function(address,cityState,callback){
        var params = {};
        params['zws-id'] = z.zId;
        params['address'] = address;
        params['citystatezip'] = cityState;
        var ajax = {
            url: z.deepSearch,
            data: params,
            success: function(res){
                console.log(res);
                if(callback)callback(res);
            },
            failure: function(res){
                if(callback)callback(res);
                console.log(res);
            }
        };
        $.ajax(ajax);
    }
    return z;
});