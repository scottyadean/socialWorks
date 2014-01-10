$(function(){
	  /*
	  * Crud New/Edit Medical Exam Form
	  */
	  $("body").delegate("a.crud-new-update-med", "click", function() {
		
		var el  = $(this);
		var cid = el.attr('data-cid');
		var id  = el.attr('data-id');
		var path = '/consumer/exam/';
		var action =  id != '' ? 'edit': 'new';
		
		lightBox.show('mainModal', 'Add Medical Info',
					  {'remote':path+action+'/'+id+'/',
					   'params': { cid:cid, id:id },
					   'callback':function(data) {
			    
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
	  * Crud Create Med Submit
	  */
	  $("body").delegate( ".js-exam-form-new", "click", function() {
		Crud.Create('/consumer/exam/new/',
			'consumerexamform', function(data) {
				var template = _.template($("#js-crud-edit-med").html());
				$(".js-med-index").prepend("<tr class='exam-row-"+data.id+" highlight-child'>"+template(data)+"</tr>");
                                lightBox.close('mainModal');
		}, 'json' );
	  });
	
	 /*
	  * Crud Update Med Submit
	  */
	  $("body").delegate( ".js-exam-form-edit", "click", function() {
		Crud.Update('/consumer/exam/edit/',
			'consumerexamform', function(data) {
				var template = _.template($("#js-crud-edit-med").html());
				var el = $(".exam-row-"+data.id);
			      	el.html(template(data));
					el.css({'display':'none'});
					el.fadeIn(1000, function() {  });
				$("#mainModalLabel").html("<i class='alert alert-success'>Medical Info Updated</i>");
                                lightBox.close('mainModal');
                                
		}, 'json' );
	  });

	  /*
	   Curd Delete
	  */
      $("body").delegate(".crud-del-med", "click", function() {
		 
		 var el = $(this);
         var id =  el.attr('data-id');
         var cd = el.attr('data-cid');
		 
		 Crud.Confirm({ url: '/consumer/exam/delete/',
					   params: {id: id, cid:cd },
					   title: 'Please confirm deleting of Medical Info.',
					   text:  'The record will be completelly removed! Is it ok?',
					   ok: 'Remove',
					   cancel: 'Cancel'
		  }).done(function(data) {
			  var el = $("#med-"+data.id).closest('tr');
			      el.css({'color':'red'});
			      el.fadeOut(1000, function() { $(this).remove(); });
		  }).fail(function() {
		  });
		  });
	  
});
