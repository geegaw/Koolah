define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/node',
  'models/core/label',
  'collections/templates/sections',
  'models/templates/section'
], function(_, Backbone, koolahToolkit, Node, Label, Sections, Section){
  var Template = Node.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahTemplate',
		    displayLabel: 'Template',
	    },
	    initialize: function(){
	    	if (!this.get('sections')){
	    		var sections = new Sections();
	    		this.set({ sections: sections });
	    	}
	    },
	    parse: function(data, options) {
	    	Template.__super__.parse.apply(this, arguments);
	    	if (data.sections){
		    	var sections = new Sections(data.sections);
		    	this.set({sections : sections});
		    	delete(data.sections);
	    	}
	    	return data;
		},
		validate: function(){
			var errors = [];
			if (!this.get('label'))
				errors.push('you must provide a name');
			var sections = this.get('sections');
			if (sections && !this.get('sections').isValid())
				errors.push( sections.validationError );
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		addSection: function(section){
	   		try{
	   			this.get('sections').add(section);
	   		}
	   		catch (e){
	   			console.log(e);
	   		}
	   	},
	   	getSection: function(sectionId){
	   		var section = null;
	   		var sections = this.get('sections');
			if (sections){
				section = sections.get(sectionId); 
			}
			return section;
	   	},
	   	removeSection: function(section){
	   		try{
	   			this.get('sections').remove(section);
	   		}
	   		catch (e){
	   			console.log(e);
	   		}
	   	},
	   	addField: function(sectionId, field){
	   		try{
	   			this.get('sections').get(sectionId).get('fields').add(field);
	   		}
	   		catch (e){
	   			console.log(e);
	   		}
	   	},
	   	removeField: function(sectionId, field){
	   		try{
	   			this.get('sections').get(sectionId).get('fields').remove(field);
	   		}
	   		catch (e){
	   			console.log(e);
	   		}
	   	},
	   	getField: function(sectionId, fieldId){
			var field = null;
			var section = this.getSection( sectionId );
			if (section)
				field = section.getField(fieldId); 
			return field;	   			
	   	},
	   	
	});
  
  return Template;
});