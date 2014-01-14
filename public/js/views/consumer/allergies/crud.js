          /* //////////////////////////////////////////
	  * //////////////////////////////////////////
	  * Crud New/Edit allergy
	  */////////////////////////////////////////////
	  $("body").delegate("a.crud-new-update-allergy", "click", function() {
		var el  = $(this);
		var id  = el.attr('data-id');
                var cid  = el.attr('data-cid');
		var path = '/consumers/allergies/';
                var action = id == '' ? 'new' : 'edit';
                var btn = '<input type="button" value="Submit" class="js-allergy-form-'+action+' btn" />';
		lightBox.show('mainModal', 'Add Medical Info',
					  {'remote':path+action+'/'+id+'/',
					   'params': { id:id, cid:cid },
					   'callback':function(data) {
                                           $("#consumerallergiesform").append(btn);
					}},
					'get');
	  });  

          /*
	  * Crud Update allergy
	  */
	  $("body").delegate( ".js-allergy-form-new", "click", function() {
		Crud.Update('/consumers/allergies/new/','consumerallergiesform', function(data) {
		    var template = _.template($("#js-crud-edit-allergy").html());
                    $(".js-allergy-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
                    lightBox.close('mainModal');  
		}, 'json' );
	  });
	 
          /*
	  * Crud Update allergy
	  */
	  $("body").delegate( ".js-allergy-form-edit", "click", function() {
		Crud.Update('/consumers/allergies/edit/','consumerallergiesform', function(data) {
		    var template = _.template($("#js-crud-edit-allergy").html());
                    $(".allergy-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
                    lightBox.close('mainModal');  
		}, 'json' );
	  });

          /*
           Curd Delete allergy
          */
       $("body").delegate(".crud-del-allergy", "click", function() {
	   var el = $(this);
           var id =  el.attr('data-id');
           Crud.Confirm({ url: '/consumers/allergies/delete/',
                                    params: {id: id},
                                    title: 'Please confirm deleting of Allergy Info.',
                                    text:  'The record will be completelly removed! Is it ok?',
                                    ok: 'Remove',
                                    cancel: 'Cancel'
             }).done(function(data) {
                   var el = $(".allergy-row-"+data.id).css({'color':'red'});
                       el.fadeOut(1000, function() { $(this).remove(); });
             }).fail(function() {
            });
	  });     