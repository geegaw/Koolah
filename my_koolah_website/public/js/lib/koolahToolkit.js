var koolahToolkit = {};
koolahToolkit.imageUrl = function(id, format){
	var url = FM_URL+'?id='+id;
	if (format){
		if (typeof format == 'string')
			url+= '&format='+format;
		else {
			for (key in format)
				url+= '&format'+koolahToolkit.ucfirst(key)+'=' + format[key];
        }    
	}	
	if (window.devicePixelRatio > 1)
		url+='&retina=true';
	return url; 
}

koolahToolkit.center = function($el){
    var w = $el.width();
    $el.css( 'margin-left', (Math.floor(w /2 ) * -1)+'px' );
}

koolahToolkit.centerInContainter = function($el, $container){
    var elW = $el.width();
    var containerW = $container.width();
    console.log(elW)
    console.log(containerW)
    var margin = (Math.floor( (containerW - elW) /2 ))+'px';
    $el.css({ 
    	'margin-left':  margin,
    	'margin-right':  margin
    });
}

koolahToolkit.ucfirst = function(s){
	return s.charAt(0).toUpperCase() + s.slice(1);	
}
