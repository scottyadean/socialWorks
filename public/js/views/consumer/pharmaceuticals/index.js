$(function(){



        $(".js-display-edit-icon").each( function(){             
             helpers.editIcon(this);
         });

        $("body").delegate('.js-edit-icon', 'click', function(){window.location = '/pharmaceuticals/update/id/'+$(this).attr('phrama:id');});
         
});