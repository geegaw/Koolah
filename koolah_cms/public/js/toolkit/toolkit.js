/**
 * @fileOverview creates filemanager object
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * fileManager
 * - helper class to interact with the
 * file manager
 * @package koolah\cms\public\js\toolkit
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @class 
 * @namespace fileManager
 */
var koolahToolkit = {};
    
    /**
     * center
     * - centers a dom element 
     * @param jQuery dom object $el - element to be centered
     * @param jQuery dom object $to - where $el should be centered
     * @param string type - optional -absolute|fixed
     * @returns bool - heightToo - optional - center on the y axis as well
     */
    koolahToolkit.center = function( $el, $to, type, heightToo ){
        if ( !type )
            type = 'absolute';
        
        var elWidth = $el.width();
        var elHeight = $el.height();
        
        var margin_left = parseInt( elWidth / 2  ) * -1;
        
        $el.css({
            position: type,
            left: '50%',
            'margin-left': margin_left
        });
        
        if ( heightToo ){
            var toHeight = $to.height();
            var top = parseInt((toHeight - elHeight) / 2) 
        
            $el.css({
                top: top,
           }); 
        } 
        
    }
    
    /**
     * getExtFromFilename
     * - get extention from a file name 
     * @param string filename
     * @returns string
     */
    koolahToolkit.getExtFromFilename = function (filename){
        ext = filename.split('.');
        return ext[ (ext.length -1) ].toLowerCase(); 
    }
