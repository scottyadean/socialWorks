$(function(){
        $(".del-insurance").click(function(){
      var id = $(this).attr('data-id'); 
      
      Crud.Confirm({
	url: '/consumer/insurance/delete/',
	params: {id: id},
	title: 'Please confirm deleting of insurance info.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#insurance-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });