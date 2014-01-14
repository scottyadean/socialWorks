    $(function(){

        var examdate = $("#date-element");
        var examField = $("#date");
            examField.attr('readonly','readonly' );

        var efield    = examdate.html();
                        examdate.html("");
                                        
        var template = _.template($('#date-template').html());
             
        $("#date-element").append(template({start:'now',field:efield}));
        $("#date-picker").datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev){}); 
    });