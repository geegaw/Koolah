var RULE_MIN_LEN = 6;
var RULE_MAX_LEN = 12;
var RULE_MIN_NUMS = 1;

$(document).ready(function(){
	
	$('.error input, .error select, .error textarea').live('focus', function(){
	    var $parent = $(this).parents('.error:first');
		$parent.removeClass( 'error' );
		$parent.find('.errorBorder').removeClass('errorBorder');
    });
	
	$('.required').blur(function(){
        new FormTYPE( null, null ).validateField( $(this) );
    });
    
    $('.email, input[type=email]').blur(function(){
        new FormTYPE( null, null ).validateEmail( $(this) );
    });
    
    
    $('.number').blur(function(){
        new FormTYPE( null, null ).validateNumber( $(this) );
    });
    
    $('.int').blur(function(){
        new FormTYPE( null, null ).validateInt( $(this) );
    });
    
});

function FormTYPE( $el, successFn ){
     this.$el = $el;
     this.successFn = successFn;
	 
	 if ( $el ){
	    this.submit = this.$el.find( '.submit' );
        this.reset = this.$el.find('.reset');
        this.cancel = this.$el.find('.cancel');
     }
     
     var self = this;
    
     if ( this.submit && this.submit.length ){
        this.submit.click( function(){
     		if ( self.validate() )
				return self.successFn();
			else
				self.$el.effect( 'shake',{times:4, distance:10}, 75);				
			return false;
     	})
     }

    if ( this.reset && this.reset.length ){	
     	this.reset.click(  function(){
     		self.resetForm();
		    return false;		    
     	})
     }
	
	if ( this.cancel && this.cancel.length ){	
     	this.cancel.click( function(){ return false; })
     }
     
     this.resetForm = function(){
     	
     	self.$el.find('input[type=text]:not(.noreset)').each(function(){
     		$(this).val('');
	    });
	    
	    self.$el.find('input[type=$password]:not(.noreset)').each(function(){
	        $(this).val('');
	    });
	    
	    self.$el.find('input[type=hidden]:not(.noreset)').each(function(){
	    	$(this).val('');
	    });
	    
	    self.$el.find('input[type=checkbox]:not(.noreset)').each(function(){
	        $(this).removeAttr('checked');
	    });
	    self.$el.find('textarea:not(.noreset)').each(function(){
	        $(this).val('');
	    });
	    self.$el.find('select:not(.noreset)').each(function(){
	        $(this).find('option:first').attr('selected', 'selected');
	    });
	    
	    self.$el.find('.errorText').remove();
	    self.$el.find('.errorBorder').removeClass('errorBorder');
	    self.$el.find('.error').removeClass('error');
     }
     
	
     this.validate = function(){ 
         return self.validateRequired() 
         && self.validateEmails() 
         && self.validateConfirmations() 
         && self.validateNumbers() 
         && self.validateInts();
     }	
     
     this.validateRequired = function(){
     	var valid = true;
     	self.$el.find('.required:visible' ).each(function(){
     	    valid = self.validateField( $(this) ) && valid;
		});
		return valid;
     }
     
     this.validateEmails = function(){
     	var valid = true;
		self.$el.find('.email' ).each(function(){
		    valid = valid && self.validateEmail( $(this) );
		}); 
		return valid;
     }
     
     this.validateEmail = function( $el ){
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/;  
        if ( emailPattern.test( $el.val() ) )
            self.removeError( $el );
        else{
            self.addError( $el, 'This is not a proper email. Ex. emailaddress@extension.com' );
            return false;          
        }
        return true;
     }

     this.validateConfirmations = function(){
     	var valid = true;
		self.$el.find('.confirmation' ).each(function(){
			var $el1 = $(this).find('.confirmation1');
			var $el2 = $(this).find('.confirmation2');
			if ( $el1.val() == $el2.val() ){
				removeError( $(this).find('.confirmation1') );
				removeError( $(this).find('.confirmation2') );
			}
			else{
				addError( $(this).find('.confirmation1'), 'These fields do not match.' );
				addError( $(this).find('.confirmation2'), '' );
				valid = false;			
			}
		}); 
		return valid;
     }
     
     this.validateNumbers = function(){
        var valid = true;
        self.$el.find('.number' ).each(function(){
            valid = valid && self.validateNumber( $(this) );
        }); 
        return valid;
     }
     
     this.validateInts = function(){
        var valid = true;
        self.$el.find('.int' ).each(function(){
            valid = valid && self.validateInt( $(this) );
        }); 
        return valid;
     }
     
     this.show = function(){
     	self.$el.show();
     }
     
     this.hide = function(){
     	self.resetForm();
     	self.$el.hide();
     }
         
     this.validateField = function( $el ){
        var val = $.trim( $el.val() );
        if ( val.length && (val != $el.attr('placeholder'))){
            self.removeError( $el );
            return true;
        }    
        else{
            self.addError( $el, 'This field is required.' );
            return false;          
        }
     }
    
    this.validateNumber = function( $el ){
        var val = parseInt( $.trim( $el.val() ) );
        if ( (val != $el.attr('placeholder')) && (typeof val === 'number')){
            self.removeError( $el );
            return true;
        }    
        else{
            self.addError( $el, 'You must enter a non-decimal number.' );
            return false;          
        } 
    } 
    
    this.validateInt = function( $el ){
        var val = parseInt( $.trim( $el.val() ) );
        if ( (val != $el.attr('placeholder')) && (typeof val === 'number') && (val % 1 == 0)){
            self.removeError( $el );
            return true;
        }    
        else{
            self.addError( $el, 'You must enter a non-decimal number.' );
            return false;          
        } 
    }
    
    
    this.removeError = function( $el ){
        $el.removeClass('errorBorder').parents('fieldset:first').removeClass('error').find('.errorText').remove();
    }
    
    this.addError = function( $el, msg ){
        if( !self.hasErrorMsg($el))
            $el.addClass('errorBorder').parents('fieldset:first').addClass('error').append('<div class="errorText">'+msg+'</div>');
    }
    
    this.hasErrorMsg = function ( $el ){
        var $parent = $el.parents('fieldset:first'); 
        if ( $parent.hasClass('error') )
            return true;
        if ( $parent.find('.error').length )
            return true;    
        if ( $parent.find('.errorText').length )
            return true;
        return false;               
    }
    
    this.validatePasswords = function( $pass1, $pass2){
        if ( $pass1.hasClass('required') || $pass1.val().length ){
            if ( self.validatePassword($pass1) ){
                 if ( $.trim( $pass1.val() ) != $.trim( $pass2.val() ) ){
                     self.addError( $pass1, 'Passwords do not match.' );
                     return false;
                 }
                 else
                    self.removeError( $pass1 );
            }
        }
        return true;
    }
    
    this.validatePassword = function($pass){
        var errMsg = '';
        if ( $pass.hasClass('required') || $pass.val().length ){
            var $password = $.trim( $pass.val() );
            if ( $password.length < RULE_MIN_LEN )
                errMsg = 'Password must be at least '+RULE_MIN_LEN+' characters long<br />';
            if ( $password.length > RULE_MAX_LEN )
                errMsg += 'Password must can not be more then '+RULE_MAX_LEN+' characters long<br />';
    
            var numNumbers = 0;
            for (var i = 0; i < $password.length; i++){
                if ( !isNaN( $password[i] ) ){
                    numNumbers++;
                    if ( numNumbers >= RULE_MIN_NUMS)
                        continue;   
                }
            }
            
            if ( numNumbers < RULE_MIN_NUMS)
                errMsg += 'Password must contain at least '+RULE_MIN_NUMS+' number(s)<br />';
            
            if (errMsg != '')
                self.addError( $pass, errMsg );
            else
                self.removeError( $pass );
        }
        return ( errMsg == '' );       
    }

}

