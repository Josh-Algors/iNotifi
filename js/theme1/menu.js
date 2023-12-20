$(document).ready(function(){
    $('#main-menu').on({
        "hide.bs.dropdown":  function() { return false; }
    });
});