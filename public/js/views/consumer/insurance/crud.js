/* //////////////////////////////////////////
* Crud New/Edit insurance Form
*/////////////////////////////////////////////
$("body").delegate("a.crud-new-update-insurance", "click", function() {
      var el  = $(this);
      var id  = el.attr('data-id');
      var cid  = el.attr('data-cid');
      var path = '/insurance/';
      var action = id == '' ? 'create' : 'update';
      var btn = '<input type="button" value="Submit" class="js-insurance-form-'+action+' btn" />';
      lightBox.show('mainModal', 'Add Medical Info',
                                {'remote':path+action+'/'+id+'/',
                                 'params': { id:id, consumer_id:cid },
                                 'callback':function(data) {
                                 $("#consumerinsuranceform").append(btn);
                              }},
                              'get');
});  

/*
* Crud Update Insurance
*/
$("body").delegate( ".js-insurance-form-create", "click", function() {
      Crud.Update('/insurance/create/','consumerinsuranceform', function(data) {
          var template = _.template($("#js-crud-edit-insurance").html());
          $(".js-insurance-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});

/*
* Crud Update Insurance
*/
$("body").delegate( ".js-insurance-form-update", "click", function() {
      Crud.Update('/insurance/update/','consumerinsuranceform', function(data) {
          var template = _.template($("#js-crud-edit-insurance").html());
          $(".insurance-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});

/*
 Curd Delete Insurance
*/
$("body").delegate(".crud-del-insurance", "click", function() {
 var el = $(this);
 var id =  el.attr('data-id');
 Crud.Confirm({ url: '/insurance/delete/',
                          params: {id: id},
                          title: 'Please confirm deleting of Insurance Info.',
                          text:  'The record will be completelly removed! Is it ok?',
                          ok: 'Remove',
                          cancel: 'Cancel'
   }).done(function(data) {
         var el = $(".insurance-row-"+data.id).css({'color':'red'});
             el.fadeOut(1000, function() { $(this).remove(); });
   }).fail(function() {
  });
});
