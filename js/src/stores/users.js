define(['pad','proxies'],function(pad,proxies){
    var me = pad.stores.users;
    if (!me) me = {}; pad.stores.users = me;
    me.proxy = proxies.json;
    me.record = 'result';
    me.data = {};
    me.fields = [
        {name: 'idPerson', type:'number'},
        {name: 'primEmail'},
        {name: 'nameFirst'},
        {name: 'nameLast'}
    ];
    me.process = function(json) {
        me.data = me.proxy(json,me.fields);
    };
    me.getRecByIndex = function(index) {
        return me.data[index];
    }
    return me;
});