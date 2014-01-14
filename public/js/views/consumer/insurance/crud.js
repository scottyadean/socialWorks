/* //////////////////////////////////////////
* Crud New/Edit insurance Form
*/////////////////////////////////////////////
$("body").delegate("a.crud-new-update-insurance", "click", function() {
      var el  = $(this);
      var id  = el.attr('data-id');
      var cid  = el.attr('data-cid');
      var path = '/consumer/insurance/';
      var action = id == '' ? 'new' : 'edit';
      var btn = '<input type="button" value="Submit" class="js-insurance-form-'+action+' btn" />';
      lightBox.show('mainModal', 'Add Medical Info',
                                {'remote':path+action+'/'+id+'/',
                                 'params': { id:id, cid:cid },
                                 'callback':function(data) {
                                 $("#consumerinsuranceform").append(btn);
                              }},
                              'get');
});  

/*
* Crud Update Insurance
*/
$("body").delegate( ".js-insurance-form-new", "click", function() {
      Crud.Update('/consumer/insurance/new/','consumerinsuranceform', function(data) {
          var template = _.template($("#js-crud-edit-insurance").html());
          $(".js-insurance-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});

/*
* Crud Update Insurance
*/
$("body").delegate( ".js-insurance-form-edit", "click", function() {
      Crud.Update('/consumer/insurance/edit/','consumerinsuranceform', function(data) {
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
 Crud.Confirm({ url: '/consumer/insurance/delete/',
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
