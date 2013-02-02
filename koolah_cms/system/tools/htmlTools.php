<?php

class htmlTools{
    
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
//debug::vardump($tabLabel, true);

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
    
    public static function mkTab( $label, $body, $suplemantalClasses=null, $id=null ){
        if ( !$id )
            $id = 'tab_'.rand();
        
        return array( 
            self::mkTabLabel( $id, $label, $suplemantalClasses),
            self::mkTabBody( $id, $body, $suplemantalClasses)
        );
    }
    public static function mkTabLabel( $id, $label, $suplemantalClasses=null){
        return '<div class="tab '.$suplemantalClasses.'"><a href="#'.$id.'">'.$label.'</a></div>';
    }
    
    public static function mkTabBody( $id, $body, $suplemantalClasses=null){
        return '<div id="'.$id.'" class="tabBody fullWidth hide '.$suplemantalClasses.'">'.$body.'</div>';
    }
    
    static function mkPod( $id, $label, $jsID = null, $extraClass='', $params=null ){
        if ( !$jsID )
            $jsID = 'pod'.uniqid();
        $html = '';
        $html.= '<div id="'.$jsID.'" class="pod '.$extraClass.'"  data-id="'.$id.'">';
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