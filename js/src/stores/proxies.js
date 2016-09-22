define(['pad'],function(pad){
    var me = pad.stores.proxies;
    if (!me) me = {}; pad.stores.proxies = me;
    var helper = pad.helper;
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
    me.json = function(data,fields) {
        var rec,field;
        for (var i=0; i<data.length; i++) {
            rec = me.convert(fields,data[i]);
        }
    };
    me.interpret = function(val,type,field) {
        if (!type) {
            return val.toString();
        }
        if (type == 'number') {
            return Number(val);
        }
        if (type == 'date') {
            return me.helper.convertDate(val,field.dateFormat);
        }
    },
    me.convert = function(fields, rec) {
        var field;
        for (var prop in rec) {
            for (var i=0; i<fields.length; i++) {
                field = fields[i];
                if (field.name == prop) {
                    rec[prop] = me.interpret(rec[prop],field.type,field);
                }
            }
        }
    };
    return me;
});