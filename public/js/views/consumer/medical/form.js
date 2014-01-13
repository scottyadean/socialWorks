    $(function(){

    var dateEl = $("#date-element");
        var dateField = $("#date");
            dateField.attr('readonly','readonly' );

        var dfield    = dateEl.html();
                        dateEl.html("");
                                        
        var template = _.template($('#date-template').html());
             
        $("#date-element").append(template({start:'now',field:dfield, id:'date-picker'}));
        $("#date-picker").datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev){}); 
    
    
     var nextEl = $("#next-element");
        var nextField = $("#next");
            nextField.attr('readonly','readonly' );

        var nfield    = nextEl.html();
                        nextEl.html("");
                                        
        var template = _.template($('#date-template').html());
             
        $("#next-element").append(template({start:'now',field:nfield, id:'date-picker-next'}));
        $("#date-picker-next").datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev){});
    
    });