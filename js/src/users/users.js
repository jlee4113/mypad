define(['pad','userStore'],function(pad,userStore){
    var me = pad.users,
        helper = pad.helper;
    me.required = {
        email: true,
        password: true,
        firstName: true,
        lastName: true
    };
    me.cached = false;
    me.updateNav = function() {
        pad.helper.addHtml('#nav-menu-right','nav-account');
    };
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
        pass: function(){
            var props = pad.helper.getInput('#reset-password-form'),
                email = props.primEmail;
            var ajax = {
                method: 'POST',
                url: '../php/Processes/passwordReset.php',
                data: {primEmail: email},
                success: function(res) {
                    res = JSON.parse(res);
                    $.ajax({
                        method: 'POST',
                        url: 'php/Processes/sendEmail.php',
                        data: {
                            sendTo: email,
                            emailContent: 'Your new password is: '+res.data[0].newPassword,
                            emailTitle: 'MyPad Password Update'
                        },
                        success: function(res) {
                            console.log('Success');
                        },
                        error: function(res) {
                            console.log('Error');
                        }
                    });
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
        var loginReq = {
            url: '../php/Processes/passwordCheck.php',
            method: 'POST',
            data: props,
            success: function (res) {
                res = JSON.parse(res);
                if (Number(res.returnCode) === 1) {
                    pad.helper.validate(props.primEmail,function(res){
                        if (!res.returnCode) {
                            console.log('failure');
                            //add code to handle failure
                        }
                        if (res.returnCode == '2' || res.returnCode === 2) {
                            ls.setItem('userId',res.data[0].idPerson);
                            userStore.process(res);
                            helper.updateActivity();
                        }
                    });
                    ls.setItem('email',props.primEmail);
                    me.updateNav();
                }
                if (res.returnCode !== 1) {
                    console.log('nope');
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
        var reg = {
            url: '../php/Processes/createUser.php',
            method: 'POST',
            data: props,
            success: function (res) {
                res = JSON.parse(res);
                var code = Number(res[0].returnCode);
                if (res === 3) {
                    alert('Success. Account Created');
                }
            },
            failure: function (res) {
                alert('Login Failed <br>' + res);
            }
            };
        var check = {
            url:'../php/Processes/validateEmail.php',
            method: 'POST',
            data: {primEmail: props.primEmail},
            success: function(res) {
                res = JSON.parse(res);
                var code = Number(res[0].returnCode);
                if (code === 2) {
                    //add code to display that the account already exists
                    alert('Account already exists');
                    return;
                }
                if (code === 1) {
                    //validated that the account does not exist. Continue on with the registration
                    $.ajax(reg);
                }
            },
            failure: function(res) {
                alert('Failed validate email');
            }
        };
        $.ajax(check);
    };
    me.init = function(){
        $('#login-submit').click(function(e){
            me.login();
        });
        $('#register-submit').click(function(e){
            me.register()
        });
        $('#reset-password').click(function(e){
            me.recover.pass()
        });
        $('.cache').click(function(e){
            me.cached = e.target.value()
        });
        me.updateNav();
    };
    return me;
});