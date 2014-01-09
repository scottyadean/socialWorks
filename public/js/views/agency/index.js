$(function(){
        $(".del-agency").click(function(){
      var id = $(this).attr('data-id'); 
      
      Crud.Confirm({
	url: '/agency/delete/',
	params: {id: id},
	title: 'Please confirm deleting of Agency.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#agency-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });