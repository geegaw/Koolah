define([
  'jquery',
  'underscore',
  'backbone',
  'views/common/globalView',
  'models/users/sessionuser',
], function($, _, Backbone, GlobalView, SessionUser){
  var KoolahRouter = Backbone.Router.extend({
    routes: {
      	'signout'				: 'signout',
      	'signin'				: 'signinInit',
      	'home'					: 'homeInit',
      	'ratios'				: 'ratioInit',		
      	'taxonomy'				: 'taxonomyInit',
      	'menus'					: 'menusInit',
      	'pages'					: 'pagesInit',
      	'page/:id'				: 'pageInit',
      	'page'					: 'pageInit',
      	'users'					: 'usersInit',
      	'roles'					: 'rolesInit',
      	'templates'				: 'templatesInit',
      	'widgets'				: 'widgetsInit',
      	'fields'				: 'fieldsInit',
      	'template/:id'			: 'templateInit',
	  	'new/template/:type'	: 'newTemplateInit',
	  	'account'				: 'accountInit',
	  	'uploadcenter'			: 'uploadCenterInit',
	  	
      	'*actions'				: 'defaultAction'
    }
  });
  var self = this;
  			
  var initialize = function(){
  	var koolahRouter = new KoolahRouter;
  	var globalView = new GlobalView(koolahRouter);
  	var sessionUser = new SessionUser();
  	if (location.pathname.substr(1) != 'signin')
  		var sessionUserFetch = sessionUser.fetch();
  
  	koolahRouter.views = [];
  	
  	koolahRouter.loadCollectionPage = function(file, name, args, perm){
  		sessionUserFetch.done(function(){
  			if(perm && sessionUser.can(perm)){
	  			if (koolahRouter.views[name]){
					var view = new koolahRouter.views[name]( koolahRouter, sessionUser, args );
					view.render(); 
				}
				else{
					require([file], function(View){
						koolahRouter.views[name] = View;
						var view = new koolahRouter.views[name]( koolahRouter, sessionUser, args );
						view.render();
					});	
				}
			}
			else
				koolahRouter.accessDenied();
		});
  	};
  	
  	koolahRouter.loadModelPage = function(file, name, args, perm){
  		sessionUserFetch.done(function(){
  			if(perm && sessionUser.can(perm)){
		  		if (koolahRouter.views[name]){
		  			var view = new koolahRouter.views[name]( koolahRouter, sessionUser, args );
		  			koolahRouter.renderModelPage(view);
				}
				else{
					require([file], function(View){
						koolahRouter.views[name] = View;
						var view = new View( koolahRouter, sessionUser, args );
				    	koolahRouter.renderModelPage(view);
					});
				}
			}
			else
				koolahRouter.accessDenied();
		});
  	};
  	
  	koolahRouter.renderModelPage = function(view){
  		view.model.fetch().done(function(){
			if (typeof view.reinitialize == 'function')
				view.reinitialize();
			view.render();
		});
  	};
  	
  	koolahRouter.accessDenied = function(){
  		require(['text!templates/common/accessdenied.html'], function(accessDenied){
			var tmpl = _.template( accessDenied );
			$('body > section').remove();
    		$(tmpl()).insertAfter('header');
		});
  	};
  	
  	koolahRouter.on({
  		'route:defaultAction': function(actions){
  			require(['text!templates/common/pagenotfound.html'], function(pagenotfound){
				var tmpl = _.template( pagenotfound );
				$('body > section').remove();
	    		$(tmpl()).insertAfter('header');
			});
	   	},
	   	'route:signinInit': function(actions){
	   	},
	   	'route:signout': function(actions){
	   		sessionUser = null;
  			location.href = '/signout';
	   	},
	   	'route:homeInit': function(actions){
	   		sessionUserFetch.done(function(){
	  			require(['views/home/home'], function(HomeView){
	  				var homeView = new HomeView(koolahRouter, sessionUser);
				});
			});
	   	},
  		'route:ratioInit': function(){
  			koolahRouter.loadCollectionPage('views/ratios/ratios', 'ratios', {}, 'ratios_m');
	    },
	    'route:taxonomyInit': function(){
	    	koolahRouter.loadCollectionPage('views/taxonomy/taxonomy', 'taxonomy', {}, 'taxonomy_m');
	    },
	    'route:menusInit': function(){
	    	koolahRouter.loadCollectionPage('views/menus/menus', 'menus', {}, 'menus_m');
	    },
	    'route:pagesInit': function(){
	    	koolahRouter.loadCollectionPage('views/pages/pages', 'pages', {}, 'pages_m');
	    },
	    'route:pageInit': function(id){
	    	var args = {id:id}; 
	    	koolahRouter.loadModelPage( 'views/pages/page', 'page', args, 'pages_m' );
	    },
	    'route:usersInit': function(){
	    	koolahRouter.loadCollectionPage('views/users/users', 'users', {}, 'admin');
	    },
	    'route:rolesInit': function(){
	    	koolahRouter.loadCollectionPage('views/roles/roles', 'roles', {}, 'admin');
	    },
	    'route:templatesInit': function(){
	    	var args= {
	    		type: 'page'
	    	};
	    	koolahRouter.loadCollectionPage('views/templates/templates', 'templates', args, 'template_page_m');
	    },
	    'route:widgetsInit': function(){
	    	var args= {
	    		type: 'widget'
	    	};
	    	koolahRouter.loadCollectionPage('views/templates/templates', 'templates', args, 'template_widget_m');
	    },
	    'route:fieldsInit': function(){
	    	var args= {
	    		type: 'field'
	    	};
	    	koolahRouter.loadCollectionPage('views/templates/templates', 'templates', args, 'template_field_m');
	    },
	    'route:templateInit': function(id){
	    	var args = {id:id}; 
	    	koolahRouter.loadModelPage( 'views/templates/template', 'template', args, ['template_page_m','template_widget_m','template_field_m']  );
	    },
	    'route:newTemplateInit': function(type){
	    	var args = {type:type}; 
	    	koolahRouter.loadModelPage( 'views/templates/template', 'template', args, ['template_page_m','template_widget_m','template_field_m'] );
	    },
	    'route:accountInit': function(){
	    	sessionUserFetch.done(function(){
		    	require(['views/account/account'], function(AccountView){
					var view = new AccountView( koolahRouter, sessionUser, {});
					view.render();
				});	
			});
		 }, 
		 'route:uploadCenterInit': function(){
	    	koolahRouter.loadCollectionPage('views/uploadCenter/uploadCenter', 'uploadCenter', {}, 'files_m');
	    },
	    
	});
  
  	//Backbone.history.start();
    Backbone.history.start({pushState: true});
  };
  
  return {
    initialize: initialize
  };
});