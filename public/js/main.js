var lightBox = {
    path:null,
    div:null,
    callback:null,
     
    show:function(div, label, options, method) {   
        
	if (options.remote != undefined && this.path != options.remote) {
            
	    var params = options.params != undefined ? options.params : {};
            var reqm = (method != undefined) ? method : 'get'; 
            var send = (reqm.toLowerCase() == 'post') ? asyncAction.sendPost : asyncAction.sendGet;
            
	    this.callback = options.callback != undefined ? options.callback : null;
            this.path = options.remote;
            this.div = div;     
            
	    $("#"+div+"Label").html(label);
            $('#'+div+"Body").html( "loading..." );
            
	    send(this.path, params,  $.proxy(this.load, this), 'html');
                        
        }else{
            
           $('#'+div).modal('show');
        }   
    },
    
    load:function(data) {
	
	$('#'+this.div+"Body").html(data);
        $('#'+this.div).modal('show');
	
	if (typeof this.callback == 'function') {
	    this.callback.call(data);
	}
	
    },
    
    close:function(div)
    {
	$("#"+div).modal('hide');
	
    },
    
    dateFields:function() {
	
	 $(".date_widget").each(function(){
            
            var el = $(this);
                el.attr('readonly','readonly');

            var id = el.attr('id');
            var html = $("#"+id+"-element");

            var efield = html.html();
                         html.html("");
                                            
            var template = _.template($('#date-template').html());
            html.append(template({start:'now',field:efield, id:'date-picker'+id}));
	    $('#date-picker'+id).datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev){}); 
        });
    }
};

var Crud =  {
    
      scope:null,
      template:"",
      callback:false,
      
      Events:function(){
		
		/* //////////////////////////////////////////
		 * //////////////////////////////////////////
		 * Crud New/Edit Events
		 */////////////////////////////////////////////
		 $("body").delegate(".crud-create-update", "click", function() {
			  var crud = Crud.Info($(this));
			 $.proxy(lightBox.show('mainModal', crud.title,
							{'remote':crud.full_path,
							 'params': crud.params,
							 'callback':function(data) {
								$("#"+crud.form).append(crud.btn);
						  }},'get'), crud);
		 });  
	
		/*
		* Crud Update 
		*/
		$("body").delegate( ".crud-create", "click", function() {
			var el = $("#"+$(this).attr('data-bind'));
			var crud = Crud.Info(el, 'create');
			 Crud.Update(crud.path+"/create/", crud.form,  $.proxy(function(data) {
			  var template = _.template($("#"+crud.template).html());
			  $("#"+crud.target).prepend(template(data)).css({'display':'none'}).fadeIn(3000,function() {});          
			  lightBox.close('mainModal');  
			  },crud), 'json' );
			 
		});
	
		/*
		* Crud Update
		*/
		$("body").delegate( ".crud-update", "click", function() {
			  
			  var el = $("#"+$(this).attr('data-bind'));
			  var crud = Crud.Info(el, 'update');
			  
			  Crud.Update(crud.path+"/update/", crud.form,  $.proxy(function(data) {
				  var template = _.template($("#"+crud.template).html());
				  $("#"+crud.target+" .crud-row-"+data.id).html(template(data)).css({'display':'none'}).fadeIn(3000,function() {});          
				  lightBox.close('mainModal');  
			  },crud), 'json' );
		});
		
		/*
		 Curd Delete
		*/
		$("body").delegate(".crud-delete", "click", function() {
		 var el = $("#"+$(this).attr('data-bind'));   
		 var crud = Crud.Info(el, 'delete');
		 var params = Crud.paramsString($(this).attr('data-params'));
		 Crud.Confirm({ url: crud.path+"/delete/",
								  params: params,
								  title: 'Please confirm deleting of Allergy Info.',
								  text:  'The record will be completelly removed! Is it ok?',
								  ok: 'Remove',
								  cancel: 'Cancel'
		   }).done(function(data) {
				 var el = $("#"+crud.target+" .crud-row-"+data.id).css({'color':'red'});
					 el.fadeOut(1000, function() { $(this).remove(); });
		   }).fail(function() {
		  });
		});
		
	  },
	  	  
	  Info:function(el, act) {
    
		var params  = (el.attr('data-params') != undefined ) ? el.attr('data-params') : '';
		var action  = (el.attr('data-action') != undefined ) ?  el.attr('data-action') : act;
		var element = (el.attr('data-bind') != undefined ) ? $("#"+el.attr('data-bind')) : el;
		var info = { path: element.attr('data-path'),
					form: element.attr('data-form'),
					title: element.attr('data-title'),
					action: action,
					target: element.attr('data-target'),
					element: element.attr('id'),
					template: element.attr('data-template'),
					request:Math.round(new Date().getTime() / 1000),
					params:Crud.paramsString(params)};  
		
		info.btn = '<input type="button" value="Submit" data-bind="'+info.element+'" class="crud-'+info.action+'" />';
		info.full_path = info.path+"/"+info.action+'/crud/'+info.request+'/';
		
		return info;
                        
        },
      
    Create:function(path, data, callback, format) {	
		var params = Crud.params(data);
		$.post(path,
			   params,
			   callback,format).error(Crud.error);
    },

    Read:function(path, params, scope, el, template, callback, format ) {
		Crud.attr = scope;
		Crud.template = template;
		Crud.el = $("#"+el);
		Crud.callback = callback;
		Crud.el.html("");
		$.post(path, params, Crud.populate, format).error(Crud.error);
    },
      
    Update:function(path, data, callback, format) {
		var params = Crud.params(data);
		$.post(path,
			   params,
			   callback,format).error(Crud.error);
      },
      
    Delete:function(path, params, callback, format) {
		$.post(path,
			   params,
			   callback,format).error(Crud.error);
      },

    Confirm:function(options) {
		if (options == undefined || !options) { options = {}; }
	 
		var show = function(el, text) {
			if (text) { el.html(text); el.show(); } else { el.hide(); }
		}
	 
		var url = options.url ? options.url : '';
		var data = options.params ? options.params : '';
		var ok = options.ok ? options.ok : 'Ok';
		var cancel = options.cancel ? options.cancel : 'Cancel';
		var title = options.title
		var text = options.text;
		var dialog = $('#confirm-dialog');
		var header = dialog.find('.modal-header');
		var footer = dialog.find('.modal-footer');
	 
		show(dialog.find('.modal-body'), text);
		show(dialog.find('.modal-header h3'), title);
		footer.find('.btn-danger').unbind('click').html(ok);
		footer.find('.btn-cancel').unbind('click').html(cancel);
		dialog.modal('show');
	 
		var $deferred = $.Deferred();
		var is_done = false;
		
		footer.find('.btn-danger').on('click', function(e) {
			is_done = true;
			dialog.modal('hide');
			if (url) {	
			$.ajax({
				url: url,
				data: data,
				type: 'POST',
				}).done(function(result) {
				$deferred.resolve(result);
				}).fail(function() {
				$deferred.reject();	
			});
			} else {
			$deferred.resolve();
			}
		 });
	
		dialog.on('hide', function() {
			if (!is_done) { $deferred.reject(); }
		})
		 
		 return $deferred.promise();
    },      
  
    populate:function( data ) {
        
	var template = _.template(Crud.template);
	var attr = data;

	if (Crud.attr !== false) {
	  attr = data[Crud.attr];    
	}
	
	for (key in attr) {
	    Crud.el.append(template(attr[key]));
	}
	
	if (typeof Crud.callback == 'function') {
	    Crud.callback(data);
	}
	
      },
    
	params:function(data) {
		return (typeof data == 'object') ? data : $("#"+data).serialize();
	},
      
    paramsString:function(string) {
		var array = string != undefined && string.length > 0 ?  string.split(",") : false; 
		var values = {};
		if (array == false) { return {}; }
		_.map(array, function(v) {
			if (v != '') {
			var p = v.split(":");
			values[p[0]] = p[1];
			}
		});	
		return values; 
	},
      
    loadTemplates:function() {	
		$(".crud-load-template").each(function(){
			var el = $(this);
			var script = $('<script></script>');
				script.attr('type','text/template');
				script.attr('id',el.attr("data-template"));
				$("body").append( script );
				
				$.proxy( content.load('/js/templates/'+el.attr("data-template-path")+'.js',
									  null,
									  function(data){
										script.append(data);
									   },
									  'text'),  script);
				
		});
	},
	error:function(e) {
		console.log(e);
    } 
}

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
	
	appendToDom:function(ele, path, params, method, format) {
		var reqf = format !== undefined ? format.toLowerCase() : 'html'; 
		var reqm = method !== undefined ? method.toLowerCase() : 'get';
		var send = reqm == 'post' ? this.sendPost : this.sendGet;
		this.ele = ele;
		send( path, params, this.appendToDomComplete, format ); 
		return true;
	},
  
	appendToDomComplete:function(html) {
	
		$("#"+asyncAction.ele.id).append(html);
		if (typeof asyncAction.ele.callback == 'function') {
			asyncAction.ele.callback( asyncAction.ele.id, html );
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
            
        },
	
	escapeHtml:function(unsafe) {
	   return unsafe
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
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

        
    var synonyms = {
	
	init:function() {

	    //event for the close btn inside the pop-over
	    $("body").delegate(".js-synonyms-popover-destroy", "click", function(){
		
		var el = $(this);
		var id = el.attr('data-target');
		$("#"+id).popover('destroy');
			
	    });

	    
	    $( "body" ).delegate( ".js-synonyms", "click", function() {
		
		var el = $(this);
		var id = el.attr('data-target');
		var tr = $("#"+id);
		var vl = tr.val().trim();
		var ti = 'Synonyms <i data-target="'+id+'" class="js-synonyms-popover-destroy pull-right icon-remove pointer"></i>';
		try{ tr.popover('destroy'); }catch(e){}
		
		if (vl != '') {
		
		    tr.popover({html:true,
			       placement:'top',
			       title:ti,
			       trigger:'manual',
			       content:synonyms.checkForSynonyms(vl),
			       });
		    tr.popover('show');
		}
	    });
	    
	},
	
	
	checkForSynonyms:function(vl) {
	    
	    var s = this.knownSynonyms();
	    for(var key in s) {
	
		if( vl.indexOf(key.toString()) != -1 ) {
		    var regexp = new RegExp(key.toString(), "gi"); 
		    vl = vl.replace(regexp, '<abbr class="highlight" title="'+s[key]['synonyms'].join(", ")+'">'+key.toString()+'</abbr>' );
		}	
	    }       
	    return vl;	    
	},
	
	
	knownSynonyms:function() {
	    
	   return {
		assist: {
		    synonyms:['facilitate',
			      'aid',
			      'ease',
			      'expedite',
			      'spur',
			      'promote',
			      'boost',
			      'benefit',
			      'foster',
			      'encourage',
			      'stimulate',
			      'precipitate',
			      'accelerate',
			      'advance',
			      'further',
			      'forward']
		    },
		
		
		support: {
		    synonyms:
			  ['teach',
			  'train',
			  'couch',
			  'instruct',
			  'encourage',
			  'demonstrate',
			 'substantiate',
			 'back up',
			 'corroborate',
			 'confirm',
			 'attest to',
			 'verify',
			 'prove',
			 'validate',
			 'authenticate',
			 'endorse',
			 'ratify']
			  }
	    
	    };  
	    
	    
	}
	
    };
    
    
    
    /*
    * Functions called on all pages
    */
     $( function(){  
        synonyms.init();
		lightBox.dateFields();
		//Crud.loadTemplates();
		Crud.Events();
     });
   

    
        
