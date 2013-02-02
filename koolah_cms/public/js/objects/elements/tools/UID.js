function UID(){
    var uid =  String( (new Date().getTime() )+Math.random()*Math.random() );
    uid  = uid.replace( /\./g, "" );
    return uid;    
}
