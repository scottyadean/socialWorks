$(function(){
        $(".curd-del").click(function(){
      var id = $(this).attr('data-id'); 
      var url = $(this).attr('data-url');
      Crud.Confirm({
	url: url,
	params: {id: id},
	title: 'Please confirm deleting of data.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#crud-edit-"+data.id).parent().parent().remove();
		}).fail(function() {
		});
        });
    });