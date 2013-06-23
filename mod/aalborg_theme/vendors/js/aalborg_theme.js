// iOS Hover Event Class Fix
if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
    $(".elgg-page").click(function(){
        // 
    });
}

// remove autofocus to avoid pagejump
$(document).ready(function(){
   $(".elgg-form-login input").removeClass("elgg-autofocus");
});
