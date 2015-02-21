/**
 * @fileOverview UID creates a uniue id
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UID
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\elements\tools
 */
function UID(){
    var uid =  String( (new Date().getTime() )+Math.random()*Math.random() );
    uid  = uid.replace( /\./g, "" );
    return uid;    
}
