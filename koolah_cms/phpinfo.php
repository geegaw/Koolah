<?php
/**
 * phpinfo
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * phpinfo
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms\conf
 */ 
 
require(conf/conf.php);
if (is_defined('DEBUG') && PHPINFO_VISIBLE
&& is_defined('ENV') && ENV == 'dev') 
    phpinfo(); 
?>