requirejs.config({
    baseUrl: 'js',
    shim : {
        "bootstrap" : { "deps" :['jquery'] },
        "maps": {"deps" : ['googleMaps']}
    },
    paths: {
        jquery: 'lib/jquery.min',
        jqueryui: 'lib/jquery-ui.min',
        tether: 'lib/tether.min',
        bootstrap: 'lib/bootstrap.min',
        zillow: 'api/zillow',
        maps: 'api/google',
        redfin: 'api/redfin',
        trulia: 'api/trulia',
        zip: 'api/zip',
        mls: 'api/mls.js',
        pad: 'src/pad',
        home: 'src/homes/homes',
        listings: 'src/listings/listings',
        users: 'src/users/users',
        login: 'src/users/login',
        register: 'src/users/register',
        homes: 'src/stores/homes',
        proxies: 'src/stores/proxies',
        userStore: 'src/stores/users',
        googleMaps: 'http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAk0Af-IvqZfHuvOTyIHRq0UeNSJbRb7MY'
    }
});
require(['pad'],function(pad){
    pad.routes.init();
});