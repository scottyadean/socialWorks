    $(function(){

        
        
        var dateEl = $("#date-element");
        var dateField = $("#date");
            dateField.attr('readonly','readonly' );

        var dfield    = dateEl.html();
                        dateEl.html("");
                                        
        var template = _.template($('#date-template').html());
             
        $("#date-element").append(template({start:'now',field:dfield}));
        $("#date-picker").datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev){}); 
    
    
    });