define(['pad','proxies'],function(pad,proxies){
    var me = pad.stores.homes;
    if (!me) me = {}; pad.stores.homes = me;
    me.proxy = proxies.xml;
    me.record = 'result';
    me.data = {},
    me.fields = [
        {name: 'FIPScounty'},
        {name: 'address'},
        {name: 'links'},
        {name: 'localRealEstate'},
        {name: 'useCode'},
        {name: 'taxAssessmentYear',type:'number'},
        {name: 'taxAsssessment',type: 'number'},
        {name: 'yearBuilt',type:'number'},
        {name: 'lotSizeSqft',type:'number'},
        {name: 'finishedSqft',type:'number'},
        {name: 'bathrooms',type:'number'},
        {name: 'bedrooms',type:'number'},
        {name: 'totalRooms',type:'number'},
        {name:'lastSoldDate',type:'number'},
        {name: 'lastSoldPrice',type:'number'},
        {name: 'zestimate',type:'number'}
    ];
    me.process = function(xml) {
        me.data = me.proxy(xml,me.record);
        return me.data;
    };
    me.getRecByIndex = function(index) {
        return me.data[index];
    }
    return me;
});
