/* //////////////////////////////////////////
* Crud New/Edit appointments Form
*/////////////////////////////////////////////
$("body").delegate("a.crud-new-update-appointments", "click", function() {
      var el  = $(this);
      var id  = el.attr('data-id');
      var cid  = el.attr('data-cid');
      var path = '/appointment/';
      var action = id == '' ? 'create' : 'update';
      var btn = '<input type="button" value="Submit" class="js-appointments-form-'+action+' btn" />';
      lightBox.show('mainModal', 'Add Appointment',
                                {'remote':path+action+'/'+id+'/',
                                 'params': { id:id, cid:cid },
                                 'callback':function(data) {
                                 $("#consumermedappontmentform").append(btn);
                                 lightBox.dateFields();
                              }},
                              'get');
});


$("body").delegate(".crud-view-appointment", "click", function(){
        
        var id = $(this).attr('data-id');
     
        lightBox.show('mainModal', 'Appointment Info',
                                {'remote':'/appointment/read/id/'+id+'/',
                                 'params': { id:id},
                                 'callback':function(data) {
                              }},
                              'get');
    
});

/*
* Crud Update Appointments
*/
$("body").delegate( ".js-appointments-form-create", "click", function() {
      Crud.Update('/appointment/create/','consumermedappontmentform', function(data) {
          var template = _.template($("#js-crud-edit-appointments").html());
          $(".js-appointment-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});

/*
* Crud Update Appointments
*/
$("body").delegate( ".js-appointments-form-update", "click", function() {
      Crud.Update('/appointment/update/','consumermedappontmentform', function(data) {
          var template = _.template($("#js-crud-edit-appointments").html());
          $(".appointments-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
          lightBox.close('mainModal');  
      }, 'json' );
});


/*
 Curd Delete Insurance
*/
$("body").delegate(".crud-del-appointments", "click", function() {
 var el = $(this);
 var id =  el.attr('data-id');
 Crud.Confirm({ url: '/appointment/delete/',
                          params: {id: id},
                          title: 'Please confirm deleting of Insurance Info.',
                          text:  'The record will be completelly removed! Is it ok?',
                          ok: 'Remove',
                          cancel: 'Cancel'
   }).done(function(data) {
         var el = $(".appointments-row-"+data.id).css({'color':'red'});
             el.fadeOut(1000, function() { $(this).remove(); });
   }).fail(function() {
  });
});
