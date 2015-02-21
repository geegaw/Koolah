define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/node',
  'models/core/label',
  'collections/ratios/ratioSizes'
], function(_, Backbone, koolahToolkit, Node, Label, RatioSizes){
  var Ratio = Node.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahRatio',
		    displayLabel: 'Ratio',
	    },
	    initialize: function(){
	    	if (!this.get('sizes')){
	    		var sizes = new RatioSizes();
	    		this.set({ sizes: sizes });
	    	}	
	    },
	    parse: function(data, options) {
	    	Ratio.__super__.parse.apply(this, arguments);
	    	var sizes = new RatioSizes(data.sizes);
	    	this.set({ sizes: sizes });
	    	delete(data.sizes);
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			if (!$.trim(attrs.label).length)
				errors.push('you must provide a name');
			if (!parseInt(attrs.w))
				errors.push('width can not be 0');
			if (!parseInt(attrs.h))
				errors.push('height can not be 0');
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		computeRatio: function(w, h){
			if (!w)
				w = +this.get('w');
			if (!h)
				h = +this.get('h');
			if (w && h)
				return w / h;
			return null;
		}
		
	});
  
  return Ratio;
});