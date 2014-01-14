          /* //////////////////////////////////////////
	  * //////////////////////////////////////////
	  * Crud New/Edit Hospital
	  */////////////////////////////////////////////
	  $("body").delegate("a.crud-new-update-hospital", "click", function() {
		var el  = $(this);
		var id  = el.attr('data-id');
                var cid  = el.attr('data-cid');
		var path = '/consumer/hospitalized/';
                var action = id == '' ? 'new' : 'edit';
                var btn = '<input type="button" value="Submit" class="js-hospital-form-'+action+' btn" />';
		lightBox.show('mainModal', 'Add Hospital Info',
					  {'remote':path+action+'/'+id+'/',
					   'params': { id:id, cid:cid },
					   'callback':function(data) {
                                           $("#consumerhospitalizedform").append(btn);
                                           
                                                  var examDate  = $("#date-element");
                                                  
                                                  var dateField = $("#date");
                                                  dateField.attr('readonly','readonly' );
                                                  
                                                  var template  = _.template($('#date-template').html());	
                                                  var efield    = examDate.html();
                                                  examDate.html("");
                                                  
                                                  $("#date-element").append(template({start:'now',field:efield}));
                                                  $("#date-picker").datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev){});
                                                  $("#consumerexamform").append('<input type="button" value="Submit" class="js-exam-form-'+action+' btn" />');

                                           
                                           
                                           
					}},
					'get');
	  });  

          /*
	  * Crud Update hospital
	  */
	  $("body").delegate( ".js-hospital-form-new", "click", function() {
		Crud.Update('/consumer/hospitalized/new/','consumerhospitalizedform', function(data) {
		    var template = _.template($("#js-crud-edit-hospital").html());
                    $(".js-hospital-index tbody").prepend(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
                    lightBox.close('mainModal');  
		}, 'json' );
	  });
	 
          /*
	  * Crud Update hospital
	  */
	  $("body").delegate( ".js-hospital-form-edit", "click", function() {
		Crud.Update('/consumer/hospitalized/edit/','consumerhospitalizedform', function(data) {
		    var template = _.template($("#js-crud-edit-hospital").html());
                    $(".hospital-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(1000,function() {});          
                    lightBox.close('mainModal');  
		}, 'json' );
	  });

          /*
           Curd Delete hospital
          */
       $("body").delegate(".crud-del-hospital", "click", function() {
	   var el = $(this);
           var id =  el.attr('data-id');
           Crud.Confirm({ url: '/consumer/hospitalized/delete/',
                                    params: {id: id},
                                    title: 'Please confirm deleting of Hospital Visit Info.',
                                    text:  'The record will be completelly removed! Is it ok?',
                                    ok: 'Remove',
                                    cancel: 'Cancel'
             }).done(function(data) {
                   var el = $(".hospital-row-"+data.id).css({'color':'red'});
                       el.fadeOut(1000, function() { $(this).remove(); });
             }).fail(function() {
            });
	  }); 