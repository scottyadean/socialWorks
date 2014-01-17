/* //////////////////////////////////////////
* Crud New/Edit Goals
*/////////////////////////////////////////////
$("body").delegate("a.crud-new-update-goals", "click", function() {
      var el  = $(this);
      var id  = el.attr('data-id');
      var cid  = el.attr('data-cid');
      var path = '/goals/';
      var action = id == '' ? 'create' : 'update';
      var btn = '<input type="button" value="Submit" class="js-goals-form-'+action+' btn" />';
      lightBox.show('mainModal', 'Add Goal',
                                {'remote':path+action+'/'+id+'/',
                                 'params': { id:id, consumer_id:cid },
                                 'callback':function(data) {
                                 $("#consumergoalsform").append(btn);
                                 lightBox.dateFields();
                              }},
                              'get');
});  

/*
* Crud Update Goals
*/
$("body").delegate( ".js-goals-form-create", "click", function() {
      Crud.Update('/goals/create/','consumergoalsform', function(data) {
          var template = _.template($("#js-crud-edit-goals").html());
          $(".js-goals-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});

/*
* Crud Update Goals
*/
$("body").delegate( ".js-goals-form-update", "click", function() {
      Crud.Update('/goals/update/','consumergoalsform', function(data) {
          var template = _.template($("#js-crud-edit-goals").html());
          $(".goals-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});

/*
 Curd Delete Goals
*/
$("body").delegate(".crud-del-goals", "click", function() {
 var el = $(this);
 var id =  el.attr('data-id');
 Crud.Confirm({ url: '/goals/delete/',
                          params: {id: id},
                          title: 'Please confirm deleting this goal.',
                          text:  'The record will be completelly removed! Is it ok?',
                          ok: 'Remove',
                          cancel: 'Cancel'
   }).done(function(data) {
         var el = $(".goals-row-"+data.id).css({'color':'red'});
             el.fadeOut(1000, function() { $(this).remove(); });
   }).fail(function() {
  });
 
});