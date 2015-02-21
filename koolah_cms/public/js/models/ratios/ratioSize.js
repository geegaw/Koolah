define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/label',
], function(_, Backbone, koolahToolkit, Label){
  var RatioSize = Backbone.Model.extend({
	    defaults: {
	        label: '',
		},
		toJSON: function() {
		  var json = Backbone.Model.prototype.toJSON.apply(this, arguments);
		  json.id = this.cid;
		  return json;
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
		/***
		 * computeWFromRatio
		 * @param float ratio
		 * @param int h
		 * note: ratio = w / h
		 * w = ratio * h
		 */
		computeWFromRatio: function(ratio, h){
			if (h && ratio)
				return ratio * h;
			return null;
		},
		/***
		 * 
		 * @param float ratio
		 * @param int w
		 * note: ratio = w / h
		 * h = w / ratio
		 */
		computeHFromRatio: function(ratio, w){
			if (w && ratio)
				return w / ratio;
			return null;
		}
	});
  
  return RatioSize;
});