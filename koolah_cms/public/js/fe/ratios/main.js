$(document).ready(function(){
    /*******************************
     *                       Constants                   *
     *******************************/
    var FADE_TIME = 450;
    /*******************************/
   
    /*******************************
     *                   Page Elements                *
     *******************************/
    var ratioForm = new FormTYPE($('#ratioForm form'))
    var ratioSizeForm = new FormTYPE($('#ratioSizeForm form'))
    var $msgBlock = $('#msgBlock');
    /*******************************/
   
    /*******************************
     *                 System Elements              *
     *******************************/
    var ratios = new RatiosTYPE( $msgBlock ) ;
    /*******************************/
    
    /*******************************
     *                          Init                          *
     *******************************/
    init();
    /*******************************/
   
    /*******************************
     *                         Actions                     *
     *******************************/
    $('#addRatio').click(function(){
        closeRatioForm();
        showRatioForm();
        $('#ratioForm h2 span').html('New');        
    })
    
    $('#addRatioSize').click(function(){
        showRatioSizeForm();
        $('#ratioSizeForm h2 span').html('New');
    })
    
    $('#cancelSaveRatio').click(function(){
        closeRatioForm();
        return false;
    })
    
    $('#cancelSaveRatioSize').click(function(){
        closeRatioSizeForm();
        return false;
    })
    
    $('#saveRatio').click(function(){
        if ( ratioForm.validate() ){
            var ratio = new RatioTYPE( $msgBlock );
            ratio.readForm();
            ratio.save( getRatios );
            closeRatioForm();
            closeRatioSizeForm();
        }
        return false;
    })
    
    $('#saveRatioSize').click(function(){
        if ( ratioSizeForm.validate() ){
            var ratio = ratios.find( $('#ratioID').val() );
            var ratioSizeID = $('#ratioSizeID').val();
            if ( ratioSizeID )
                var ratioSize = ratio.sizes.find( $('#ratioSizeID').val() );
            else
                var ratioSize = new RatioSizeTYPE( $msgBlock );
            ratioSize.readForm();
            
            if ( !ratioSizeID )
                ratio.sizes.append( ratioSize );
                
            ratio.save(function(){
                $('#ratioSizesList ul').html( ratio.sizes.mkList() );
                closeRatioSizeForm();    
            });
        }
        return false;
    })
    
    $('#filterRatiosGo').click(function(){
        filterRatios();
    })
    
    $('#filterRatiosReset').click(function(){
        $('#filterRatio').val('');
        displayRatios();
    })
    
    
    $('body').on('click', '.ratio .edit', function(){
        $('#ratioForm h2 span').html('Edit');
        showRatioForm();        
    })
    
    $('body').on('click', '.ratioSize .edit', function(){
        $('#ratioSizeForm h2 span').html('Edit');
        showRatioSizeForm();        
    })
    
    $('body').on('click', '#ratioSizeDeleteConfirm', function(){
        var ratio = ratios.find( $('#ratioID').val() );
        ratio.sizes.remove( $(this).data().id );
        ratio.save( function(){ $('#ratioSizesList ul').html( ratio.sizes.mkList() ); } )   
    })
    
    $('#ratioSizeWidth').change( function(){
        if ( new FormTYPE( null, null ).validateInt( $(this) )){
            var ratio = $('#ratioHeight').val() / $('#ratioWidth').val();
            $('#ratioSizeHeight').val( parseInt( $(this).val() * ratio) );
        }
    })
    
    $('#ratioSizeHeight').change( function(){
        if ( new FormTYPE( null, null ).validateInt( $(this) )){
            var ratio = $('#ratioWidth').val() / $('#ratioHeight').val();
            $('#ratioSizeWidth').val( parseInt( $(this).val() * ratio) );
        }
    })
     /*******************************/
    
    /*******************************
     *                       Functions                    *
     *******************************/
    function init(){
       getRatios();       
    } 
    
    function closeAllPopupForms(dontclose){
        if (dontclose != 'ratio')            
            closeRatioForm();    
        if (dontclose != 'ratioSize')            
            closeRatioSizeForm();    
    }
    
   function getRatios(){
       ratios.get( displayRatios, null, null, false );              
   } 
   
   function displayRatios(){
        $('#ratioList ul').html( ratios.mkList() );      
        $('#filterRatio').autocomplete({
            source: ratios.getDropdownList(),
            select: filterRatios
        });
   }   
   
   function filterRatios(){
        var suspect = $('#filterRatio').val();
        var results = ratios.filter(  suspect, 'regex');
        $('#ratioList ul').html( results.mkList() );
   }
   
   function showRatioForm(){
       closeAllPopupForms('ratio');
       $('#ratioForm').fadeIn(FADE_TIME);       
   }
   
   function closeRatioForm(){
       ratioForm.resetForm();
       $('#ratioSizesList ul').html('');
       $('#ratioForm').fadeOut(FADE_TIME)
   }
   
   function showRatioSizeForm(){
       //closeRatioSizeForm();
       $('#ratioSizeForm').fadeIn(FADE_TIME);       
   }
   
   function closeRatioSizeForm(){
       ratioSizeForm.resetForm();
       $('#ratioSizeForm').fadeOut(FADE_TIME);
   }
    /*******************************/ 
    
})