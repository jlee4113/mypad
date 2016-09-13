define(['pad'],function(pad){
    var me = pad.stores.proxies;
    if (!me) me = {}; pad.stores.proxies = me;
    me.xml = function(xml,record){
        var data = false;
        var results = xml.getElementsByTagName(record),result;
        if (results.length) data = [];
        function getData(xml) {
            var val = {};
            for (var i=0; i<xml.childNodes.length; i++) {
                if (!xml.childNodes[i].firstChild) {
                    val[xml.childNodes[i].nodeName] = '';
                } else {
                    if (xml.childNodes[i].firstChild.nodeValue !== null) {
                        val[xml.childNodes[i].nodeName] = xml.childNodes[i].firstChild.nodeValue;
                    } else {
                        //means deep structure xml
                        val[xml.childNodes[i].nodeName] = getData(xml.childNodes[i]);
                    }
                }
            }
            return val;
        }
        for (var i=0; i<results.length; i++) {
            result = results[i]
            data.push(getData(result));
        }
        return data;
     };
    return me;
});