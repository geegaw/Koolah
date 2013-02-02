$('.toggle').live({
    click: function(){
        var $this = $(this);
        var $parent = $this.parents('.collapsible:first');
        $parent.find('.collapsibleBody:first').slideToggle(250, function(){
            $this.toggleClass('open').toggleClass('closed');           
            if ( $this.hasClass('open') )
                $this.html('&#8211;');
            else
                $this.html('&nbsp;');
                //$this.html('<span class="square">&nbsp;</span>');
        });
        return false;
    }    
})

$('a.many').live({
    click: function(){
        var $parent = $(this).parents('fieldset.many:first');
        $parent.find('> .manyBody:first').clone().appendTo( $parent );
        var last = new FormTYPE( $parent.find('> .manyBody:last'), null );
        last.resetForm();
        return false;
    }
})

$('.removable').live({
    click: function(){
        $(this).parents('.removableBody:first').remove();
        return false;    
    }
})
