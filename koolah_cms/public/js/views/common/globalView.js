define([
  'jquery',
  'underscore',
  'backbone',
  'plugins/jquery.activeform',
  'toolkit/toolkit',
  'jquery_ui',
], function($, _, Backbone, koolahToolkit){
	var GlobalView = Backbone.View.extend({
		el: $('body'),
		initialize: function(router){
			$('.hide').hide().removeClass('hide');
			$('.activeform').activeForm();
			this.router = router;
		},
		events: {
			'click #globalNav': 'handleNav',
			'click #mainNav .menuItem > .subMenuTrigger': 'handleSubNav',
			'click #mainNav .menuItem > a[href!=#]': 'navigateSite',
			'click .helpTrigger': 'helpOpen',
			'click .helpTriggerClose': 'helpClose',
			'click .toggle'				: 'collapse',
			'click .removable'			: 'removeBox',	 
	    },
	    handleNav: function(e){
	    	e.preventDefault();
	    	
	    	if ( $('#mainNav').is(':visible') )
	             $('#mainNav').animate({ left: '-150px' }, NAV_DURATION, function(){ $(this).hide(); } );
	        else
	            $('#mainNav').show().animate({ left: 0  }, NAV_DURATION );
	        this.handleSubNav(e);
	    },
	    handleSubNav: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $menuItem = $this.parents('.menuItem:first');
	    	var $subNav = $menuItem.find('> .subMenu');
	        
	        if ( $subNav.is(':visible') )
	             closeSubnav( $menuItem.find('.subMenu:visible') );
	        else{
	        	var $sameLevel = $menuItem.siblings();
	        	if ($sameLevel.find('.subMenu:visible').length)
	        		closeSubnav( $sameLevel.find('.subMenu:visible'), function(){ openSubnav($subNav); } );
	    		else
					openSubnav($subNav);
	        }        
	        
	        function openSubnav($el){ $el.show().animate({ left: '150px'  }, NAV_DURATION ); }
	        function closeSubnav($el, callback){ 
	            $el.animate({ left: '0' }, NAV_DURATION, function(){ 
	                $(this).hide(); 
	                if (callback)
	                    callback(); 
	            }); 
	        }	
	    },
	    navigateSite: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	$('body > section').remove();
	    	this.handleNav(e);
	    	this.router.navigate($this.attr('href'), {trigger: true});
	    },
	    helpOpen: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $parent = $this.parents('.help');
	    	$parent.find('.helpArea')
	    		   .show()
	    		   .animate({
	    		   		height: '75px',
	    		   		width: '300px',
	    		   }, 500);
	    },
	    helpClose: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $parent = $this.parents('.help');
	    	$parent.find('.helpArea')
	    		   .animate({
	    		   		height: '13px',
	    		   		width: '10px',
	    		   }, 500, function(){
	    		   		$(this).hide();
	    		   });
	    },
	    collapse: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $parent = $this.parents('.collapsible:first');
	        $parent.find('.collapsibleBody:first').slideToggle(250, function(){
	            $this.toggleClass('open').toggleClass('closed');           
	            if ( $this.hasClass('open') )
	                $this.html('&#8211;');
	            else
	                $this.html('&nbsp;');
	                //$this.html('<span class="square">&nbsp;</span>');
	        });
	    },
	    removeBox: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $parent = $this.parents('.removableBody:first');
	    	$parent.remove();
	    }
	});

	return GlobalView;
});