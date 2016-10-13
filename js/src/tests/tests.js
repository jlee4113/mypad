define(['pad'],function(pad){
    var me = pad.tests;
    if (!me) me={}; pad.tests=me;
    me.loadProcs = function(el) {
        $.ajax({
            url: 'php/Processes/list-procs.php',
            success: function(res){
                res = JSON.parse(res);
                var string = '';
                for (var i=0; i<res.length; i++) {
                    if (res[i]  !== 'list-procs.php') {
                        string += '<li><a class="proc" href="#">'+res[i]+'</a></li>';
                    }
                }
                $(el).html(string);
            }
        });
    };
    me.send = function() {
        var length = $('.param').length,data={};
        for (var i=0; i<length; i++) {
            data[$('.param')[i].value] = $('.param-value')[i].value;
        }
        $.ajax({
            url: 'php/Processes/'+pad.tests.php,
            data: data,
            success: function(res) {
                $('#results').html('<div class="row text-center">URL: '+"php/Processes/"+pad.tests.php+'</div>'+
                    '<div class="text-center">'+res+'</div>');
            }
        });
     };
    return me;
});