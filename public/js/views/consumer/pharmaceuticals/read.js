$(function(){


$("#js-pharma-more-info").click(function() {
    
    $("#js-pharma-more-info-iframe").removeClass('hidden');  
 
    $("#js-pharma-more-info-iframe").toggle("slow","linear",function(){
    
    
        if( $("#js-iframe").attr('src') == '' ){
    
            $("#js-iframe").attr('src',$("#site-link").attr('href'));
            
        }   
    });
});



});