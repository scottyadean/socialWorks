$(function(){
    
     $("body").delegate(".curd-del", "click",function(){
      var id = $(this).attr('data-id'); 
      var url = $(this).attr('data-url');
     
      Crud.Confirm({
	url: url,
	params: {id: id},
	title: 'Please confirm deleting of Agency.',
	text:  'The record will be completelly removed! Is it ok?',
	ok: 'Remove',
	cancel: 'Cancel'
		}).done(function(data) {
			$("#crud-item-"+data.id).remove();
		}).fail(function() {
		});
        });
    
    /*
     $("body").delegate(".pagination a", "click", function(event) {
	event.preventDefault();   
        var el  = $(this);
        
            Crud.Html(el.attr('href'), {'crudPaging':false}, function(data){
            $('#crud-index').html(data);
            
        }, 'html');
        
     });
    */
    });