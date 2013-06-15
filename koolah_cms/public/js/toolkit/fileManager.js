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
var fileManager = {};

    /**
     * formatUrl
     * - formats a url to call an image 
     * @param string id - image id
     * @param string format - format name
     * @param string format2 - optional - second format name to use when offering both a landscape and portrait view
     * @returns string - fomratted url
     */
    fileManager.formatUrl = function(id, format, format2){
        var extras = '';
        if (format2 && format){
            extras+= '&formatP='+format;
            extras+= '&formatL='+format2;
        }
        else if (format)
            extras +='&format='+format;
        return FM_URL+'?id='+id+extras;
    }
