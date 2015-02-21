$(document).ready(function(){
	/*******************************
     *                       Constants                   *
     *******************************/
    var FADE_TIME = 450;
    /*******************************/
   
    /*******************************
     *                   Page Elements                *
     *******************************/
    var $msgBlock = $('#msgBlock');
    /*******************************/
   
    /*******************************
     *                 System Elements              *
     *******************************/
    var terms = new TermsTYPE( $msgBlock ) ;
    /*******************************/
    
    /*******************************
     *                          Init                          *
     *******************************/
    init();
    /*******************************/
   
    /*******************************
     *                         Actions                     *
     *******************************/
    
    $('#addTaxonomy').click(function(){
    	var newTerm = new TermTYPE($msgBlock);
    	$('#taxonomyList ul').append( newTerm.mkList(newTerm) );
    	$('#'+newTerm.jsID+' .edit').trigger('click');
    })
    
    /*******************************/
    
    /*******************************
     *                       Functions                    *
     *******************************/
    
    function init(){
    	terms.get(function(){
    		$('#taxonomyList ul').append( terms.mkList() );
    	}, 
    	{
    		parentID: ''
    	});
    }
    
    /*******************************/
})
