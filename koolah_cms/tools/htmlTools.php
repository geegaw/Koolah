<?php
/**
 * htmlTools
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * htmlTools
 * 
 * HTML tools for displaying html 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\tools
 */
class htmlTools{
    
    /**
     * mkHelp
     * mk html predfined format for help with 
     * passed in help text and display to screen
     * @access public
     * @param string $helpText
     */    
    public static function mkHelp($helpText){
        echo 
            "<div class='help'>
                <a class='helpTrigger' href='#'>?</a>
                <div class='helpArea hide'>
                    <a class='helpTriggerClose' href='#'>?</a>
                    <span class='helpText'>$helpText</span>
                </div>
            </div>";
    }
    
    
    /**
     * mkTabSection
     * mk html predfined format for tabs 
     * @access public
     * @param assocArray $labelsMap
     * <code>
     * $labelsMap = array(
     *      array(
     *          string $label -- tab label,
     *          string $body -- body for the tab
     *          string $suplemantalClasses -- additional classes to add (optional)
     *      ), ... 
     * )
     * </code>
     * @param string $id -- optional
     */    
    public static function mkTabSection($labelsMap, $id=null){
        if ( !$id )
            $id = 'tabSection_'.rand();
        $tabLabels = '';
        $tabBodies = '';
        foreach ( $labelsMap as $labelMap ){
            $suplemantalClasses = null;     
            if ( count($labelMap) == 3 )
                list( $label, $body,  $suplemantalClasses) = $labelMap;
            else
                list( $label, $body ) = $labelMap;
            list( $tabLabel,  $tabBody ) = self::mkTab( $label, $body, $suplemantalClasses );

            $tabLabels.= $tabLabel;
            $tabBodies.= $tabBody;
                
        }
        
        $html = '';
        $html.='<div id="'.$id.'" class="tabSection fullWidth">';
        $html.=    '<div class="tabLabels fullWidth">'.$tabLabels.'</div>';
        $html.=    '<div class="tabsBody fullWidth">'.$tabBodies.'</div>';
        $html.='</div>';
        return $html;
    }
    
    /**
     * mkTab
     * helper for mkTabSection 
     * @access public
     * @param string $label
     * @param string $body
     * @param string $suplemantalClasses -- optional
     * @param string $id -- optional
     * @return array
     */    
    public static function mkTab( $label, $body, $suplemantalClasses=null, $id=null ){
        if ( !$id )
            $id = 'tab_'.rand();
        
        return array( 
            self::mkTabLabel( $id, $label, $suplemantalClasses),
            self::mkTabBody( $id, $body, $suplemantalClasses)
        );
    }
    
    /**
     * mkmkTabLabelTab
     * helper for mkTabSection - makes the clickable tabs
     * @access public
     * @param string $id
     * @param string $label
     * @param string $suplemantalClasses -- optional
     * @return string
     */    
    public static function mkTabLabel( $id, $label, $suplemantalClasses=null){
        return '<div class="tab '.$suplemantalClasses.'"><a href="#'.$id.'">'.$label.'</a></div>';
    }
    
    /**
     * mkTabBody
     * helper for mkTabSection - makes the tab's bodies
     * @access public
     * @param string $id
     * @param string $body
     * @param string $suplemantalClasses -- optional
     * @return string
     */    
    public static function mkTabBody( $id, $body, $suplemantalClasses=null){
        return '<div id="'.$id.'" class="tabBody fullWidth hide '.$suplemantalClasses.'">'.$body.'</div>';
    }
    
    /**
     * mkPod
     * makes little round 'pod' html structure
     * can pass params to make pod selectable, editable, or custom action class
     * assigns it a unique id if non passed 
     * @example file tags in upload center
     * @access public
     * @param string $id
     * @param string $label
     * @param string $jsID -- optional
     * @param string $extraClass -- optional
     * @param assocArray $params -- optional
     * @return string
     */    
    static function mkPod( $id, $label, $jsID = null, $extraClass='', $params=null ){
        if ( !$jsID )
            $jsID = 'pod'.uniqid();
        $html = '';
        $html.= '<div id="'.$jsID.'" class="pod '.$extraClass.'"  data-id="'.$id.'" data-label="'.$label.'">';
        $html.=     '<input type="hidden" class="podID" value="'.$id.'" />';
        $html.=     '<span class="name">';
        if ( isset($params['action']) )
            $html.=     '<button type="button" class="'.$params['action'].'">'.$label.'</button>';
        else
            $html.=     $label;
        $html.=     '</span>';
        if ( isset($params['selectable']) )
            $html.= '<button type="button" class="selectMe">select</button>';
        if ( isset($params['editable']) )
            $html.= '<button type="button" class="del">X</button>';
        $html.= '</div>';
        return $html;
    }
}

?>