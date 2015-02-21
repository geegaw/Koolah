/**
 * @fileOverview defines importFile
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Pod
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\elements\tools
 * @class - handles a file import
 * @constructor
 */
function ImportFile($msgBlock){    
    this.fileToUpload = null;
    this.$msgBlock = $msgBlock || $('#importFormMsgBlock');
    this.successFn = null;
    
    var self = this;
    
    this.save = function(){
        var oFile = document.getElementById('importFile').files[0];
        if (oFile){
            var oReader = new FileReader();
            
            oReader.onload = function(e){
                var ext = koolahToolkit.getExtFromFilename( oFile.name );
                if ( $.inArray(ext, IMPORT_FILE_TYPES) == -1 )
                    errorMsg( self.$msgBlock, '['+ext+'] is not a vaild file type' );
                else
                    self.fileToUpload = oFile;
            };
            
            oReader.onprogress= function(evt){
                if (evt.lengthComputable){
                    $('#importForm progress').show();
                    var loaded = parseInt( (evt.loaded / evt.total) * 100 );
                    $('#importForm progress').val( loaded );
                    
                    if ( loaded >= 100 )
                        setTimeout(function(){$('#importForm progress').hide();}, 500);
                }
            }
        
            oReader.readAsText(oFile);
            oReader.onload = function(e){
                self.uploadFile( oReader );    
            }
            
        }
        else
            errorMsg( self.$msgBlock, 'no file selected' );  
            
        return false;
    }
    
    this.uploadFile = function(obj){
        $.ajax({
            url: AJAX_IMPORT_URL, 
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                    "data": obj.result
                  }, 
            error: function(e){ errorMsg( self.$msgBlock, 'error' ); console.log(e)},
            success: function(response){
                console.log(response);
                if ( response.status ){
                    successMsg( self.$msgBlock );
                    self.succesFn();
                }
                else
                    errorMsg( self.$msgBlock, response.msg );
            },
        })
    }
}
