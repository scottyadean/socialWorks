         /* //////////////////////////////////////////
	  * //////////////////////////////////////////
	  * Crud New/Edit medication Form
	  */////////////////////////////////////////////
	  $("body").delegate("a.crud-new-update-medication", "click", function() {
		var el  = $(this);
		var id  = el.attr('data-id');
                var cid  = el.attr('data-cid');
		var path = '/consumers/medications/';
                var action = id == '' ? 'new' : 'edit';
                var btn = '<input type="button" value="Submit" class="js-medication-form-'+action+' btn" />';
		lightBox.show('mainModal', 'Add Medical Info',
					  {'remote':path+action+'/'+id+'/',
					   'params': { id:id, cid:cid },
					   'callback':function(data) {
                                           $("#consumers_pharmaceuticals_form").append(btn);
					}},
					'get');
	  });  

          /*
	  * Crud Update Medication
	  */
	  $("body").delegate( ".js-medication-form-new", "click", function() {
		Crud.Update('/consumers/medications/new/','consumers_pharmaceuticals_form', function(data) {
		    var template = _.template($("#js-crud-edit-medication").html());
                    $(".js-medication-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
                    lightBox.close('mainModal');  
		}, 'json' );
	  });
	 
          /*
	  * Crud Update Medication
	  */
	  $("body").delegate( ".js-medication-form-edit", "click", function() {
		Crud.Update('/consumers/medications/edit/','consumers_pharmaceuticals_form', function(data) {
		    var template = _.template($("#js-crud-edit-medication").html());
                    $(".medication-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
                    lightBox.close('mainModal');  
		}, 'json' );
	  });

          /*
           Curd Delete Medications
          */
       $("body").delegate(".crud-del-medication", "click", function() {
	   var el = $(this);
           var id =  el.attr('data-id');
           Crud.Confirm({ url: '/consumers/medications/delete/',
                                    params: {id: id},
                                    title: 'Please confirm deleting of Medication Info.',
                                    text:  'The record will be completelly removed! Is it ok?',
                                    ok: 'Remove',
                                    cancel: 'Cancel'
             }).done(function(data) {
                   var el = $(".medication-row-"+data.id).css({'color':'red'});
                       el.fadeOut(1000, function() { $(this).remove(); });
             }).fail(function() {
            });
	  });