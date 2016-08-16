function init () {
    var me = this;
    $('.nav').click(function(e){
        var target = e.target;
        for (var i=0; i< $('.page').length; i++) {
            var sec = $('.page')[i];
            if (sec.getAttribute('id') !== target.getAttribute('page')) {
                $(sec).hide();
            }
        }
    });

    $('#login-form-link').click(function(e) {
        loginNav(e);
    });
    $('#register-form-link').click(function(e) {
        registerNav(e);
    });
    $('.submit').click(function(e){
        submit(e);
    });
}

function loginNav (e) {
    $("#login-form").delay(100).fadeIn(100);
    $("#register-form").fadeOut(100);
    $('#register-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
}

function registerNav (e) {
    $("#register-form").delay(100).fadeIn(100);
    $("#login-form").fadeOut(100);
    $('#login-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
}


function getInput(el) {
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
}

function submit (e) {
    e.preventDefault();
    var me = this,
        target = e.target,addReq,checkReq;
    switch (target.getAttribute('id')) {
        case 'register-submit' :
            var props = me.getInput('#register-form');
            addReq = {
                url: '../php/users.php',
                method: 'POST',
                data: props,
                success: function(res) {
                    console.log('success');
                },
                failure: function (res) {
                    console.log('failure');
                }
            };
            checkReq = {
                url: '../php/checkuser.php',
                method: 'GET',
                data: {email:props.email},
                success: function(res) {
                    console.log(res);
                    if (res == 'true') res = true;
                    if (res !== true) {
                        $.ajax(addReq);
                    } else {
                        loginNav();
                    }
                },
                failure: function (res) {
                    console.log('failure');
                }
            }
            $.ajax(checkReq);
            break;
        case 'login-submit' :
            var props = me.getInput('#login-form'),
                loginReq = {
                    url: '../php/login.php',
                    method: 'POST',
                    data: props,
                    success: function (res) {
                        //1=success, 0=username not found, 2=username found, but wrong password
                        if (res == 1) {
                            alert('Success');
                        } else {
                            alert('No');
                        }
                    },
                    failure: function (res) {
                        alert('Failure <br>' + res);
                    }
                };
            $.ajax(loginReq);

    }
}
