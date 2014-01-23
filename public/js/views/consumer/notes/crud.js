
        $('body').delegate('.js-goals-suggestions', 'click', function(){
            
            var ele = $(this);
            var form = ele.attr('data:form');
            var name = ele.attr('data:namespace');
            $("#"+form +" #goal_id"+name).val(ele.attr('goal:id'));
            $("#"+form +" #goal-to-notes"+name).val(ele.html().trim());
            $("#"+form +" #goal-to-notes"+name).attr("data-value", ele.html().trim());
            ele.closest("ul.dropdown-menu").html("").hide();
            
        });
        
        $('body').delegate(".goal-to-notes", "keyup",  function() {
            var val = $(this).val().trim();
            var cid = $("#consumer_id").val();
            var api = $(this).attr('data-append');
            var form = $(this).attr('data-form');
            var appendTo = $("#js-goals-suggestions-select"+api);
            if (val != '') {
                    appendTo.html("").show();
                    $.proxy(asyncAction.sendPost( '/consumer/async/search-goals', {'consumer_id': cid, 'search':val },
                        function(data){
                            for (var i = 0; i<data.length; i++) {
                              var goal = data[i].goal;
                                  appendTo.append('<li><a class="js-goals-suggestions" data:form="'
                                                  +form+'" data:namespace="'+api+'" goal:id="'+data[i].id+'">'+goal+'</a></li>'); 
                            }
               
                            if (data.length == 0) {
                                 appendTo.hide();
                            }}, 'json'), appendTo, form, api);
            }else{
                appendTo.html("").hide();
            }
        });
        
        
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
        
        var _cal = $("#js-calendar");
        var _year = _cal.attr('data-year');
        var _month = _cal.attr('data-month');
   
        Crud.Read('/consumer/notes/notes-by-date/'+$("#page-data-consumer-id").val(),
                  {'month':_month,'year':_year},
                  false,
                  'js-case-note-data',
                  $("#js-notes-template").html(),
                  Notes.MapToCal,
                  'json');
        
        
        $("#new-note-sub").click(function() {
           Crud.Create('/consumer/notes/new/'+$("#page-data-consumer-id").val(),
                      'js-add-new-note', function(data) {
                         var template = _.template($("#js-notes-template").html());
                         $("#js-case-note-data").prepend(template(data.values));
                         Notes.MapToCalByDay(data.values.created, 'set');
                         }, 'json' );
        });
        
        $("body").delegate( "td i.note-del", "click", function() {
          var ele = $(this);
          var nid = ele.attr('data-id'); 
          Crud.Delete('/consumer/notes/delete/'+$("#page-data-consumer-id").val(),
                      {id:nid},
                      function(data) {
                         var res = $(".consumer-note-"+data.id).remove();
                         }, 'json' );
        });
        
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
               Crud.Read('/consumer/notes/notes-by-date/'+$("#page-data-consumer-id").val(),
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
               Crud.Read('/consumer/notes/notes-by-date/'+$("#page-data-consumer-id").val(),
                  {'month':m, 'year':y},
                  false,
                  'js-case-note-data',
                  $("#js-notes-template").html(),
                  Notes.MapToCal,
                  'json');
        });
        
        
        $("body").delegate( ".js-calendar-nextprev", "click", function() {
               
               //get the next prev
               var el = $(this);
               
               //get the current date
               var m = el.attr('data-month');
               var y = el.attr('data-year');
               
               //request the notes form the db that match the date     
               Crud.Read('/consumer/notes/notes-by-date/'+$("#page-data-consumer-id").val(),
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
        
        /*Edit note*/
        $("body").delegate( ".js-edit-note-text", "click", function() {
               var template = _.template($("#js-notes-edit-template").html());
               $(this).parent().html(template({'id':$(this).attr('data-id'),
                                      'note':$(this).html().trim(),
                                      'consumer_id': $("#page-data-consumer-id").val(), 
                                      'user_id':$("#js-add-new-note #user_id").val(),
                                      'goal':$(this).attr('data-goal'),
                                      'goal_id':$(this).attr('data-goal_id')
               }));
        });
        
        /*cancel note edit*/
        $("body").delegate( ".update-note-cancel", "click", function() {
               var id = $(this).attr('data-id');
               var note = $("#note-undo-"+id).html();
               $(this).parent().parent().html(note);
        });


        /*submit update note*/
        $("body").delegate( ".update-note-sub", "click", function() {
          
          var id = $(this).attr('data-id');
            //-update-<%= id %>
           var params = {};              
           var values = $("#update-note-"+id).serializeArray();
           for( var i = 0; i<values.length; i++ ) {
            
            console.log(values[i].name+" "+values[i].value);
            
            var cleanName = values[i].name.replace("-update-"+id, '');
            
            params[cleanName] = values[i].value;
            
           }
    
          Crud.Update('/consumer/notes/edit/'+$("#page-data-consumer-id").val(),
                      params, function(data) {
                        
                            if (data.success != false) {
                                
                              var template = _.template($("#js-note-update-template").html());
                              var el = $(".consumer-note-"+data.id);
                                  el.html(template(data.values));
                              
                             }
  
                         }, 'json');
        });