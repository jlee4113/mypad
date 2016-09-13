define(['pad'],function(pad){
    var g = pad.api.google;
    if (!g) g={}; pad.api.google = g;
    //add api functions here
    return g;
});