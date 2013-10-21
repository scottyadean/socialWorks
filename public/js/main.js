var lightBox = {


    path:null,
    div:null,
    
    show:function(div, label, options, method) {
        
        if (options.remote != undefined && this.path != options.remote) {
       
            var params = options.params != undefined ? options.params : {};

            this.path = options.remote;
            this.div = div;     

            $("#"+div+"Label").html(label);
            $('#'+div+"Body").html( "loading..." );
                        
            var reqm = (method != undefined) ? method : 'get'; 
            var send = (reqm.toLowerCase() == 'post') ? asyncAction.sendPost : asyncAction.sendGet;
            send(this.path, params,  lightBox.load, 'html');
                        
        }else{
            
           $('#'+div).modal('show');
        }
       
    },
    
    load:function(data) {
        
        $('#'+lightBox.div+"Body").html(data);
        $('#'+lightBox.div).modal('show');
        
    }


};

var content = {
    
        load:function(path, params, callback, format) {
            
             asyncAction.sendPost(path, params, callback, format);
        }
    
    }; 


var asyncAction = {
    
    ele:{},
        
    
    sendPost:function( path, params, callback, format) {
        
        
        var reqf = format !== undefined ? format : 'json'; 
        
        $.post(path,
	    params,
	    callback,
	    reqf).error(function(e){ console.log(e); });
        
    },
    
    
    sendGet:function( path, params, callback, format) {
     
     
     var reqf = format !== undefined ? format : 'json'; 
     
     $.get(path,
         params,
         callback,
         reqf).error(function(e){ console.log(e); });
     
 },
 
 
 
  appendToDom:function(ele, path, params, method, format ) {
    
     var reqf = format !== undefined ? format.toLowerCase() : 'html'; 
     var reqm = method !== undefined ? method.toLowerCase() : 'get';
     var send = reqm == 'post' ? this.sendPost : this.sendGet;
     this.ele = ele;
     send( path, params, this.appendToDomComplete, format );
    
    
  },
  
  
  appendToDomComplete:function(html) {
    
        $("#"+asyncAction.ele.id).append(html);
        
        if (typeof asyncAction.ele.callback == 'function') {
            asyncAction.ele.callback( asyncAction.ele.id );
        }
        
  }
    
    
    
    
};




var imgUpload = {
     
         complete:function(success, msg){
             
             if (success) {

                 var timestamp = new Date().getTime();
                 var refresh = $("#js-update-img").attr('src')+'/'+timestamp;
                 $('.js-update-img').attr('src', refresh);
                
             }else{
                 
                 $('#img-upload-form-msg').addClass('alert alert-error');
                 $('#img-upload-form-msg').html( this.error(msg) );
             }
                            
         },
        
         error:function(key){
             
             var errs = {};
                 errs.fileMimeTypeFalse = 'Allowed file types, gif and jpg';
                 errs.fileUploadErrorNoFile = 'File field can not be empty'; 
             
             if (errs[key] != undefined) {
                 return errs[key];
             }
             
             
             return key;
             
         }
     
     };
     
  var assignTo = {
     
     
     update:function(prefix, data, html ){
         
         
         var par = "#"+prefix+"_"+data.focus;
         var div = $(par);
         
         var btnOn    = 'js-'+prefix+'-remove-link';
         var btnOff   = 'js-'+prefix+'-assign-link';
         var styleOn  = 'btn-success';
         var styleOff = 'btn-warning';
         
         
         if(data.do == "assign") {
             var template = _.template(html);
             var params   = {name:div.attr(prefix+':name'), phone:div.attr(prefix+':phone'), id:data.focus, node:prefix};
             $("#consumer-"+prefix+"-list").append(template(params));
         
         }else{
         
             btnOn   = 'js-'+prefix+'-assign-link';
             btnOff  = 'js-'+prefix+'-remove-link';
             styleOn  = 'btn-warning';
             styleOff = 'btn-success';
             $(".consumer-"+prefix+"-"+data.focus).remove();
         
         }
         
         
         $(par+" ."+btnOn).removeClass('hidden');
         $(par+" ."+btnOff).addClass('hidden');
         $(par+" .js-assign-to-consumer-btn" ).addClass(styleOn);
         $(par+" .js-assign-to-consumer-btn" ).removeClass(styleOff);    
     
     }
     
 };
 
 
 
     var helpers = {
        
      
        editIcon:function(element){
            
              var ele = $(element);

            //onmouse over    
            ele.mouseenter(
                function () {
                     ele.find("div:last").append($("<p class='pull-right'><i class='icon-edit'> </i> <small>edit</small></p>"));
                });
             //on mouse out
             ele.mouseleave( 
                function () {
                     ele.find("p:last").remove();
                }
            );
            
        }  
        
    }; 

/*
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src='//www.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
        




*************************

          //add this to the element you want to add the edit icon to
          //class="js-display-edit-icon" consumer:id="<?=$consumer->id?>"
          $(".js-display-edit-icon").each( function(){ 
            
            var ele = $(this);
            //onclick
            ele.click( 
                function() { 
                     window.location = "/consumer/edit/"+ele.attr('consumer:id');
                });
            //onmouse over    
            ele.mouseenter(
                function () {
                     ele.find("div:last").append(
                     $("<p class='pull-right'><i class='icon-edit'> </i> <small>edit</small></p>"));
                });
             //on mouse out
             ele.mouseleave( 
                function () {
                     ele.find("p:last").remove();
                }
            );
            
       });
               
 */       

/* simple chat widget
   requires the dom elements chat-send, chat-key-enter, chat-msg
*/
var Chat = {
        el:null,
        offset:0,
 
        init:function(id) {
            
            Chat.el = $("#chat-result");            
            Chat.offset = parseInt(id);
            Chat.scroller();
                
            $(".chat-send").click(function() {
                Chat.input();      
            });
            
            $(".chat-key-enter").keyup(function(event){
                if(event.keyCode=='13') {
                   Chat.input();
                }
            });

            setTimeout("Chat.append()", 5000);
        },

        input:function() {
            
            var val = $('#chat-msg').val().trim();
            
            if (val != '') {
                Chat.offset++;
                $.ajax({
                    type: "POST",
                    url: "/tools/async/chat-input",
                    data: {msg:val},
                    success: Chat.inputOk,
                    dataType: 'json'
                });
            }
        },
        
        inputOk:function(rsp) {
            if (rsp.success == 1) {
                Chat.template(rsp.data.pointer, rsp.data.data);
                $("#chat-msg").val("").focus();    
            }
        },
        
        append:function(){
            try {
                $.ajax({
                    type: "POST",
                    url: "/tools/async/chat",
                    data: {offset:Chat.offset},
                    success: Chat.appendOk,
                    dataType: 'json'
                });
                        
            } catch(e) {
                setTimeout("Chat.append()", 5000);
            }
            
        },
        
        appendOk:function(msg) {
                
                var mg = msg.messages;
                var id = Chat.offset;
                
                for ( var i in mg ) {
                  Chat.template(mg[i].pointer, mg[i].data);
                  id = mg[i].id;
                }
                
                Chat.offset = id;    
                setTimeout("Chat.append()", 5000);
        },


        template:function(name, message) {
          Chat.el.append(name + " "+ message+ "<br/>");
          Chat.scroller();  
        },        
        
        scroller:function() {
            Chat.el.animate({ scrollTop: document.getElementById('chat-result').scrollHeight}, 1000);   
        }
    };



