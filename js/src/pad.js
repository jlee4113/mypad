define(['jquery','bootstrap','maps'],function($,bs,maps){
    pad = {};
    var ls = localStorage;
    pad.users = {
        views: {
            login: '#login',
            register: '#register',
            edit: '#edit-user'
        }
    };
    pad.docs = {};
    pad.stores = {};
    pad.currentHome = {};
    pad.api = {
        zillow:{},
        redfin:{},
        trulia:{},
        mls:{},
        google:{}
    };
    pad.listing = {
        create: {
            views: {
                general: '#homes-general',
                advanced: '#homes-advanced'
            }
        },
        manage: {},
        negotiate: {},
        showing: {}
    };
    pad.buyer = {};
    pad.helper = {
        getInput: function(el) {
            var form = $(el),
                fields = form.find('.input'),
                field, prop, value, obj={};
            for (var i=0; i<fields.length; i++) {
                field = $(fields[i]);
                prop = field.prop('id');
                value = field.val();
                obj[prop] = value;
            }
            return obj;
        },
        addHtml: function(parentDiv,html,append){
            if (append) {
                $(parentDiv).load('html/'+html+'.html');
            } else {
                $(parentDiv).html('');
                $(parentDiv).load('html/'+html+'.html');
            }
        },
        getUrlParam: function(name,url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
        loggedIn: function() {
            if (!ls.getItem('email')) return false;
            var now = Date.now(),
                last = ls.getItem('lastActivity');
            if (!last) return false;
            //if last activity was over an hour, force login.
            if (now-ls.getItem('lastActivity') > 3600000) return false;
            else return true;
        },
        updateActivity: function(){
            if (ls.getItem('lastActivity')) {
                ls.setItem('lastActivity',Date.now());
            } else {
                ls.setItem('lastActivity',Date.now());
            }
        },
        convertDate: function(val, format){
            //assumes YYYYMMDD
            if (!format) format = 'm/d/Y';
            //add code to format date based on format
            return val;
        },
        updateActions: function(val){
            switch (val) {
                case 'clOne.html':
                    pad.actions = {
                        persist: true
                    };
                    break;
                case 'clTwo.html':
                    pad.actions = {
                        zillow: true
                    };
                    break;
            }
         },
        formatPhone: function(input) {
            input = input.replace(/\D/g,'');
            input = input.substring(0,10);
            var size = input.length;
            if(size == 0){
                input = input;
            }else if(size < 4){
                input = '('+input;
            }else if(size < 7){
                input = '('+input.substring(0,3)+') '+input.substring(3,6);
            }else{
                input = '('+input.substring(0,3)+') '+input.substring(3,6)+' - '+input.substring(6,10);
            }
            return input;
        },
        handleActions: function(actions){
            for (var prop in actions) {
                if (actions[prop] === true) {
                    if (prop == 'zillow') {
                        var address = $('#address').getValue();
                    }
                    if (prop == 'persist') {

                    }
                }
            }
        },
        validate: function(email,callback) {
            $.ajax({
                url: 'php/Processes/validateEmail.php',
                method: 'POST',
                data: {
                    primEmail: email
                },
                success: function(res){
                    res = JSON.parse(res);
                    if (callback) callback(res);
                },
                failure: function(res) {
                    if (callback) callback(res);
                }
            });
        },
        cache: {
            get: function(param){
                ls.getItem(param);
            },
            set: function(param,val){
                ls.setItem(param,val);
            }
        }
    };
    pad.rederer = {
        render: function(feild){
            //insert code to handle render
        }
    };
    pad.routes = {
        home: '#home',
        login: '#login',
        register: '#register',
        listing: '#listing',
        route: function(path) {
            path = decodeURIComponent(path);
            if (path.substr(path.length-5) !== '.html') {
                path = 'html/'+path+'.html';
            } else {
                path = 'html/external/'+path;
            }
            $('#widget').load(path);
            $('#results').html('');
        },
        showResults: function(val,append){
            if (!append) {
                $('#results').html(val);
            } else {
                $('#results').append(val);
            }
        },
        init: function() {
            var me=this;
            $('#widget').on('DOMNodeInserted', function(e) {
                if ($('#address').length > 0) {
                    var el = $('#address')[0];
                    el.setAttribute('autocomplete',"off");
                    if (pad.currentHome.address) {
                        el.value = pad.currentHome.address;
                    } else {
                        if (el.getAttribute('acHit') !== "true") {
                            var ac = maps.autoComplete($('#address')[0]);
                            ac.addListener('place_changed',function(){
                                var place = ac.getPlace(),
                                    address = place.formatted_address;
                                pad.currentHome.address = address;
                                address = address.split(',');
                                require(['listings'],function(listings){
                                    listings.updateHomeInfo(address);
                                });
                            });
                        }
                        el.setAttribute('acHit',true);
                    }
                }
                if ($('#mobile_phone').length > 0) {
                    for (var i=0; i<$('#mobile_phone').length; i++) {
                        var el = $('#mobile_phone')[i];
                        $(el).keyup(function(e){
                            e.preventDefault();
                            e.target.value = pad.helper.formatPhone(e.target.value);
                        });
                    }
                }
            });
            $(document).on('click','.proc',function(el){
                $('#proc').html(el.target.innerHTML);
                $('#proc').attr('value',el.target.innerHTML)
                pad.tests.php = el.target.innerHTML;
            });
            $(document).on('click','.nav',function(e){
                if (pad.actions) {
                    pad.helper.handleActions(pad.actions);
                }
                var target = e.target.getAttribute('nav');
                me.route(target);
            });
            $(document).on("click mousedown mouseup focus blur keydown change", function(){
                pad.helper.updateActivity();
            });
            var navs = $('.nav'),nav,
                customRender = $('.custom-render'),
                urlPath = pad.helper.getUrlParam('route');
            if (!urlPath) {
                if (!pad.helper.loggedIn()) {
                    urlPath = 'login';
                } else {
                    urlPath = 'home';
                }
            }
            me.route(urlPath);
            for (var i=0; i<navs.length; i++) {
                nav = navs[i];
                $(nav).click(function(e){
                    var target = e.target.getAttribute('nav');
                    me.route(target);
                });
            }
            for (var i=0; i<customRender.length; i++) {
                $(customRender[i]).change(function(e){

                });
            }
        }
    };
    return pad;
});