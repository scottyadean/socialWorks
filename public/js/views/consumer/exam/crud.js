
  /*
  if checked set the exam element background
  */
  $("body").delegate("[name='exams[]']", "click", function() {
        var el = $(this)
            el.closest('tr').css({'background-color': el.prop("checked") == true ? '#D6FFB7' : '#FFFFFF'});
  });
  


  /*
  Select all exams 
  */
  $(".js-select-all-exams").click(function() {

      var el = $(this)
      var at = el.attr('data-status');    
      at == 'n' ? el.attr('data-status', 'y') : el.attr('data-status', 'n');
      
      $("table.js-exams-index input[type=checkbox]").each(function() {
            var input = $(this);
            at == 'n' ? input.prop("checked", "checked") : input.removeProp("checked");
            input.closest('tr').css({'background-color': at == 'n' ? '#D6FFB7' : '#FFFFFF'})
      });
      
  });
  
  //if the box was checked before check it again:
  Exam ={
  
    checkbox:function(id){
       var el = $(".crud-row-"+id);
       var ck = el.find('input[type=checkbox]');
       var checked = ck.prop('checked') == true ? 'checked="checked"' : '';
       /*
       if (checked) {
                $(this).css({'background-color':'#D6FFB7'});
             }else{
                $(this).css({'background-color':'#FFFFFF'});       
             }
             
             */
    }
  
  }; 

      