define(function(){
	var koolahToolkit = {
    
		/**
		 * center
		 * - centers a dom element 
		 * @param jQuery dom object $el - element to be centered
		 * @param jQuery dom object $to - where $el should be centered
		 * @param string type - optional -absolute|fixed
		 * @returns bool - heightToo - optional - center on the y axis as well
		 */
		center: function( $el, $to, type, heightToo ){
		    if ( !type )
		        type = 'absolute';
		    
		    var elWidth = $el.width();
		    var elHeight = $el.height();
		    
		    var margin_left = parseInt( elWidth / 2  ) * -1;
		    
		    $el.css({
		        position: type,
		        left: '50%',
		        'margin-left': margin_left
		    });
		    
		    if ( heightToo ){
		        var toHeight = $to.height();
		        var top = parseInt((toHeight - elHeight) / 2); 
		    
		        $el.css({
		            top: top,
		       }); 
		    }
		},
		
		/**
		 * getExtFromFilename
		 * - get extention from a file name 
		 * @param string filename
		 * @returns string
		 */
		getExtFromFilename: function (filename){
		    ext = filename.split('.');
		    return ext[ (ext.length -1) ].toLowerCase(); 
		},
		
		/**
		 * UID
		 * 
		 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
		 * @package koolah\cms\public\js\objects\elements\tools
		 */
		UID: function(){
		    var uid =  String( (new Date().getTime() )+Math.random()*Math.random() );
		    uid  = uid.replace( /\./g, "" );
		    return uid;    
		},
		
		/**
		 * setModelFromForm
		 * 
		 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
		 * @package koolah\cms\public\js\objects\elements\tools
		 */
		formToAssoc: function($form){
			var assoc = {};
		    $form.find('input:not(:submit):enabled, select:enabled, textarea:enabled').each(function(){
		    	var name = $(this).prop('name');
		    	if (name){
			    	var val = _.escape($(this).val());
			    	if (name.indexOf('[]') >= 0 ){
			    		name = name.replace('[]', '');
			    		console.log(name);
			    		if (!assoc[ name ])
			    			assoc[ name ] = [];
			    		if ($(this).is(':checkbox') || $(this).is(':radio')){
			    			if ($(this).is(':checked'))
			    				assoc[ name ].push(val);
			    		}
			    		else
			    			assoc[ name ].push(val);
			    	}
			    	else{
			    		if ($(this).is(':checkbox') || $(this).is(':radio')){
			    			if ($(this).is(':checked'))
			    				assoc[ name ] = val;
			    		}
			    		else
			    			assoc[ name ] = val;
			    	}
		    	}	
		    });
console.log(assoc);		    
		    return assoc;
		},
		
		resetMsg: function( $el ){
			$el.removeClass('error').removeClass('success').html('');
		},
		
		successMsg: function( $el ){
			koolahToolkit.resetMsg( $el );
			$el.addClass('success').html( 'Success' ).show();  
		    setTimeout(function(){
              $el.fadeOut(1500);
          	}, 2000);
		},
		
		errorMsg: function( $el, msg, fadeout ){
			koolahToolkit.resetMsg( $el );
			$el.addClass('error').html( 'Error: '+msg ).show();
			if ( fadeout ){
		    	setTimeout(function(){
                  $el.fadeOut(1500);
              	}, 2000);
		    }
		},
		
		ucFirst: function(s){
			if (!s)
				return '';
			return s.charAt(0).toUpperCase() + s.substr(1);
		},
	}
	return koolahToolkit;
})