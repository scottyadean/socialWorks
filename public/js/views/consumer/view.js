$(function() {

    
        //load the medical appointment list.
        //content.load( '/consumer/medical/list', {id:'1'}, function(html){  console.log(html) }, 'html' );
        
        Notes = {
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
                                      'user_id':$("#js-add-new-note #user_id").val()  
               }));
        });
        
        /*cancel note edit*/
        $("body").delegate( ".update-note-cancel", "click", function() {
               var template = _.template($("#js-notes-text-template").html());
               var id = $(this).attr('data-id');
               var note = $("#note-undo-"+id).html();
               $(this).parent().parent().html(template({'id':id,'note':note}));
        });


        /*submit update note*/
        $("body").delegate( ".update-note-sub", "click", function() {
          
          var id = $(this).attr('data-id');
          
          Crud.Update('/consumer/notes/edit/'+$("#page-data-consumer-id").val(),
                      'update-note-'+id, function(data) {
                              var template = _.template($("#js-notes-text-template").html());
                              var el = $("#update-note-"+data.values.id).parent();
                              el.html(template({'id':data.values.id,'note':data.values.note}));
                         }, 'json');
        });
        
        
        $(".js-assign-physician").click(function(){
            
            var ele = $(this);
            var id = ele.attr('consumer:id');
            lightBox.show('mainModal', 'Assign Physician', {'remote':'/consumer/assign/physicians/'+id});

        });
        
        $('.js-assign-coordinator').click(function(){
            var ele = $(this);
            var id = ele.attr('consumer:id');
            lightBox.show('mainModal', 'Assign Service Coordinator', {'remote':'/consumer/assign/coordinators/'+id});
        });
        
        
       $(".js-assign-pharmaceutical").click(function(){
            
            var ele = $(this);
            var id = ele.attr('consumer:id');
            lightBox.show('mainModal', 'Assign Physician', {'remote':'/pharmaceuticals/assign/id/'+id});

        });
        
        
        $(".js-edit-assignee").click(function(){
            var ele = $(this);
            var id = ele.attr('consumer:id');
            lightBox.show('mainModal', 'Assign Employee', {'remote':'/consumer/assignees/'+id});
            
        });
    
        $( ".js-update-img" ).click( function(){

            var ele = $(this);
            var id = ele.attr('consumer:id');
            var tp = ele.attr('consumer:type');            
            lightBox.show('mainModal', 'Add Image', {'remote':'/image/db/create/'+id+'/'+tp+'/callback/imgUpload.complete'});
           
         });

    
         $("body").delegate('a.js-append-info-to', "click", function(event) {
                         
                var ele = $(this);
                var id  = ele.attr('append:id');
                var url = ele.attr('append:url');
                var div = ele.attr('append:div');

                asyncAction.sendPost(url+id, {}, function(html){
                        $( "#"+ div ).html( html );
                }, 'html');
                
         
         } );
            
          //to add employees        
          $("body").delegate("#js-consumer-user-links a.js-assign-to-consumer", "click", function(event){         
            
            var ele = $(this);
            var par = ele.parent();
            var act = ele.attr('consumer:action');
            var cid = par.attr('consumer:id');
            var uid = par.attr('consumer:user');
            
            asyncAction.sendPost('/consumer/assignees/'+cid, {'user':uid, 'do':act}, function(data){
            
                    if( data.success ){ 
                        
                        var template = '<li class="consumer-<%= node %>-<%= id %>"> <%= name %> </li>';
                        assignTo.update('user', data, template);
                         
                    }          
            });
        

        });
          
         
 
         
         //assign medical doctors
          $("body").delegate("#js-consumer-physicians-btns a.js-assign-to-consumer", "click", function(event){         
            
 
            var ele = $(this);
            var par = ele.parent();
            var act = ele.attr('consumer:action');
            var cid = par.attr('consumer:id');
            var pid = par.attr('consumer:physician');
            
            asyncAction.sendPost('/consumer/assign/physicians/'+cid, {'physician':pid, 'do':act}, function(data){
            
                    if( data.success ){
                        
                        var template = '<tr class="consumer-<%= node %>-<%= id %>"><td> <%= name %> </td><td> <%= phone %>  </td></tr>';
                        assignTo.update('physician', data, template);
                         
                    }          
            });
        

        });
        
        
         //assign medications. /
          $("body").delegate("#js-consumer-pharmaceuticals-btns a.js-assign-to-consumer", "click", function(event){         
            
 
            var ele = $(this);
            var par = ele.parent();
            var act = ele.attr('consumer:action');
            var cid = par.attr('consumer:id');
            var pid = par.attr('consumer:pharmaceutical');
            
            asyncAction.sendPost('/pharmaceuticals/assign/id/'+cid, {'pharmaceutical':pid, 'do':act}, function(data){
            
                    if( data.success ){
                        
                        var template = '<tr class="consumer-<%= node %>-<%= id %>"><td> <%= name %> </td><td> <%= phone %>  </td></tr>';
                        assignTo.update('pharmaceutical', data, template);
                         
                    }          
            });
        

        });
        
        
        
         //assign service Coordinator. /
          $("body").delegate("#js-consumer-coordinators-btns a.js-assign-to-consumer", "click", function(event){         
            
 
            var ele = $(this);
            var par = ele.parent();
            var act = ele.attr('consumer:action');
            var id = par.attr('consumer:id');
            var cid = par.attr('consumer:coordinator');
            
            asyncAction.sendPost('/coordinator/assign/id/'+id, {'coordinator':cid, 'do':act}, function(data){
            
                    if( data.success ){
                        var template = '<li class="consumer-<%= node %>-<%= id %>"> <%= name %> </li>';
                        assignTo.update('coordinator', data, template);
                         
                    }          
            });
        

        });
        
        
        
        
        
        
        
        
        
        

    });
