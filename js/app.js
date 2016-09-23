requirejs.config({
    baseUrl: 'js',
    shim : {
        "bootstrap" : { "deps" :['jquery'] }
    },
    paths: {
        jquery: 'lib/jquery.min',
        jqueryui: 'lib/jquery-ui.min',
        tether: 'lib/tether.min',
        bootstrap: 'lib/bootstrap.min',
        zillow: 'api/zillow',
        google: 'api/google',
        redfin: 'api/redfin',
        trulia: 'api/trulia',
        zip: 'api/zip',
        mls: 'api/mls.js',
        pad: 'src/pad',
        home: 'src/homes/homes',
        users: 'src/users/users',
        login: 'src/users/login',
        register: 'src/users/register',
        homes: 'src/stores/homes',
        proxies: 'src/stores/proxies',
        userStore: 'src/stores/users'
    }
});
require(['pad'],function(pad){
    pad.routes.init();
});