var cmsTools = {}

cmsTools.parseID = function( sID ){
    if ( sID ){
        var parts = sID.split( '_' );
        var parsedID = {}
        parsedID.type = parts[0];
        parts.splice( 0, 1 );
        parsedID.id = parts.join('_');
        return parsedID
    }       
}
