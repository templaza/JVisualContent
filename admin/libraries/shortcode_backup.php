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

JModelLegacy::addIncludePath(JVISUALCONTENT_COMPONENT_ADMIN.'/models','JVisualcontentModel');

class JVisualContentShortCode{

    protected static $sTypes            = array();
    protected static $shortcode_tags    = array();
    protected static $custom_css        = null;

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
        self::$shortcode_tags   = self::get_shortcode_tags_name($content);
        $shortcode_tags = self::$shortcode_tags;

        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }

        if (empty($shortcode_tags) || !is_array($shortcode_tags))
            return $content;

        $pattern = self::get_shortcode_regex();

        while(preg_match("/$pattern/s",$content)){
            $content    = preg_replace_callback( "/$pattern/s", function($m){
                return self::do_shortcode_tag($m);
            }, $content );
        }
        return $content;
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
            // enclosing tag - extra parameter
            return $m[1] .self::shortcode_parse_html($shortcode_tags,$tag,$attr,$m[5]). $m[6];
        } else {
            // self-closing tag
            return $m[1] . self::shortcode_parse_html($shortcode_tags,$tag,$attr,$m[5]) . $m[6];
        }
    }

    protected static function shortcode_parse_html($shortcode_tags,$tag_name,$attr,$shortcode = null){

        $test   = 'false';
        $sTags  = self::get_shortcode_tags($shortcode_tags);
        if(isset($sTags[$tag_name])){
            $tag_html   = $sTags[$tag_name];
            $child_pattern      = self::get_shortcode_child_regex();
            if(preg_match("/$child_pattern/s",trim($shortcode))){
                self::do_shortcode_child($shortcode,$tag_html);
            }

            if(preg_match_all('/\{(.*?)\}/',$tag_html,$match)){
                $match  = $match[count($match) - 1];
                if(count($match)){
                    foreach($match as $field_name){
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
                            $tag_html   = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s',$attr[$field_name],$tag_html);
                        }
                        else{
                            $tag_html   = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s','',$tag_html);
                        }
                    }
                }
            }
            if(preg_match('/\[type\]\[\/type\]/',$tag_html)){
                $tag_html   = preg_replace('/\[type\]\[\/type\]/i',$shortcode,$tag_html);
            }

            if(preg_match('/\[typeid\]\[\/typeid\]/',$tag_html)){
                $unix   = str_replace('.','',microtime(true));
                $tag_html   = preg_replace('/\[typeid\]\[\/typeid\]/i',$unix.'-0-'.rand(0,9),$tag_html);
            }

            if($tag_name == 'tz_row' && isset($attr['width'])){
                $tag_html   = '<div class="'.$attr['width'].'">'.$tag_html.'</div>';
            }

            return $tag_html;
        }
    }

    public static function do_shortcode_child(&$shortcode,&$tag_html) {
        if ( false === strpos( $shortcode, '{' ) ) {
            return $tag_html;
        }
        $child_pattern      = self::get_shortcode_child_regex();

        // {tz_element}{/tz_element}
        while(preg_match("/$child_pattern/s",trim($shortcode))){
            $shortcode = preg_replace_callback( "/$child_pattern/s", function($m) use (&$tag_html){
                self::do_shortcode_tag_child($m,$tag_html);
                return $m[1].$m[6];
            }, trim($shortcode) );
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
            // enclosing tag - extra parameter
            return $m[1] .self::shortcode_child_parse_html($tag_html,$attr,$m[5]). $m[6];
        }else {
            // self-closing tag
            return $m[1] . self::shortcode_child_parse_html($tag_html,$attr,$m[5]) . $m[6];
        }
    }

    protected static function shortcode_child_parse_html(&$tag_html,$attr,$shortcode = null){
        $tag_html   = preg_replace_callback( "/".self::get_shortcode_regex(array('loop'))."/s", function($m2) use (&$tag_html,$attr,$shortcode){
            if ( isset( $m2[5] ) ) {
                $child_tag_html = $m2[5];
                if(preg_match_all('/\{(.*?)\}/',$child_tag_html,$match)){
                    $match  = $match[count($match) - 1];
                    if(count($match)){
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

                if(preg_match('/\[typeid\]\[\/typeid\]/',$tag_html)){
                    $unix   = str_replace('.','',microtime(true));
//                    $child_tag_html   = preg_replace('/\[typeid\]\[\/typeid\]/i',$attr['typeid'],$child_tag_html);
                    $child_tag_html   = preg_replace('/\[typeid\]\[\/typeid\]/i',$unix.'-0-'.rand(0,9),$child_tag_html);
                }

                if(preg_match('/\[type\]\[\/type\]/',$tag_html)){
                    $child_tag_html   = preg_replace('/\[type\]\[\/type\]/i',$shortcode,$child_tag_html);
                }
                return $m2[1].$child_tag_html.$m2[6].$m2[0];

            }else{
                // self-closing tag
                return $m2[1] . self::shortcode_child_parse_html($tag_html,$attr,$m2[5]) . $m2[6];
            }
        }, $tag_html );

        return $tag_html;
    }

    protected static function get_shortcode_tags($tagNames){
        $model = JModelLegacy::getInstance('Elements', 'JVisualContentModel', array('ignore_request' => true));
        $model -> setState('filter.alias',$tagNames);
        $shortcode_tags = array();
        if($types      = $model -> getItems()){
            foreach($types as $type){
                $shortcode_tags[$type -> name]  = $type -> html;
                self::$sTypes[$type -> name]     = $type -> html;
            }
            if(!isset($shortcode_tags['tz_row'])){
                $shortcode_tags['tz_row']       = '<div class="row {css_class} {el_class}" style="text-align: {text_align}">[type][/type]</div>';
            }
            if(!isset($shortcode_tags['tz_column'])){
                $shortcode_tags['tz_column']    = '<div class="{css_class} {el_class}" style="text-align: {text_align}">[type][/type]</div>';
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
        if($tag == 'tz_row' || $tag == 'tz_column'){

            if($attr){
                $attr['css_class']  = '';

                if($tag == 'tz_column' ){
                    if( isset($attr['width']) && $attr['width']){
                        $w  = $attr['width'];
                        $w  = ' jvc_col-sm-'.$w * 12;
                        $attr['css_class']  .= $w;
                    }
                    if(isset($attr['offset']) && $attr['offset']){
                        $attr['css_class']  .= ' '.$attr['offset'];
                    }
                }

                foreach($attr as $name => $val){
                    if(($name == 'padding' || $name == 'margin' || $name == 'border_width'
                            || $name == 'border_style' || $name == 'border_color') && $val){
                        $attr['css_class']  .=  ' '.str_replace('_','-',$name).'='.$val;
                    }
                }
                if(isset($attr['css']) && preg_match('/\.(.*)\{.*?\}/i',$attr['css'],$match)){
                    $attr['css_class'] .= $match[count($match) - 1];
                    if(!preg_match_all('@'.preg_quote($attr['css']).'@',self::$custom_css)){
                        self::$custom_css .=  "\n".$attr['css'];
                    }
                }
            }
        }
        return $attr;
    }

    protected static function vc_shortcode_custom_css_class( $param_value, $prefix = '' ) {
        $css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
        return $css_class;
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