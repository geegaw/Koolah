$(document).ready(function(){
    $('.hide').hide().removeClass('hide');
    
    $('nav .filters a').click(function(){
    	var $this = $(this);
    	display.position = 0;
    	
    	if (!$('nav .filters a.inactive').length)
    		$('nav .filters a').addClass('inactive');
    	
    	$this.toggleClass('inactive').toggleClass('active');
    	var $parent = $this.parents('li:first');
    	if ($parent.find('ul').length)
			$parent.find('ul').slideToggle();

    	display.applyFilters();
    	
    	if ($('nav .viewType a.active').data().type == 'gallery')
    		display.$container.masonry();
    	    	
    	return false;
    });
    
    $('nav .viewType a').click(function(){
    	var $this = $(this);
    	var data = $this.data();
    	
    	$('nav .viewType a.active').removeClass('active');
    	$this.addClass('active');
    	
    	if (data.type == 'gallery')
    		display.galleryView.init();
		else
    		display.slideView.init();

    	return false;
    });
    
    $('nav aside').click(function(){
    	if ( $('nav').hasClass('open'))
    		display.closeNav()
    	else
    		display.openNav();
    });
    
    display.init();
})

$(document).on('click', '.goDirection', function(){
	var $this = $(this);
	var direction = $this.data().direction;
	var numPhotos = $('.photo:visible').length;
	
	if (direction == 'left')
		display.position--;
	else 
		display.position++;
	display.position = (numPhotos + display.position) % numPhotos; 
	var offset = display.position * display.device.width * -1;
		
	display.$container.animate({
		left: offset
	}, 750);
	 
})

$(document).on('click', '.photo a', function(){
	var $this = $(this);
	var $parent = $this.parents('.photo:first');
	
	if ($('nav .viewType a.active').data().type == 'gallery'){
		display.slideView.init();
		
		display.position = $('.photo:visible').index( $parent );
		var offset = display.position * display.device.width * -1;

		display.$container.css({
			left: offset
		});	
	}
})

var display = {};
display.container = null;
display.masonryData = {
	columnWidth: 50,
	gutter: '15px',
	itemSelector: '.photo',
	layoutMode : 'masonry' 
};
display.device = {};
display.position = 0;

display.init = function(){
	display.$container = $('#mainBody .slideContainer');
	display.device.height = $(window).height();
	display.device.width = $(window).width();
	
	setTimeout(function(){
		$.ajax({
			url: '/ajax/photos',
			success: function(resp){
				display.createGallery(resp);
				display.openNav()
			},
			error: function(e){
				console.log(e);
			}
		});
	}, 2500);
}

display.openNav = function(){
	$('nav').animate({
		left: 0	
	}, 750).toggleClass('open');
}

display.closeNav = function(){
	$('nav').animate({
		left: 175 * -1 + 20	
	}, 750).toggleClass('open');
}


display.createGallery = function(resp){
	var html = '<div class="goDirection goLeft" data-direction="left">&lt;</div><div class="goDirection goRight" data-direction="right">&gt;</div>';
	html+='<div class="slideContainer">';
	if (resp){
		for (var i=0; i<resp.length; i++){
			var photo = resp[i];
			html+= display.renderPhoto(photo, 'gallery');
		}
	}
	html+='</div>';
	$('#mainBody').html(html).addClass('gallery');
	
	setTimeout(function(){
		display.$container.isotope(display.masonryData);
		display.$container.isotope( 'reLayout');
	}, 500);
}

display.renderPhoto = function(photo, format){
	var html = '';
	html+= '<div ';
	html+= 	'class="photo '+format;
	if (photo.tag){
		for (var i =0; i < photo.tag.length; i++){
			if (photo.tag[i].tag)
				html+= " "+photo.tag[i].tag.toLowerCase();
		}
	}
	html+= 	'">';
	if (format == 'gallery' ){
		format = display.getPhotoFormat(format);
		html+= 	'<a href="#">'; 
	}
	
	html+= 		'<img src="'+koolahToolkit.imageUrl(photo.photo, format)+'" alt="'+photo.name+'" data-photo="'+photo.photo+'"/>'
	if (format == 'gallery' )
		html+= 	'</a>';
	html+= '</div>';
	return html;
}

display.getPhotoFormat = function(format){
	
	if (format == 'gallery' ){
		return {
			maxW: 300,
			maxH: 300
		};
	}
	return {
		maxW: display.device.width - 20,
		maxH: display.device.height
	};
	return {
		p: 'portrait-full',
		l: 'landscape-full'
	};
}

display.applyFilters = function(){
	$('.photo').show();
	if (!$('nav .filters a.active').length){
		$('nav .filters a').removeClass('inactive');
	}
	else{
		var items = [];
		$('.filters a.active').each(function(){
    		var data = $(this).data();
			//$('.photo:not(.'+data.tag+')').hide();
			var item = display.$container.masonry( 'getItem', $('.photo:not(.'+data.tag+')') )
			items.push(item);
    	});
    	display.$container.masonry('reloadItems')
    	console.log(display.$container.masonry('getItemElements'))
    	display.$container.masonry( 'hide', items )
	}
}

display.updateImageFormats = function(format){
	$('.photo img').each(function(){
		var $this = $(this);
		var id = $this.data().photo;
		$this.attr('src', koolahToolkit.imageUrl(id, format));
	})
}

display.slideView = {};
display.slideView.init = function(){
	display.$container.masonry('destroy');
	$('.photo').css({width: display.device.width});
	display.updateImageFormats(display.getPhotoFormat('slide'));
	display.closeNav();
	display.$container.removeClass('gallery').addClass('slides');
}

display.galleryView = {};
display.galleryView.init = function(){
	$('.photo').css({width: 'auto'});
	display.$container.addClass('gallery').removeClass('slides');
	display.updateImageFormats(display.getPhotoFormat('gallery'));
	display.$container.masonry(display.masonryData).masonry();
	display.openNav();
}