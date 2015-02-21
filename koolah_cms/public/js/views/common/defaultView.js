define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/KoolahContainerView',
  'views/common/koolahFormView',
  'views/common/paginationView',
  'text!templates/common/koolahBody.html',
], function($, _, Backbone, koolahToolkit, KoolahContainerView, KoolahFormView, PaginationView, koolahBody){
	var DefaultView = Backbone.View.extend({
		initialize: function(router, sessionUser, args){
			var data = this.defaults;
			var tmpl = _.template( koolahBody );
			document.title = (data.title ? data.title + ' | ': '') + 'koolah';
			$(tmpl(data)).insertAfter('header');

			this.router - router;
			this.sessionUser = sessionUser;
						
			this.container = new KoolahContainerView({sessionUser:sessionUser});
			this.container.sessionUser = sessionUser;
			this.container.defaults = this.defaults;
			this.container.$appendTo = $('#'+this.defaults.classname);
			this.container.render();
			
			this.container.form.defaults = this.defaults;
			this.container.form.$appendTo = $('#'+this.defaults.classname);
			
			var self = this;
			this.collection.fetch().done(function(){
				self.container.collection = self.collection;
				self.container.renderList();
			});
		},
	});

	return DefaultView;
});