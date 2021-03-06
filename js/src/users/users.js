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
        if (!$('#user-dropdown').length) {
            pad.helper.addHtml('#nav-menu-right','nav-account');
        }
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
        recoverPass: function(){
            var props = pad.helper.getInput('#reset-password-form'),
                email = props.primEmail;
            function getContent(email){
                var val = 'Reset your password here: http://'+document.domain+
                    '?email='+email+'&route=change-password';
                return val;
            }
            $.ajax({
                method: 'POST',
                url: 'php/Processes/sendEmail.php',
                data: {
                    sendTo: email,
                    emailContent: getContent(email),
                    emailTitle: 'Password Update'
                },
                success: function(res) {
                    console.log('Success');
                },
                error: function(res) {
                    console.log('Error');
                }
            });
        },
        init: function() {
            var email = pad.helper.getUrlParam('email');
            $('#email').html(email).attr('value',email);
        },
        setPass: function() {
            var props = pad.helper.getInput('#change-password-form'),
                ajax = {
                    method: 'POST',
                    url: '../php/Processes/passwordSave.php',
                    data: {
                        email: pad.helper.getUrlParam('email'),
                        password: props.password
                    },
                    success: function(res) {
                        res = JSON.parse(res)
                        if (Number(res.returnCode) === 0) {
                            pad.routes.showResults('Password successfully updated');
                            setTimeout(function(){
                                window.location.href = 'http://'+document.domain+'?route=list';
                            },2000);
                        }
                    },
                    error: function(res) {
                        res = JSON.parse(res);
                        console.log('error');
                    }
                };
            $.ajax(ajax);
        }
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
                            ls.setItem('email',res.data[0].primEmail);
                            userStore.process(res);
                            pad.helper.updateActivity();
                            location.reload();
                        }
                    });
                    ls.setItem('email',props.primEmail);
                    me.updateNav();
                } else {
                    if (Number(res.returnCode) ===2) {
                        alert('Incorrect Password');
                    }
                }
            },
            failure: function (res) {
                alert('Login Failed <br>' + res);
            }
        };
        $.ajax(loginReq);
    };
    me.signOut = function(){
        var ls = localStorage;
        ls.removeItem('email');
        ls.removeItem('lastActivity');
        location.reload();
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
                var code = Number(res.returnCode);
                if (code === 3) {
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
                var code = Number(res.returnCode);
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
        $('#login-submit').unbind('click').click(function(e){
            me.login();
        });
        $('#register-submit').unbind('click').click(function(e){
            me.register()
        });
        $('#reset-password').unbind('click').click(function(e){
            me.recover.recoverPass()
        });
        $('#update-password-btn').unbind('click').click(function(e){
            me.recover.setPass()
        });
        $('.cache').click(function(e){
            me.cached = e.target.value()
        });
        $('#sign-out-nav').unbind('click').click(function(e){
            me.signOut();
        });
        me.updateNav();
    };
    return me;
});