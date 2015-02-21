/*
 * Active Form - jQuery plugin for actively validating forms
 *
 * Copyright (c) National Journal
 *
 * 
 * NOTE:
 *  expects html structure 
 * <form>
 *  <fieldset>
 *      <label>
 *      <input>
 *  </fieldset>
 * ...
 * <fieldset>
 *      <input type="submit" value="save" class="save noreset">
 *      <input type="submit" value="reset" class="reset noreset"> (optional)
 *  </fieldset>
 * </form>
 * 
 */
(function($) {
    $.fn.activeForm = function(options){
        
        return this.each(function() {
            var $this = $(this);
            var selector = $this.attr('id') ? '#'+$this.attr('id') : '.'+$this.attr('class').replace(' ', '.');

            var options = options || {
                successFn: null,
                failureEffect: {
                    times: 4,
                    distance: 20,
                    animation: 500
                }
            };
            
            /***
             * required on blur, if empty add error msg, else remove errmsg
             */
            $(document).on('blur', selector+' .required', function(){
                handleRequiredField( $(this) );                    
            });
            
            /***
             * required on focus, remove error on input
             */
            $(document).on('focus', selector+' .required', function(){
                $(this).removeClass('error');    
            });
            
            /***
             * email on blur, if not valid email add error msg, else remove errmsg
             */
            $(document).on('blur', selector+' .email', function(){
                handleEmailField( $(this) );        
            });
            
            /***
             * email on focus, remove error on input
             */
            $(document).on('focus', selector+' .email', function(){
                $(this).removeClass('error');        
            });
            
            /***
             * submit on click, check all fields are valid, 
             * if valid call successfn
             * else inform invalid
             */
            $(document).on('click touchstart', selector+' .save', function(){
            	var valid = true;
                $this.find('.required').each(function(){
                    if ( !handleRequiredField( $(this) ) ){
                        valid = false;
                        return;
                    }
                });
                
                if (valid){
                    $this.find('.email').each(function(){
                        if ( !handleEmailField( $(this) ) ){
                            valid = false;
                            return;
                        }
                    });
                }
                
                if (valid){
                    if (options.successFn)
                        options.successFn();
                }
                else{
                    $this.effect( "shake",{times:options.failureEffect.times, distance:options.failureEffect.distance}, options.failureEffect.animation);
                    return false;
                }    
            });
            
            /***
             * reset on click, reset all fields(including error messages) except for no reset fields 
             */
            $(document).on('click touchstart', selector+' .reset', function(){
                $this.find('input:not(.noreset,[type=checkbox],[type=radio])').each(function(){
                    $(this).val('');
                    removeError( $(this) );
                });
                
                $this.find('input[type=checkbox]:not(.noreset),input[type=radio]:not(.noreset)').each(function(){
                    $(this).removeAttr('checked');
                    removeError( $(this) );
                });
                
                $this.find('select:not(.noreset)').each(function(){
                    $(this).val( $(this).find('option:first').val() );
                    removeError( $(this) );
                }); 
            });
            
            
            /***
             * @function: handleRequiredField
             * @var: $el jQuery object
             * @return: bool 
             * @description: check field is not empty
             * if empty add error msg
             * else remove error msg 
             */
            function handleRequiredField($el){
                var valid = validateRequired($el); 
                if ( valid )
                    removeError($el);
                else
                    addError($el, 'This field is required');                    
                return valid;                
            }
            
            /***
             * @function: handleEmailField
             * @var: $el jQuery object
             * @return: bool 
             * @description: check email is valid
             * if required field, handle required first
             * if empty add error msg
             * else remove error msg 
             */
            function handleEmailField($el){
                var valid = true;
                if ($el.hasClass('required') )
                    valid = handleRequiredField( $el );    
                if (valid)
                    valid = validateEmail($el);
                if ( valid )
                    removeError($el);
                else
                    addError($el, 'This is not a valid email');                    
                return valid;                
            }
            
            /***
             * @function: addError
             * @var: $el jQuery object
             * @var: msg string
             * @return: void 
             * @description: 
             * adds error class to parent fieldset
             * adds error to current input
             * appends error message to fieldset
             */
            function addError($el, msg){
                $el.addClass('error');
                var $fieldset = $el.parents('fieldset:first');
                if ($fieldset.length){
                    $fieldset.addClass('error');                    
                    if (msg && $fieldset.find('.errMsg').length == 0)
                        $fieldset.append( '<div class="errMsg">'+msg+'</div>' );
                }
            }
            
            /***
             * @function: removeError
             * @var: $el jQuery object
             * @return: void 
             * @description: 
             * removes error class from parent fieldset
             * removes error from current input
             * removes appended error message from fieldset
             */            
            function removeError($el){
                $el.removeClass('error');
                var $fieldset = $el.parents('fieldset:first');
                if ($fieldset.length){
                    $fieldset.removeClass('error');
                    if ($fieldset.find('.errMsg').length)
                        $fieldset.find('.errMsg').remove();
                }
            }
            
            
            /***
             * @function: validateRequired
             * @var: $el jQuery object
             * @return: bool 
             * @description: false if empty or same value as placeholder 
             */
            function validateRequired($el){
                var data = $.trim( $el.val() );
                if ($el.is('select') && data == 'no_selection')
                	data = '';
                return ((data.length > 0) && (data != $el.attr('placeholder'))) ;                
            }
            
            /***
             * @function: validateEmail
             * @var: $el jQuery object
             * @return: bool 
             * @description: false if invalid email 
             */
            function validateEmail($el) { 
                var email = $.trim( $el.val() );
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            } 
            
            
        });
        
    };
    
}(jQuery));