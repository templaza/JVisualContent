<?php
/*------------------------------------------------------------------------

# JVisualContent Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

//JModelLegacy::addIncludePath(JVISUALCONTENT_COMPONENT_ADMIN.'/models','JVisualcontentModel');
//
JLoader::register('JVisualContentModelExtraFields', JVISUALCONTENT_COMPONENT_ADMIN.'/models/extrafields.php');

class JVisualContentShortCode{

    protected static $sTypes            = array();
    protected static $shortcode_tags    = array();
    protected static $css               = null;
    protected static $js                = array();
    protected static $fields_name       = array();
    protected static $parentid          = null;
    protected static $count             = 0;
    protected static $element_index     = 0;

    function __construct($config = array()){
        self::$css                  = new stdClass();
        self::$css -> css           = array();
        self::$css -> fields_css    = array();
        self::$css -> custom_css    = array();
        self::$css -> shortcode = array();
    }

    public static function get_custom_css(){
        self::$css -> custom_css   = array_unique(self::$css -> custom_css);
        self::$css -> custom_css   = array_filter(self::$css -> custom_css);
        return implode("",self::$css -> custom_css);
    }

    public static function getShortCodeTagsName(){
        return self::$shortcode_tags;
    }

    public static function getFieldsName(){
        return self::$fields_name;
    }

    public static function getCss($onlycode=true){
        self::getFieldsCss();
        if($onlycode){
            $css    = '';
            if(count(self::$css)){
                foreach(self::$css as $_css){
                    $css    .= implode("\n",$_css);
                }
            }
            return trim($css);
        }
        return self::$css;
    }

    public static function getJavascript($onlycode=true){
        $js = self::$js;
        $js = array_unique($js);
        $js = array_filter($js);
        if($onlycode){
            return implode("\n",$js);
        }
        return $js;
    }

    public static function getFieldsCss($onlycode=true){
        $css                = array();
        self::$fields_name  = array_unique(self::$fields_name);
        self::$fields_name  = array_filter(self::$fields_name);
        if(count(self::$fields_name)){
            if($f_model = JModelLegacy::getInstance('ExtraFields', 'JVisualContentModel', array('ignore_request' => true))){
                $f_model->setState('filter.name', self::$fields_name);
                if($items = $f_model -> getItems()){
                    foreach($items as $item){
                        if(isset($item -> css_code) && !empty($item -> css_code)) {
                            $css[$item -> name] = $item->css_code;
                        }
                    }
                }
            }
        }

        if(count($css)) {
            $css                        = array_unique($css);
            $css                        = array_filter($css);
            self::$css -> fields_css    = $css;
            if($onlycode){
                return trim(implode("\n",$css));
            }
            return $css;
        }
        return $css;
    }

    public static function get_shortcode_tags_name($text,$prefixRemove = false){
        if(preg_match_all('/\[(\w+[*|\-]?\w+)[\s*?|\]]/s',$text,$match)){
            $match  = $match[count($match) - 1];
            if($prefixRemove){
                $_match = array_map(function($val){return preg_replace('/^(tz_)/s','',$val);},$match);
                $match  = $_match;
            }
            return $match;
        }
        return array();
    }

    function strip_shortcode_tag( $m ) {
        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        return $m[1] . $m[6];
    }


    public static function do_shortcode($content) {
        $jscontent_shortcode    = new self;
//        self::__init();
        $jscontent_shortcode -> __construct();
        self::$shortcode_tags   = self::get_shortcode_tags_name($content);
        $shortcode_tags = self::$shortcode_tags;

        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }

        if (empty($shortcode_tags) || !is_array($shortcode_tags))
            return $content;

        $pattern = self::get_shortcode_regex();

        $_content   = trim($content);
//        if(preg_match_all('/(\<\w+>)(\[\w+.*?\])(\<\/\w+>)/ms',$_content)){
//            $_content   = preg_replace('/(\<\w+>)(\[\w+.*?\])(\<\/\w+>)/ms','$2',$_content);
//        }
//        if(preg_match_all('/(\<\w+>)(\[\/\w+.*?\])(\<\/\w+>)/ms',$_content)){
//            $_content   = preg_replace('/(\<\w+>)(\[\/\w+.*?\])(\<\/\w+>)/ms','$2',$_content);
//        }
        if(preg_match('/^(\[\w+.*?\])(\<\/\w+>)/ms',$_content)){
            $_content = preg_replace('/^(\[\w+.*?\])(\<\/\w+>)/ms','$1',$_content);
        }
        if(preg_match('/^(\{tz_element.*?\})(\<\/\w+>)/ms',$_content)){
            $_content = preg_replace('/^(\{tz_element.*?\})(\<\/\w+>)/ms','$1',$_content);
        }
        if(preg_match('/(\<\w+>)(\[\/\w+.*?\])$/ms',$_content)){
            $_content = preg_replace('/(\<\w+>)(\[\/\w+.*?\])$/ms','$2',$_content);
        }
        if(preg_match('/(\<\w+>)(\{\/tz_element.*?\})$/ms',$_content)){
            $_content = preg_replace('/(\<\w+>)(\{\/tz_element.*?\})$/ms','$1',$_content);
        }

        while(preg_match("#{$pattern}#",$_content)){
            $_content    = preg_replace_callback( "#{$pattern}#", function($m){
                self::$parentid = uniqid();
                return self::do_shortcode_tag($m);
            }, $_content );
        }
        self::getFieldsCss();

        return $_content;
    }

    protected static function do_shortcode_tag( $m ) {
        $shortcode_tags = self::$shortcode_tags;

        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = self::shortcode_parse_atts( $m[3] );
        $attr   = self::shortcode_prepare_atts($tag,$attr);

        if ( isset( $m[5] ) ) {
            $m5   = trim($m[5]);
//            if(preg_match('/^(\[\w+.*?\])(\<\/\w+>)/ms',$m5)){
//                $m5   = preg_replace('/^(\[\w+.*?\])(\<\/\w+>)/ms','$1',$m5);
//            }
//            if(preg_match('/^(\{tz_element.*?\})(\<\/\w+>)/ms',$m5)){
//                $m5   = preg_replace('/^(\{tz_element.*?\})(\<\/\w+>)/ms','$1',$m5);
//            }
//            if(preg_match('/(\<\w+>)(\[\/\w+.*?\])$/ms',$m5)){
//                $m5   = preg_replace('/(\<\w+>)(\[\/\w+.*?\])$/ms','$2',$m5);
//            }
//            if(preg_match('/(\<\w+>)(\{\/tz_element.*?\})$/ms',$m5)){
//                $m5   = preg_replace('/(\<\w+>)(\{\/tz_element.*?\})$/ms','$1',$m5);
//            }
            // enclosing tag - extra parameter
            return $m[1] .self::shortcode_parse_html($shortcode_tags,$tag,$attr,$m5). $m[6];
        } else {
            // self-closing tag
            return $m[1] . self::shortcode_parse_html($shortcode_tags,$tag,$attr,null) . $m[6];
        }
    }

    protected static function shortcode_parse_html($shortcode_tags,$tag_name,$attr,$shortcode = null){
//        var_dump($shortcode);

        $sTags  = self::get_shortcode_tags($shortcode_tags);
        $unix   = str_replace('.','',microtime(true)).'-0-'.rand(0,9);
        if(isset($sTags[$tag_name]) && isset($sTags[$tag_name] -> html)){
            $tag_html   = $sTags[$tag_name] -> html;
            $child_pattern      = self::get_shortcode_child_regex();
            if(preg_match("/$child_pattern/s",trim($shortcode))){
                self::do_shortcode_child($shortcode,$tag_html);
            }

            // Create parent id
            if(preg_match_all('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',$tag_html)){
                $tag_html   = preg_replace('/(\[\/parentid\])|(\[parentid\]\[\/parentid\])/',self::$parentid,$tag_html);
            }

            if(preg_match_all('/\{(.*?)\}/',$tag_html,$match)){
                $match  = $match[count($match) - 1];
                if(count($match)){
                    self::$fields_name    = array_merge(self::$fields_name,$match);
                    foreach($match as $field_name){
                        if($field_name != 'editor'){
                            if(isset($attr[$field_name])){
                                if(preg_match('/(\{'.preg_quote($field_name,'/').'\}.*?\n+?.*?\{\/'.preg_quote($field_name,'/').'\})/ms',$tag_html)){
                                    if(is_numeric($attr[$field_name])){
                                        if($attr[$field_name] == 1){
                                            $tag_html   = preg_replace('/(\{'.preg_quote($field_name,'/').'\}.*?\n+?.*?\{\/'.preg_quote($field_name,'/').'\})/ms','',$tag_html);
                                        }
                                    }else{
                                        if($attr[$field_name] == 'true'){
                                            $tag_html  = preg_replace('/(\{'.preg_quote($field_name,'/').'\}.*?\n+?.*?\{\/'.preg_quote($field_name,'/').'\})/ms','',$tag_html);
                                        }
                                    }
                                    $tag_html   = preg_replace('/(\{'.preg_quote($field_name,'/').'\})(.*?\n+?.*?)(\{\/'.preg_quote($field_name,'/').'\})/ms','$2',$tag_html);
                                }
                                $f_content  = htmlspecialchars_decode($attr[$field_name]);
                                $f_content  = preg_replace('/&nbsp;/m',' ',$f_content);
                                $tag_html   = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s',$f_content,$tag_html);
                            }
                            else{
                                $tag_html   = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s','',$tag_html);
                            }
                        }else{
                            $tag_html           = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s',$shortcode,$tag_html);
                        }
                    }
                }
            }
            while(preg_match_all('/<img.*?src=[\"|\']([^http]+.*?)[\"|\'].*?\/?\>/s',$tag_html,$imgs)){
                $tag_html   = preg_replace('/(<img.*?src=[\"|\'])([^http]+.*?[\"|\'].*?\/?\>)/s','$1'.JUri::root().'$2',$tag_html);
            }
            if(preg_match('/\[\/type\]/',$tag_html)){
                $tag_html   = preg_replace('/\[\/type\]/i',$shortcode,$tag_html);
            }

            $parentids  = array();
            if(preg_match_all('/(\[parentid[0-9+]?\].*?\[\/parentid[0-9+]?\])/ms',$tag_html,$pmatches3)){
                $pmatches3    = $pmatches3[count($pmatches3) - 1];
            }elseif(preg_match_all('/(\[\/parentid[0-9+]?\])/ms',$tag_html,$pmatches3)){
                $pmatches3    = $pmatches3[count($pmatches3) - 1];
            }else{
                $pmatches3   = array();
            }
            if($pmatches3 && count($pmatches3)){
                $pmatches3    = array_filter($pmatches3);
                $pmatches3    = array_unique($pmatches3);

                foreach($pmatches3 as $pmatch3){
                    $parent_unix            = uniqid();
//                    $parentids[$pmatch3]    = $parent_unix;
                    $tag_html               = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$pmatch3).')/msi',
                        $parent_unix,$tag_html);
                }
            }


//            if(preg_match_all('/(\[typeid[0-9+]?\].*?\[\/typeid[0-9+]?\])/',$tag_html,$typeids)){
//                $typeids    = $typeids[count($typeids) - 1];
//            }elseif(preg_match_all('/(\[\/typeid[0-9+]?\])/',$tag_html,$typeids)){
//                $typeids    = $typeids[count($typeids) - 1];
//            }else{
//                $typeids    = array();
//            }
//
//            if($typeids && count($typeids)){
//                $typeids    = array_filter($typeids);
//                $typeids    = array_unique($typeids);
//
//                foreach($typeids as $i => $typeid){
//                    $time   = explode(' ',microtime());
//                    $time   = str_replace('.','',$time[1].$time[0]);
////                    $time   = substr($time,0,strlen($time) - 2);
//                    $tag_html           = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$typeid).')/msi',$time.'-0-'.$i,$tag_html);
//                }
//            }

//            if(preg_match('/(\[\/typeid\])|(\[typeid\]\[\/typeid\])/',$tag_html,$test)){
////                $unix   = str_replace('.','',microtime(true));
////                $unix   = uniqid();
//                $tag_html   = preg_replace('/(\[\/typeid\])|(\[typeid\]\[\/typeid\])/',$unix,$tag_html);
//            }

            return $tag_html;
        }
    }

    public static function do_shortcode_child(&$shortcode,&$tag_html) {
        if ( false === strpos( $shortcode, '{' ) ) {
            return $tag_html;
        }
        $child_pattern      = self::get_shortcode_child_regex();

        // {tz_element}{/tz_element}
        self::$element_index    = 0;
        while(preg_match("/$child_pattern/s",trim($shortcode))){
            $shortcode = preg_replace_callback( "/$child_pattern/s", function($m) use (&$tag_html){
                self::do_shortcode_tag_child($m,$tag_html);
                return $m[1].$m[6];
            }, trim($shortcode) );
            self::$element_index++;
        }
        $tag_html   = preg_replace( "/".self::get_shortcode_regex(array('loop'))."/s",'',$tag_html);

        return $tag_html;
    }

    protected static function do_shortcode_tag_child($m,&$tag_html=null){
        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '{' && $m[6] == '}' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = self::shortcode_parse_atts( $m[3] );
        $attr   = self::shortcode_prepare_atts($tag,$attr);

        if ( isset( $m[5] ) ) {
            $m5   = trim($m[5]);
            if(preg_match('/^(\[\w+.*?\])(\<\/\w+>)/ms',$m5)){
                $m5   = preg_replace('/^(\[\w+.*?\])(\<\/\w+>)/ms','$1',$m5);
            }
            if(preg_match('/^(\{tz_element.*?\})(\<\/\w+>)/ms',$m5)){
                $m5   = preg_replace('/^(\{tz_element.*?\})(\<\/\w+>)/ms','$1',$m5);
            }
            if(preg_match('/(\<\w+>)(\[\/\w+.*?\])$/ms',$m5)){
                $m5   = preg_replace('/(\<\w+>)(\[\/\w+.*?\])$/ms','$2',$m5);
            }
            if(preg_match('/(\<\w+>)(\{\/tz_element.*?\})$/ms',$m5)){
                $m5   = preg_replace('/(\<\w+>)(\{\/tz_element.*?\})$/ms','$1',$m5);
            }
            // enclosing tag - extra parameter
            return $m[1] .self::shortcode_child_parse_html($tag_html,$attr,$m5). $m[6];
        }else {
            // self-closing tag
            return $m[1] . self::shortcode_child_parse_html($tag_html,$attr,null) . $m[6];
        }
    }

    protected static function shortcode_child_parse_html(&$tag_html,$attr,$shortcode = null){
//        $unix   = str_replace('.','',microtime(true)).'-0-'.rand(0,9);
        $tag_html   = preg_replace_callback( "/".self::get_shortcode_regex(array('loop'))."/ms", function($m2) use (&$tag_html,$attr,$shortcode){

            if ( isset( $m2[5] ) ) {
                $child_tag_html = $m2[5];
                if(preg_match_all('/\{(.*?)\}/',$child_tag_html,$match)){
                    $match  = $match[count($match) - 1];
                    if(count($match)){
                        self::$fields_name    = array_merge(self::$fields_name,$match);
                        foreach($match as $field_name){
                            if(isset($attr[$field_name])){
                                $child_tag_html   = preg_replace('/\{'.$field_name.'\}/s',$attr[$field_name],$child_tag_html);
                            }
                            else{
                                $child_tag_html   = preg_replace('/\{'.$field_name.'\}/s','',$child_tag_html);
                            }
                        }
                    }
                }

                if(preg_match_all('/(\[typeid[0-9+]?\].*?\[\/typeid[0-9+]?\])/',$child_tag_html,$typeids)){
                    $typeids    = $typeids[count($typeids) - 1];
                }elseif(preg_match_all('/(\[\/typeid[0-9+]?\])/',$child_tag_html,$typeids)){
                    $typeids    = $typeids[count($typeids) - 1];
                }else{
                    $typeids    = array();
                }

                if($typeids && count($typeids)){
                    $typeids    = array_filter($typeids);
                    $typeids    = array_unique($typeids);

                    foreach($typeids as $j => $typeid){
                        $time   = time().'-'.$j.'-'.self::$element_index;
                        $child_tag_html           = preg_replace('/('.str_replace(array('[',']','/'),
                                array('\\[','\\]','\\/'),$typeid).')/msi',$time,$child_tag_html);
                    }
                }

//                if(preg_match('/\[\/typeid\]/',$tag_html)){
//                    $child_tag_html   = preg_replace('/\[\/typeid\]/i',$unix,$child_tag_html);
//                }

//                if(preg_match('/\[typeid\]\[\/typeid\]/',$tag_html)){
//                    $child_tag_html   = preg_replace('/\[typeid\]\[\/typeid\]/i',$unix,$child_tag_html);
//                }

                if(preg_match('/\[\/type\]/',$tag_html)){
                    $child_tag_html   = preg_replace('/\[\/type\]/i',$shortcode,$child_tag_html);
                }
                return $m2[1].$child_tag_html.$m2[6].$m2[0];

            }else{
                // self-closing tag
                return $m2[1] . self::shortcode_child_parse_html($tag_html,$attr,$m2[5]) . $m2[6];
            }
        }, $tag_html );

        return $tag_html;
    }

    public static function get_shortcode_tags($tagNames){
        $model = JModelLegacy::getInstance('Elements', 'JVisualContentModel', array('ignore_request' => true));
        $model -> setState('filter.alias',$tagNames);
        $shortcode_tags = array();
        if($types      = $model -> getItems()){
            foreach($types as $type){
                $shortcode_tags[$type -> name]          = new stdClass();
                $shortcode_tags[$type -> name]          = $type;

                self::$css -> shortcode[$type -> name]  = $type -> css_code;
                self::$js[]                             = $type -> js_code;
                if(isset($type -> html)) {
                    self::$sTypes[$type->name] = $type->html;
                }
            }
            if(!isset($shortcode_tags['readmore'])){
                $shortcode_tags['readmore']               = new stdClass();
                $shortcode_tags['readmore'] -> html       = '<hr id="system-readmore" />';
            }
            if(!isset($shortcode_tags['tz_row'])){
                $shortcode_tags['tz_row']               = new stdClass();
                $shortcode_tags['tz_row'] -> html       = '<div class="{width}"><div class="jvc_row {css_class} {el_class}" style="text-align: {text_align}">[/type]</div></div>';
            }
            if(!isset($shortcode_tags['tz_column'])){
                $shortcode_tags['tz_column']            = new stdClass();
                $shortcode_tags['tz_column'] -> html    = '<div class="{css_class} {width} {el_class}">[/type]</div>';
            }
        }
        return $shortcode_tags;
    }

    protected static function shortcode_parse_atts($text) {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    protected static function shortcode_add_custom_css($css=null,$custom_tag=false){
        $custom_css = $css;
        if(!$custom_css){
            $custom_css = self::$custom_css;
        }
        if($custom_css){
            $doc    = JFactory::getDocument();
            if($custom_tag){
                $doc -> addCustomTag('<style type="text/css">'.$custom_css.'</style>');
            }else{
                $doc -> addStyleDeclaration($custom_css);
            }
        }
        self::$custom_css = null;
    }

    protected static function shortcode_prepare_atts($tag,$attr) {
        $_attr  = $attr;

        if($attr){
            foreach($attr as $name => $val){
                if($tag == 'tz_row' || $tag == 'tz_column'){
                    if(($name == 'padding' || $name == 'margin' || $name == 'border_width'
                            || $name == 'border_style' || $name == 'border_color') && $val){
                        $_attr['css_class']  .=  ' '.str_replace('_','-',$name).'='.$val;
                    }
                }else{
                    self::$fields_name[]    = $name;
                    $_attr[$name]           = htmlspecialchars_decode(str_replace('&nbsp;',' ',$val));
                }
            }

            if($tag == 'tz_row' || $tag == 'tz_column'){
                if(!isset($_attr['css_class'])) {
                    $_attr['css_class'] = '';
                }

                if($tag == 'tz_column'){
                    if( isset($attr['width']) && $attr['width']){
                        $value  = $_attr['width'];
                        if(strpos($value,'/')){
                            $width  = 1;
                            $width  = explode('/',$value);
                            if(count($width)){
                                $_width = null;
                                foreach($width as $w){
                                    if($_width == null){
                                        $_width = $w;
                                    }else{
                                        $_width /= $w;
                                    }
                                }
                                if($_width != null){
                                    $width  = $_width;
                                }
                            }
                            $_attr['width']= 'jvc_col-sm-'.($width * 12);
                        }
                    }
                    if(isset($_attr['offset']) && $_attr['offset']){
                        $_attr['css_class']  .= ' '.$_attr['offset'];
                    }
                }

//                foreach($attr as $name => $val){
//                    if(($name == 'padding' || $name == 'margin' || $name == 'border_width'
//                            || $name == 'border_style' || $name == 'border_color') && $val){
//                        $attr['css_class']  .=  ' '.str_replace('_','-',$name).'='.$val;
//                    }
//                }
                if(isset($_attr['css_class']) && preg_match('/\.(.*)\{.*?\}/i',$_attr['css_class'],$match)){
                    $custom_css = self::$css -> custom_css;
                    $custom_css = implode('',$custom_css);
                    if(!preg_match_all('@'.preg_quote($match[count($match) - 1]).'@msi',$custom_css)){
                        self::$css -> custom_css[] =  "\n .jvc_bootstrap3 ".htmlspecialchars_decode(str_replace('&nbsp;',' ',$_attr['css_class']));
                    }
                    $_attr['css_class'] = $match[count($match) - 1];
                }
            }
        }
        return $_attr;
    }

    public static function get_shortcode_regex($tagnames=array()) {
        if(!count($tagnames)){
            $tagnames = self::$shortcode_tags;
        }
        $tagregexp = join( '|', array_map('preg_quote', $tagnames));

        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
        // Also, see shortcode_unautop() and shortcode.js.
        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }

    public static function get_shortcode_child_regex($tagnames=array()) {
        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
        // Also, see shortcode_unautop() and shortcode.js.
        return
            '^\\{'                              // Opening bracket
            . '(\\{?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "(tz_element)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\}\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\})'               // A forward slash not followed by a closing bracket
            .         '[^\\}\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\}'                          // ... and closing bracket
            . '|'
            .     '\\}'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\{]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\{(?!\\/\\2\\})' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\{]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\{\\/\\2\\}'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\}?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }
}