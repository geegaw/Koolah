$(document).ready(function(){
    var $msgBlock = $('#msgBlock');
    var activeFolder;
    
    var pages = new PagesTYPE($msgBlock);
    var widgets;
    var root = new FolderTYPE(null, $msgBlock);
    root.getRoot(init, $msgBlock);
    
    var tabs = new TabSection( $('#pagesWidgets .tabSection:first') );    
    
    $('.tab a').click(function(){
        var newTab = $(this).data().tabid;
        $('.breadcrumbSection:visible').hide();
        $('#'+newTab+'Breadcrumbs').show();
    });
    
    
    $('#listViewPlease').click(function(){
        pages.get(updatePageList);            
    })
    
    $('.newPageWidget').click(function(){
        var $parent = $(this).parents('.newPageWidgetBlock');
        $parent.find('.newPageWidgetOptions').show();
        return false;
    })
    
    $('.cancel').click(function(){
        var $parent = $(this).parents('.newPageWidgetBlock');
        $parent.find('.newPageWidgetOptions').hide();
    })
    
    $('.newPageWidgetOption').change(function(){
        var choice = $(this).val();
        if ( choice != 'no_selection' ){
            var type = $(this).attr('class');
            var newPage = new PageTYPE($msgBlock);

            newPage.templateID = choice;
            newPage.save(null, $msgBlock); 
            newPage.init();
            
            $('.tabBody:visible').append( newPage.$icon );
            
            activeFolder.append( newPage );
            activeFolder.save(newPage.showInput, $msgBlock);
            
            mkPagesDynamic()
        } 
    });
    
    $('#mkFolder').click(function(){
        var newFolder = new FolderTYPE(activeFolder.parent.id, $msgBlock);
        newFolder.save( null, $msgBlock );
        
        $('.tabBody:visible').append( newFolder.$folder );
        
        activeFolder.append( newFolder );
        activeFolder.save(newFolder.showInput, $msgBlock);
        mkPagesDynamic()
    })
    
    $('.folderClick').live('click', function(){
        var $parent = $(this).parents('.folder');
        activeFolder = activeFolder.find( $parent.attr('id') )
        $('#breadcrumbs .breadcrumbSection.active').append( '<a class="breadcrumb" href="'+activeFolder.parent.id+'">/'+activeFolder.label.label+'</a>' );
        
        mkPagesDynamic()
        return false;
    })
     
    $('.breadcrumb').live('click', function(){
        clearCurrentFolder();
        
        activeFolder = null;
        activeFolder = new FolderTYPE(null, $msgBlock);
        activeFolder.parent.id = $(this).attr('href');
        activeFolder.get(null, $msgBlock, false);
        
        showNewActiveFolder();
        
        var $nextBreadcrumb = $(this).next('.breadcrumb');
        while( $nextBreadcrumb.length ){
            var $curBreadcrumb = $nextBreadcrumb; 
            $nextBreadcrumb = $(this).next('.breadcrumb');
            $curBreadcrumb.remove();
        }
        
        return false;
    })
    
    $('#list .yes').live('click', function(){
        var id = $(this).attr('id').split('deleteConfirm')[0];
        activeFolder.removeChild(id);
        activeFolder.save(null, $msgBlock);
        return false;
    })
    
    function showNewActiveFolder(){
        clearCurrentFolder();
        activeFolder.showChildren( $('.tabBody:visible') );
        mkPagesDynamic()
    }
    
    function clearCurrentFolder(){
        $('.page').remove();
        $('.folder').remove();
    }
    
    function init(){
        var pageTab = tabs.findTab( 'pages' );
        var widgetTab = tabs.findTab( 'widgets' );
        
        console.log( root );
        
        $('#pagesBreadcrumbs a:first').attr( 'href', root.children[0].parent.id );
        $('#widgetsBreadcrumbs a:first').attr( 'href', root.children[1].parent.id )

        activeFolder = root.children[0];
        activeFolder.get(null, $msgBlock, false);
        showNewActiveFolder();
    }    
    
    function mkPagesDynamic(){
        
        /*
        // Make listing sortable
        // TODO write code to make sorting persist
        // NOTE: this method should only be used for sites with few 
        //       people working on the site. 
        $('#pages').sortable({
            placeholder: 'movingFolder',
            items: '.folder',
            stop: function(e, ui){
                if ( ui.item.parent().attr('id') != 'pages' ){
                    droppedOnFolder(ui.item.attr('id'), ui.item.parents('.folder').attr('id'))
                }
                //else{}//code to persist sortationww
                
            },
            connectWith: $('.folder .folderClick'),//'.folder a',
            appendTo: 'body',
            helper: 'clone'
        }).disableSelection();
        
        $('.folder .folderClick').sortable({
            items: null,//'.beingDragged',
        });
        */
        $('.folder').draggable({
                        helper: 'clone',
                        appendTo: 'body'
                    })
                    .droppable({
                        accept: '.page, .folder',
                        drop: droppedOnFolder,
                        hoverClass: 'hover'
                    })
                    .disableSelection();
        
        $('.page').draggable({
                        helper: 'clone',
                        appendTo: 'body'
                  })
                  .disableSelection();
        
        $('.breadcrumb').droppable({
                            accept: '.page, .folder',
                            hoverClass: 'hover',
                            tolerance: 'pointer',
                            drop: droppedOnBreadCrumb 
                        })
                        .disableSelection();
       
    }
    
    function droppedOnFolder(e, ui){
        var droppeeID = ui.helper.prevObject.attr('id');
        var droppee = activeFolder.find( droppeeID );

        var droppendOnID = $(this).attr('id');
        var droppedOn = activeFolder.find( droppendOnID );

        moveFile( droppee, droppedOn );
    }
    
    function droppedOnBreadCrumb(e, ui){
        var droppedOnID = $(this).attr('href');
        var droppedOn = new FolderTYPE(null, $msgBlock );
        droppedOn.parent.id = droppedOnID;
        
        var droppeeID = ui.helper.prevObject.attr('id');
        var droppee = activeFolder.find( droppeeID );

        droppedOn.get(null, $msgBlock, false);
        moveFile( droppee, droppedOn );
    } 
    
    function moveFile( file, to ){
        file.parentID = to.parent.id;
        activeFolder.removeChild( file.parent.id );
        to.append( file );

        activeFolder.save( file.removeEl, $msgBlock );
        to.save( null, $msgBlock );
        file.save(null, $msgBlock );
    }
    
    function updatePageList(){
        $('#pagesList ul').html( pages.mkList() );
    }
});
