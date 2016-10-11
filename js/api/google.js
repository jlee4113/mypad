define(['pad'],function(pad){
    var g = this;
    g.autoComplete = function(div,handler) {
         return new google.maps.places.Autocomplete(div);
    }
    return g;
});