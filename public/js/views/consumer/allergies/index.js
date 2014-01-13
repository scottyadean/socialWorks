$(function(){
        $(".del-allergy").click(function(){
      var id = $(this).attr('data-id'); 
      
      Crud.Confirm({
	url: '/consumers/allergies/delete/',
	params: {id: id},
	title: 'Please confirm deleting of insurance info.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#allergy-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });