$(document).ready(function(){
    var FADE_TIME = 350;
    var $msgBlock = $('#msgBlock');
    var overlay = new Overlay( $('#menus'), 'absolute', FADE_TIME );
    
    var menus = new MenusTYPE($msgBlock);
    menus.get( displayMenus, $msgBlock );
    
    var menuForm = new FormTYPE( $('#newMenuForm') );
    
    $('.cancel').click(function(){
        closeForm();
        return false;        
    })
    
    $('.reset').click(function(){
        resetForm()
        return false;
    })
    
    $('#newMenu').click(function(){
        closeMenuList();
        showForm( false );
    })

    $('#newMenuItem').click(function(){
        showForm( true );
    })
    
    $('#closeMenuList').click(function(){
        closeMenuList();
        return false;
    })
    
    $('.save').click(function(){
        if ( menuForm.validate()  ){
            menu = new MenuTYPE(null, self.$msgBlock);
            if( $(this).attr('id') ){
                var id = $(this).attr('id');
                id = id.replace('save', '');
                menu.parent.id = id;
                menu.get(null, $msgBlock, false);
            }
            else if ( $('#menuID').val() )
                menu.parentID = $('#menuID').val(); 
                
            menu.label.label =  $.trim( $('#menuName').val() );
            menu.url = $.trim( $('#meuURL').val() );
            menu.newTab = $('#menuNewtab').attr('checked'); 
            menu.save(null, $msgBlock);
            
            if ( menu.parentID){
                var parent = new MenuTYPE(null, $msgBlock);
                parent.parent.id = menu.parentID;
                
                if (!$(this).attr('id')){
                    parent.get( null, $msgBlock, false );
                    parent.append( menu );
                    parent.save(null, $msgBlock);
                }
                parent.get( null,  $msgBlock, true);
                $('#menuList .items').html( parent.mkChildren() );
            }     
            else
                menus.get( displayMenus, $msgBlock );            
            closeForm();
        }
        
        return false;
    })
    
    $('.aMenu').live('click', function(){
        closeForm();
    });
    
    function showForm( showFull ){
        resetForm();
        if (showFull)
            $('#fullForm').show();
        else
            $('#fullForm').hide();
        overlay.open();
        $('#newMenuForm').fadeIn(FADE_TIME);
        
    }
    
    function closeForm(){
        resetForm()
        $('#newMenuForm').fadeOut(FADE_TIME);
        overlay.close();
        $('#fullForm').hide();
    }
    
    function resetForm(){
        menuForm.resetForm();
        $('#newMenuForm .save').attr( 'id', '' );
    }
    
    function displayMenus(){
       $('#menusList .items').html(  menus.mkInput() );
        mkSortable();
    }
    
    function closeMenuList(){
        $('#menuList').fadeOut(FADE_TIME);
        $('#menuID').val('');
        closeForm();        
    }
    
    function mkSortable(){
        if ( menus && menus.menus() ){  
            var menu = menus.menus()[0];
            menu.mkSortable();
        }
    }
    
    function resetStyle(){
                    
    }
    
})
