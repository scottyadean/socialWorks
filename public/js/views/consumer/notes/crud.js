
var  Notes = {
    
    
          MapToCal:function(data) {                    
               
               $("td").removeClass('event-on-calendar');
               
               $(".date-created").each(function(){
                    var date = $(this).html().trim();
                    $("td[data-day='"+date.substring(10,8)+"']").addClass('event-on-calendar');
               });
          },
          
          
          MapToCalByDay:function(date, action) {
               
              if (action !== undefined || action == 'set') {
                    
                         $("td[data-day='"+date.substring(10,8)+"']").addClass('event-on-calendar');
          
                    
               }else{
                    
                         $("td[data-day='"+date.substring(10,8)+"']").removeClass('event-on-calendar');
                    
               }
               
          }
          
        }


        //Create
        $("#new-note-sub").click(function() {
           Crud.Create('/consumer/notes/create/cid/'+$("#page-data-consumer-id").val(),
                      'js-add-new-note', function(data) {
                         var template = _.template($("#js-notes-template-crud").html());
                         $("#js-case-note-data").prepend(template(data));
                         Notes.MapToCalByDay(data.values.created, 'set');
                         }, 'json' );
        });
        
        var _cal = $("#js-calendar");
        var _year = _cal.attr('data-year');
        var _month = _cal.attr('data-month');
        
        //Read
        Crud.Read('/consumer/notes/notes-by-date/cid/'+$("#page-data-consumer-id").val(),
                  {'month':_month,'year':_year},
                  false,
                  'js-case-note-data',
                  $("#js-notes-template").html(),
                  Notes.MapToCal,
                  'json');
 
           
 
        //sort by date       
        $("body").delegate( ".js-notes-day", "click", function() {
               
               //get the current date
               var ele = $("#js-calendar");
               var y = ele.attr('data-year');
               var m = ele.attr('data-month');
               var d = $(this).attr('data-day');
               
               //remove the highlight form the calendar
               $("#js-calendar td.highlight").each(function(){
                      $(this).removeClass('highlight');
                    });
               
               //add a highlight to the current day on the calendar
               $(this).addClass('highlight');
               
               //request the notes form the db that match the date     
               Crud.Read('/consumer/notes/notes-by-date/cid/'+$("#page-data-consumer-id").val(),
                  {'year':y, 'month':m, 'day':d},
                  false,
                  'js-case-note-data',
                  $("#js-notes-template").html(),
                  false,
                  'json');
               
               //let the user know that they will be creating a new note under the current date
               //selected
               $("#js-add-note-info").html( '<strong>Create a note under this date ( ' +m+ '-' +d+ '-' +y+' )<strong>'  );
               
               //create a button to show all notes for the current month and year
               var btn = '<a class="js-show-all-notes-btn pointer" data-month='+m+' data-year='+y+'> Show all notes for '+$("#js-calendar-monthName").html()+'</a>';
               $("#js-show-all-notes-for-year-month").html( btn );
               
               //update the create date
               $("#js-add-new-note #created").val( y+ '-' +m+ '-' +d);
               
               
               
               
        });
        
        //show all notes by month
        $("body").delegate(".js-show-all-notes-btn", 'click', function() {  
               //get the next prev
               var el = $(this);
               
               //get the current date
               var m = el.attr('data-month');
               var y = el.attr('data-year');
               
                 //remove the highlight form the calendar
               $("#js-calendar td.highlight").each(function(){
                      $(this).removeClass('highlight');
               });
               
               el.remove();
               
               $("#js-add-note-info").html('Create a note for todays date');
               
               //request the notes form the db that match the date     
               Crud.Read('/consumer/notes/notes-by-date/cid/'+$("#page-data-consumer-id").val(),
                  {'month':m, 'year':y},
                  false,
                  'js-case-note-data',
                  $("#js-notes-template").html(),
                  Notes.MapToCal,
                  'json');
        });
        
        //on cal next show next month
        $("body").delegate( ".js-calendar-nextprev", "click", function() {
               
               //get the next prev
               var el = $(this);
               
               //get the current date
               var m = el.attr('data-month');
               var y = el.attr('data-year');
               
               //request the notes form the db that match the date     
               Crud.Read('/consumer/notes/notes-by-date/cid/'+$("#page-data-consumer-id").val(),
                  {'month':m, 'year':y},
                  false,
                  'js-case-note-data',
                  $("#js-notes-template").html(),
                  false,
                  'json');
               
               //let the user know that they will be creating a new note under the current date
               //selected
               $("#js-add-note-info").html( '<strong>Create a note under this date ( '+m+'-'+y+' )<strong>'  );
               
               //reload the calendar
               content.load( '/tools/calendar', {month:m, year:y},
                             function(html){  $("#js-calendar-wrapper").html(html);
                             
                             Notes.MapToCal(null);
                             
                             }, 'html' );
               
        });
        
 
        
     


   