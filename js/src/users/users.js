define(['pad'],function(pad,login,register){
    var me = pad.users;
    me.required = {
        email: true,
        password: true,
        firstName: true,
        lastName: true
    };
    me.cached = false;
    me.validate = function(props,type){
        var req = me.required;
        if (type == 'login') {
            for (var prop in props) {
                if (req[prop]) {
                    if (!props[prop]) return false;
                }
            }
        } else if (type == 'register') {
            for (var prop in props){
                if (req[prop]) {
                    if (!props[prop])return false;
                }
                if (prop = 'password'){
                    var pass = props[prop];
                    if (pass.getLength() < 7)  return false;
                    //add code to check for number in password
                }
            }
        }
    };
    me.recover = {
        pass: function(email){
            var ajax = {
                method: 'POST',
                url: '../php/recover.php',
                params: {email: email},
                success: function(res) {
                    //navigate to page that tells user that their password has been sent to them
                    alert('Password has been sent');
                },
                failure: function(res) {
                    //tell user that the email could not be found
                    alert('Email not found');
                }
            };
            $.ajax(ajax);
        },
    };
    me.login = function(){
        var ls = localStorage;
        if (me.cache) {
            var user = $('#user-entry').value();
            ls.setItem('user',user);
        } else {
            ls.clear();
        }
        var props = pad.helper.getInput('#login-form');
        //var good = me.validate(props);
        //if (!good){
        //    alert('Failed validation');
        //    return;
        //}
        var loginReq = {
            url: '../php/Processes/passwordCheck.php',
            method: 'POST',
            data: props,
            success: function (res) {
                //1=success, 0=username not found, 2=username found, but wrong password
                if (res == 1) {
                    alert('Success');
                } else if (res = 0) {
                    alert('username not found');
                } else if (res = 2) {
                    alert('username found, but wrong password');
                }
            },
            failure: function (res) {
                alert('Login Failed <br>' + res);
            }
        };
        $.ajax(loginReq);
    };
    me.register = function(){
        var ls = localStorage;
        var props = pad.helper.getInput('#register-form');
        //var good = me.validate(props);
        //if (!good){
        //    alert('Failed validation');
        //    return;
        //}
        //check to see if email exists
        var reg = {
                url: '../php/Processes/createUser.php',
                method: 'POST',
                data: props,
                success: function (res) {
                    //1=success, 0=username not found, 2=username found, but wrong password
                    if (res == 1) {
                        alert('Success');
                    } else if (res == 0) {
                        alert('Username already exists');
                    }
                },
                failure: function (res) {
                    alert('Login Failed <br>' + res);
                }
            };
        $.ajax(reg);
    };
    me.init = function(){
        $('#login-submit').click(function(e){
            me.login();
        });
        $('#register-submit').click(function(e){
            me.register()
        });
        $('.recover-pass').click(function(e){
            me.recover.pass()
        });
        $('.cache').click(function(e){
            me.cached = e.target.value()
        });
    };
    return me;
});