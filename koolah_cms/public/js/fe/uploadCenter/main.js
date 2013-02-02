$(document).ready(function(){
    
    /*******************************
     *                       Constants                   *
     *******************************/
    var FADE_TIME = 450;
    /*******************************/
    
    /*******************************
     *                   Page Elements                *
     *******************************/
    var overlay = new Overlay( $('body'), FADE_TIME, 'fixed' );           
    
    var tagForm = new FormTYPE($('#tagForm'))
    var $tagsMsgBlock = $('#tagsMsgBlock');
    
    var fileForm = new FormTYPE($('#fileForm'))
    var fileFilterForm = new FormTYPE($('#fileFilterArea'))
    var $filesMsgBlock = $('#filesMsgBlock');    
    var $fileFormMsgBlock = $('#fileFormMsgBlock');
    var fileToUpload = null;
    var fileDisplayParams = { 'editable': true };
    var tagDisplayParams = { 'editable': true }
        
    
    var cropForm = new FormTYPE($('#cropForm'))
    var $cropMsgBlock = $('#cropMsgBlock');
    var cropFile = null;
    /*******************************/
   
    /*******************************
     *                 System Elements              *
     *******************************/
    var tags = new TagsTYPE( $tagsMsgBlock ) ;
    var files = new FilesTYPE( $filesMsgBlock ) ;
    /*******************************/
    
    /*******************************
     *                          Init                          *
     *******************************/
    init();
    /*******************************/
   
    /*******************************
     *                         Actions                     *
     *******************************/
    
    $('body').on('click', '.yes', function(){
        setTimeout(init, 10);                
    })
    
    /***
     * Tags 
     */
    $('#addTag').click(function(){
        tagForm.$el.find('legend span').html('New');
        showTagForm();
    })
    
    $('#cancelSaveTag').click(function(){
        closeTagForm();
        return false;
    })
    
    $('#saveTag').click(function(){
        if ( tagForm.validate() ){
            var tag = new TagTYPE($tagsMsgBlock);
            tag.readForm();
            tag.save( getTags );
            
            closeTagForm();            
        }
        return false;
    })
    
    $('#searchTagGo').click(function(){
        if ( !$('#searchTag').val().length )
            displayTags();
        else
            filterTags();
    })
    
    $('#searchTagReset').click(function(){
        $('#searchTag').val('');
        displayTags();
    })
    
    $('body').on('click', '.tag .edit', function(){
        tagForm.$el.find('legend span').html('Edit');
        showTagForm();        
    })    
    /***/
   
   
   /***
     * Files 
     */
    $('#addFile').click(function(){
        fileForm.$el.find('legend span').html('New');
        showFileForm();
    })
    
    $('#cancelSaveFile').click(function(){
        closeFileForm();
        return false;
    })
    
    $('#saveFile').click(function(){
        if ( fileForm.validate() ){
            var file = new FileTYPE($filesMsgBlock);
            file.readForm( );
            file.file = fileToUpload;
            if ( !file.parent.id && !file.file )                errorMsg( $fileFormMsgBlock, 'No file Selected' );
            else{
                file.save( getFiles );            
                closeFileForm();
            }            
        }
        return false;
    })
    
    $('#filterFilesGo').click(function(){
        filterFiles();
        return false;
    })
    
    $('#filterFilesReset').click(function(){
        fileFilterForm.resetForm();
        displayFiles();
        return false;
    })
    
    $('#fileInput').change(function(){
        var $this = $(this);
        var $preview = $('#uploadPreview img');
        
        var oFile = document.getElementById('fileInput').files[0];
        var oReader = new FileReader();
        oReader.onload = function(e){
            var ext = new FileTYPE().getExtFromFilename( oFile.name );
            if ( !new FileTYPE().isValidType( ext ) )
                errorMsg( $fileFormMsgBlock, 'Not a vaild file type' );
            else if ( !new FileTYPE().isValidSize( oFile.size ) )
                errorMsg( $fileFormMsgBlock, 'File is too large.' );
            else {
                if( new FileTYPE().isImage( ext ) ) {
                    $preview.attr('src', e.target.result);
                    $('#fileAlt').parent('fieldset').show();
                }
                fileToUpload = oFile;
            }
        };
        oReader.onprogress= function(evt){
            if (evt.lengthComputable){
                $('#fileUploadProgress').show();
                var loaded = parseInt( (evt.loaded / evt.total) * 100 );
                $('#fileUploadProgress').val( loaded );
                
                if ( loaded >= 100 )
                    setTimeout(function(){$('#fileUploadProgress').hide();}, 500);
            }
        }

        oReader.readAsDataURL(oFile);
    })
    
    $('body').on('click', '.file .edit', function(){
        fileForm.$el.find('legend span').html('Edit');
        showFileForm();        
    })
    /***/
   
   
    
    
    /***
     * Crop 
     */
    $('body').on('click', '.file .crop', function(){
        var $this = $(this);
        var $parent = $this.parents('.file');
        var fileID =$parent.find('.fileID').val();
        cropFile = new ImageTYPE( $cropMsgBlock );
        cropFile.file = files.find( fileID );
        showCropForm();        
    })
    
    $('#cropSection .cancel').click(function(){
        closeCropForm();
        return false;
    })
    
    $('#cropSection .save').click(function(){
        cropFile.readForm();
        console.log( cropFile );
        cropFile.save();
        return false;
    })
    
    $('#cropHeight, #cropWidth').change(function(){
        changeAspectRatio();
    })
    
    $('#ratios button').click(function(){
        var $this = $(this);
        $('#ratios button.active').removeClass('active');
        $this.addClass( 'active' );
        
        var data = $this.data();
       
        var image = null;
        var file = cropFile.file;
        var cropObj = cropFile.crop.cropObj; 
        
        if ( data.id == 'freeForm' ){
            $('#cropRatioID').val('')
            $('#freeFormArea').show();
            cropFile.crop.cropObj.setOptions({aspectRatio:null });
            cropFile = new ImageTYPE( $cropMsgBlock );
        }
        else{
            $('#freeFormArea').hide();
            $('#cropRatioID').val( data.id );
            var ratio = data.w / data.h;
            cropFile.crop.cropObj.setOptions({aspectRatio:ratio });
            
            image = file.crops.findRatio( data.id ); 
            if ( image )
                cropFile.crop.cropObj.setSelect( image.crop.coordsToArray()  );
        }
        
        if ( image )
            cropFile = image;
        else
            cropFile = new ImageTYPE();
        cropFile.$msgBlock = $cropMsgBlock;
        cropFile.crop.cropObj = cropObj;
        cropFile.file = file;
    })
    
    /***/
   
    /*******************************/
    
    /*******************************
     *                       Functions                    *
     *******************************/
    function init(){
       getTags();
       getFiles();
    } 
   
    function closeAllPopupForms(dontclose){
        if (dontclose != 'tag')
            closeTagForm();
        if (dontclose != 'file')            
            closeFileForm();
        if (dontclose != 'crop')           
            closeCropForm(); 
        overlay.close();
    }
      
    /***
     * Tags 
     */
   function getTags(){
       tags.get( displayTags, null, null, false );              
   } 
   function displayTags(){
        $('#tagList ul').html( tags.mkList() );      
        $('#searchTag').autocomplete({
            source: tags.getDropdownList(),
            select: filterTags
        });
   }   
   
   function filterTags(){
        var suspect = $('#searchTag').val();
        var results = tags.filter(  suspect, 'regex');
        $('#tagList ul').html( results.mkList() );
   }
   
   function showTagForm(){
       closeAllPopupForms();
       tagForm.resetForm();
       overlay.closeFn = simpleCloseTagForm;
       overlay.open() 
       $('#tagForm').fadeIn(FADE_TIME);       
   }
   
   function closeTagForm(){
       simpleCloseTagForm()
       overlay.close();       
   }
   function simpleCloseTagForm(){
       tagForm.resetForm();
       $('#tagForm').fadeOut(FADE_TIME)
   } 
   /***/
   
   
   /***
     * Files 
     */
   function getFiles(){
       files.get( displayFiles )
   } 
   function displayFiles(){
        $('#filesList ul').html( files.mkList(fileDisplayParams, tagDisplayParams) );  
        
        $('#fileTagAdd').autocomplete({
            source: tags.getDropdownList(),
            select: mkFileTag
        });    
        
        $('#filterFileName').autocomplete({
            source: files.getDropdownList(),
            select: filterFiles
        })
        
        $('#filterFileExt').autocomplete({
            source: files.getExtDropdownList(),
            select: filterFiles
        })
        
        $('#filterFileTag').autocomplete({
            source: tags.getDropdownList(),
            select: filterFiles
        })
   }   
   
   function mkFileTag(){
       var suspect = $('#fileTagAdd').val();
       var results = tags.filter(  suspect, 'regex');
       var html = new FileTYPE().mkTagPod( results.tags()[0] );
       $('#fileTagArea').append(html);
       $('#fileTagAdd').val('');
       return false;
       
   }
      
   function filterFiles(){
        var results = files;
        var filename = $('#filterFileName').val();
        if ( filename )
            results = results.filter(  filename, 'regex');
        
        var fileType = $('#filterFileType').val();
        if ( fileType != 'all' )
            results = results.filterByType(  fileType);    
        
        var fileExt = $('#filterFileExt').val();
        if ( fileExt )
            results = results.filterByExt(  fileExt );    
        
        var fileTag = $('#filterFileTag').val();
        if ( fileTag )
            results = results.filterByTag(  fileTag );    
        
        $('#filesList ul').html( results.mkList(fileDisplayParams, tagDisplayParams) );
   }
   
   function showFileForm(){
       closeAllPopupForms();
       fileForm.resetForm();
       
       overlay.closeFn = simpleCloseFileForm;
       overlay.open() 
       $('#fileForm').fadeIn(FADE_TIME);       
       centerHorizEls()
   }
   
   function resetFileFilterForm(){
       
   }
   
   function closeFileForm(){
       simpleCloseFileForm();
       overlay.close();       
   }
   function simpleCloseFileForm(){
       fileForm.resetForm();
       $('#fileAlt').parent('fieldset').hide();
       $('#uploadPreview img').attr('src', '');
       $('#fileFormMsgBlock').html('');
       $('#fileTagArea').html('');
       $('#fileForm').fadeOut(FADE_TIME);
       fileToUpload = null;
   } 
    /***/
   
   /***
     * Crop 
     */
    
    function showCropForm(){
       closeAllPopupForms('crop');
       
       cropFile.fillForm();
       setTimeout( function(){ changeAspectRatio();}, 200);
       
       overlay.closeFn = simpleCloseCropForm;
       overlay.open();
       $('#cropSection').fadeIn(FADE_TIME); 
   }
   
   function changeAspectRatio(){
        var width = parseInt( $('#cropWidth').val() );
        var height = parseInt( $('#cropHeight').val() );
        if ( width && height){
            var ratio = width / height;
            cropFile.crop.cropObj.setOptions({aspectRatio:ratio });
        }        
    }
   
    function closeCropForm(){
       simpleCloseCropForm()
       overlay.close();       
    }
    function simpleCloseCropForm(){
        cropForm.resetForm();
        $('#cropImg').attr('src', '');
        $('#cropRatioID').val('');
        
        $('#ratios button.active').removeClass('active');
        $('#ratios button:first').addClass( 'active' );
        $('#freeFormArea').show();
        
        if (cropFile && cropFile.crop && cropFile.crop.cropObj)
            cropFile.crop.cropObj.destroy();
        cropFile = null;
        $('#cropSection').fadeOut(FADE_TIME)
   } 
   /***/
  
   /*******************************/ 
    
})
