$(function(){
    
    $('.coordinator-info-btn').each(function(){
        
     var id = $(this).attr('data-id');
     $(this).popover({html:true,
                      placement:'bottom',
                      title:"Service Coordinator Info",
                      trigger:'click',
                      content:'<div id="pop-over-content-'+id+'"></div>'
                     });
    });
    
    $("body").delegate(".coordinator-info-btn", "click", function(){
        
           var id = $(this).attr("data-id");
           var po = $("#coordinator-info-popover-"+id);
            asyncAction.appendToDom({id:'pop-over-content-'+id},
                                    '/coordinator/read',
                                    {id:id},
                                    'post',
                                    'html');
            po.popover('toggle'); 
       });
    

 });