$(function(){
        $(".del-appointment").click(function(){
      var id = $(this).attr('data-id'); 
      
      Crud.Confirm({
	url: '/appointment/delete/',
	params: {id: id},
	title: 'Please confirm deleting of Medical Appointment.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#appointment-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });