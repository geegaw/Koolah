function Pod(jsID){
    this.label = '';
    this.id = '';
    
    if ( !jsID )
        this.jsID = 'pod'+UID();
    else
        this.jsID = jsID;
        
    var self = this;
    
    this.mk = function(extraClass, params){
        var html = '';
        html += '<div id="'+self.jsID+'" class="pod '+extraClass+'"  data-id="'+ self.id+'" data-label="'+ self.label+'">';
        html +=     '<input type="hidden" class="podID" value="'+ self.id+'" />';
        html +=     '<span class="name">';
        if ( params && params.action )
            html +=     '<button type="button" class="'+patams.action+'">'+self.label+'</button>';
        else
            html +=     self.label;
        html +=     '</span>';
        if ( params && params.selectable )
            html += '<button type="button" class="selectMe">select</button>';
        if ( params && params.editable )
            html += '<button type="button" class="del">X</button>';
        html += '</div>';
        return html;
    }
    
    this.read = function(){
        var $pod = $('#'+self.jsID );
        var data = $pod.data();
        if (data){
            self.id = data.id;
            self.label = data.label;
        }
    }
    
    $('body').on( 'click', '#'+self.jsID+' .del', function(){
        var $this = $(this);
        $pod = $this.parents('.pod:first');
        $pod.remove();
    })
}
