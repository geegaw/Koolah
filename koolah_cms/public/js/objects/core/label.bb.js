var Label = Backbone.Model.extend({
    defaults: {
        label: '',
    	ref: ''
    },
    fromAJAX: function( data ){
        if (data){
            this.label = data.label;
            this.ref = data.ref;
        }        
    },
    toAJAX: function(){
        var tmp = {};
            tmp.label = self.label;
            tmp.ref = self.ref;            
        return tmp;
    }
});