$(function(){
        $(".del-coordinator").click(function(){
      var id = $(this).attr('data-id'); 
      
      Crud.Confirm({
	url: '/coordinator/delete/',
	params: {id: id},
	title: 'Please confirm deleting of Coordinator.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#coord-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });