$(document).ready(function() {
    
    /*******************************
     *         Constants           *
     *******************************/
    var FADE_TIME = 450;
    /*******************************/
    
    /*******************************
     *        Page Element         *
     *******************************/
    var $msgBlock = $('#msgBlock');
    
    var mainTabs = new TabSection($('.tabSection:first'));
    var mainForm = new FormTYPE($('.newPageWidgetForm:first'));
    
    var fileForm = new FormTYPE($('#fileForm'))
    var fileFilterForm = new FormTYPE($('#fileFilterArea'))
    var $filesMsgBlock = $('#filesMsgBlock');    
    var $fileFormMsgBlock = $('#fileFormMsgBlock');
    var fileToUpload = null;
    var $fileSelector = null;
    var fileDisplayParams = { 'selectable': true }
    /*******************************/
   
    /*******************************
     *      System Elements        *
     *******************************/
    var page = new PageTYPE($msgBlock);
    var files = new FilesTYPE( $filesMsgBlock ) ;
    var tags = new TagsTYPE( $filesMsgBlock ) ;
    /*******************************/
    
    /*******************************
     *                       Init                              *
     *******************************/
    page.templateID = $('#templateID').val();
    var pageID = $('#pageID').val();
    if (pageID)
        page.parent.id = pageID;        
    page.readForm( mainForm.$el );
    
    $('textarea:not("#fileDescription")').ckeditor();
    $('.dateField input').datepicker({ 
        //buttonImage: "/images/datepicker.gif";
        //numberOfMonths: 3,
    });
    /*******************************/
   
    /*******************************
     *           Actions           *
     *******************************/
    $('#addNewAlias').click(function() {
        var aliasName = $.trim($('#newAlias').val());
        if (aliasName.length) {
            var alias = new AliasTYPE($msgBlock);
            alias.alias = aliasName;
            alias.mkSafe();    
            if ( alias.isUnique() ){
                $('#newAlias').val('');
                page.seo.aliases.appendToPage( alias )
            }
            else
                errorMsg( $msgBlock, 'This alias is already in use.' );
        }
    })

/*    
    $('body').on('click', '.alias .del', function(){
        var $parent = $(this).parents('.alias');
        var id = $parent.attr('id');    
        //page.seo.aliases.remove( id );
console.log(page)    
        $parent.remove();    
        return false;
    })
*/
    
    $('#save').click(function(){
        if ( mainForm.validate() ){
            page.readForm( mainForm.$el.find('.tabsBody') )
console.log(page.toAJAX())    
            page.save(  updateForm, $msgBlock);
//console.log(page)        
        }
        return false;
    })
    
    //TODO ajax call to publish unpublish
    $('#publish').click(function(){
        page.publicationStatus = 'published';
        $('.workflowOptions').val('published');
        $('#publicationStatus .status').html('Published');
        $('#publish').addClass('active').removeClass('inactive');
        $('#unpublish').removeClass('active').addClass('inactive');
        return false;
    })
    
    $('#unpublish').click(function(){
        page.publicationStatus = 'draft';
        $('.workflowOptions').val('draft');
        $('#publicationStatus .status').html('Draft');
        $('#unpublish').addClass('active').removeClass('inactive');
        $('#publish').removeClass('active').addClass('inactive');
        return false;
    })
    
    $('.selectFile').click(function(){
        $fileSelector = $(this).parents('.fileField:first');
        if ( files.isEmpty() ){
            files.get(null, null, $msgBlock, false);
            tags.get(null, null, $msgBlock, false);
        }
        displayFiles();            
        $('#filesSection').fadeIn( FADE_TIME );
        return false;
    }) 
    
    
    /***
     * Files 
     */
    
    $('#closeFileSection').click(function(){
        closeFileSection();
        return false;
    })
    
    $('#addFile').click(function(){
        fileForm.$el.find('legend span').html('New');
        showFileForm();
        return false;
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
            if ( !file.parent.id && !file.file )
                errorMsg( $fileFormMsgBlock, 'No file Selected' );
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
    
    $('body').on('click', '.file .selectMe', function(){
        var $file = $(this).parents('.file:first');
        var data = $file.data();
        $fileSelector.find('.fileLabel').val( data.label );
        $fileSelector.find('.fileID').val( data.id );
        
        closeFileSection();        
    })
    /***/
    /*******************************/
    
    /*******************************
     *                       Functions                    *
     *******************************/
    function updateForm( data ){
        if ( !$('#pageID').val()){
            page.parent.id = data.id;
            $('#pageID').val( data.id );
            if (!page.seo.title || !page.seo.aliases.count()  )
                page.get(page.seo.fillForm, $msgBlock);
console.log( page );            
        }
    }
    
    /***
     * Files 
     */
   function getFiles(){
       files.get( displayFiles )
   } 
   function displayFiles(){
        $('#filesList ul').html( files.mkList(fileDisplayParams) );  
        
        if ($fileSelector.data().type ){
            var type = '';
            var disabled = true;
            switch( $fileSelector.data().type ){
                case 'image':
                    type = 'Image';                    
                    break;
                case 'doc':
                    type = 'Doc';
                    break;
                case 'vid':
                    type = 'Vid';
                    break;
                case 'audio':
                    type = 'Audio';
                    break;
                default:
                    type = 'all';
                    disabled = false;
                    break;
            }
            $('#filterFileType').val( type );
            filterFiles();
            if (disabled)
                $('#filterFileType').attr('disabled', 'disabled');
            else
                $('#filterFileType').removeAttr('disabled');
        }
        
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
        
        $('#filesList ul').html( results.mkList(fileDisplayParams) );
   }
   
   function showFileForm(){
       fileForm.resetForm();
       
       $('#fileForm').fadeIn(FADE_TIME);       
       centerHorizEls()
   }
   
   function resetFileFilterForm(){
       
   }
   
   function closeFileSection(){
       $('#filesSection').fadeOut( FADE_TIME );
   }
   
   function closeFileForm(){
       simpleCloseFileForm();
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
    /*******************************/
});
