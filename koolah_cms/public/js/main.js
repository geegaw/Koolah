require.config({
	baseUrl: "/public/js/",
	paths: {
		jquery: 'lib/jquery/jquery.2.0.min',
		jquery_ui: 'lib/jquery_ui/jquery-ui-1.11.1/jquery-ui.min',
		underscore: 'lib/underscore/underscore.min',
		backbone: 'lib/backbone/backbone.min',
		select2: 'lib/select2/select2.min',
	},
	shim:{
		'select2': ['jquery'],
	}
});

require([
  // Load our app module and pass it to our definition function
  'app',
], function(App){
  // The "app" dependency is passed in as "App"
  App.initialize();
});