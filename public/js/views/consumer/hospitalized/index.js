$(function(){
        $(".del-hospitalized").click(function(){
      var id = $(this).attr('data-id'); 
      
      Crud.Confirm({
	url: '/consumer/hospitalized/delete/',
	params: {id: id},
	title: 'Please confirm deleting of Hospitalized info.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#hospitalized-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });