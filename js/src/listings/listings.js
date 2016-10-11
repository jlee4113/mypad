define(['pad','zillow','maps','homes','zip'],function(pad,z,maps,homes,zip){
    var me = pad.listings;
    if (!me) me = {}; pad.listings = me;
    var ls = localStorage;
    me.init = function(){
        $('#home-submit').click(function(e){
            me.getHomeInfo();
        });
    };
    me.claimHome = function() {
        var home = me.currentHome,
            address = home.address,
            zip = home.zip;x
        $.ajax({
            url: '../php/Processes/createHome.php',
            data: {
                idPerson: ls.getItem('userId'),
                address: address,
                zip: zip
            },
            method: 'POST',
            success: function(res) {
                res = JSON.parse(res);
            },
            error: function(res) {
                console.log('error');
            }
        });
    };
    me.autoComplete = function(div) {
        maps.autoComplete(div);
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
    me.getCityState = function(zipCode,callback){
        zip.getCityState(zipCode,function(res){
            res = JSON.parse(res);
            var cityState = res.city+' '+res.state;
            if (callback) callback(cityState);
        });
    };
    me.updateHomeInfo = function(address) {
        z.getInfo(address[0],address[1],function(res){
            console.log(res);
            require(['homes'],function(homes){
                res = homes.process(res);
                pad.currentHome.info = res[0];
                me.createListing();
            });
        });
    };
    me.listHomeInfo = function(address, zip){
        if (!zip) {

        }
        me.currentHome = {
            address: address,
            zip: zip
        };
        me.getCityState(zip,function(cityState){
            z.getInfo(address,cityState,function(res){
                if(!res) res = "No Response";
                homes.process(res);
                var zest = $(me.createDivs(homes.data[0]));
                $.ajax({
                    url: 'html/claim-home-btn.html',
                    success: function(res){
                        zest.splice(0,0,res);
                        var append = false;
                        for (var i=0; i<zest.length; i++) {
                            if (i>0) append = true;
                            pad.routes.showResults(zest[i],append);
                        }
                    }
                });
            });
        });
    };
    me.getHomeInfo = function(){
        var me=this,
            props = pad.helper.getInput('#home-info'),
            address = props.address;
        me.listHomeInfo(address, props.zip);
    };
    me.createListing = function() {
        var data = {
                idPerson: ls.getItem('userId'),
                address: pad.currentHome.address,
            },
            overrides = ['bedrooms','bathrooms','totalRooms','finishedSqFt','lotSizeSqFt'],item;
        for (var prop in pad.currentHome.info) {
            for (var i=0; i<overrides.length; i++) {
                item = overrides[i];
                if (prop === item) {
                    data[prop] = pad.currentHome.info[prop];
                }
            }
        }
        $.ajax({
            url: 'php/Processes/createListing.php',
            method: 'POST',
            data: data,
            success: function(res) {
                console.log(res);
            },
            error: function(res) {
                alert('Error. Check the console');
                console.log(res);
            }
        });
    };
    return me;
});