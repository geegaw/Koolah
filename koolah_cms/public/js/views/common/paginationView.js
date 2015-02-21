define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'text!templates/common/pagination.html',
], function($, _, Backbone, koolahToolkit, pagination){
	var PaginationView = Backbone.View.extend({
		initialize: function(){
			this.NUM_DISPLAYS_IN_LIST = 5;
			this.halfWay = parseInt(this.NUM_DISPLAYS_IN_LIST / 2);
		},
		render: function(attrs){
			var tmpl = _.template( pagination );
			
			attrs.start = attrs.active - this.halfWay;
			if (attrs.start < 0)
				attrs.start = 0;
			
			attrs.end = attrs.active + this.halfWay + 1;
			if (attrs.end < this.NUM_DISPLAYS_IN_LIST)
				attrs.end = this.NUM_DISPLAYS_IN_LIST;
			if (attrs.end > attrs.numPages - this.halfWay + 1){
				attrs.end = attrs.numPages;
				attrs.start = Math.max(0, attrs.numPages - this.NUM_DISPLAYS_IN_LIST);
			} 
			
			attrs.first = true;
			if (attrs.active == 0 || attrs.numPages < this.NUM_DISPLAYS_IN_LIST)
				attrs.first = false;
			
			attrs.prev = attrs.active - 1;
			if (attrs.active == 0 || attrs.numPages < this.NUM_DISPLAYS_IN_LIST)
				attrs.prev = false;
			
			attrs.jumpPrev = attrs.active - this.NUM_DISPLAYS_IN_LIST;
			if (attrs.jumpPrev < 1 || attrs.numPages < this.NUM_DISPLAYS_IN_LIST)
				attrs.jumpPrev = false;
						
			attrs.jumpNext = attrs.active + this.NUM_DISPLAYS_IN_LIST;
			if ((attrs.jumpNext >= attrs.numPages) || (attrs.jumpNext > attrs.end - this.halfWay)  || attrs.numPages < this.NUM_DISPLAYS_IN_LIST)
				attrs.jumpNext = false;	
			
			attrs.next = attrs.active + 1;
			if (attrs.next == attrs.numPages || attrs.numPages < this.NUM_DISPLAYS_IN_LIST)
				attrs.next = false;		
			
			attrs.last = attrs.numPages;
			if (attrs.active == attrs.numPages - 1  || attrs.numPages < this.NUM_DISPLAYS_IN_LIST)
				attrs.last = false;
			
				console.log('half: '+this.halfWay);
				for (key in attrs)
					console.log(key+': '+attrs[key]);
			this.$el.html( tmpl(attrs) );
		},
		
	})

	return PaginationView;
});