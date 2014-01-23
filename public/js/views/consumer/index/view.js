$(function() {

    
        //load the medical appointment list.
        //content.load( '/consumer/medical/list', {id:'1'}, function(html){  console.log(html) }, 'html' );
        
     
        
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
      
      
      
        //assign payee
        $('.js-assign-person').click(function(){
            var ele = $(this);
            var id = ele.attr('person:id');
            lightBox.show('mainModal', 'Assign Person', {'remote':'/consumer/assign/person/'+id+'/type/payee'});
        });
          
        
        $("body").delegate("#js-consumer-person-btns a.js-assign-to-consumer", "click", function(event){         
            
            var ele = $(this);
            var par = ele.parent();
            var typ = par.attr('person:type');
            var act = ele.attr('person:action');
            var id = par.attr('consumer:id');
            var pid = par.attr('person:id');

            asyncAction.sendPost('/consumer/assign/person/'+id+'/type/'+typ, {'person':pid, 'do':act}, function(data){
                    if( data.success ){
                       var template = '<li class="consumer-<%= node %>-<%= id %>"> <%= name %></li>';
                        assignTo.update('person', data, template);
                         
                    }          
            });
        

        });
        
        

    
       $('.dropdown-toggle').dropdown();
        
    });
