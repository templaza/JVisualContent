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

jvisualcontentimport('shortcode');
JModelLegacy::addIncludePath(JVISUALCONTENT_COMPONENT_ADMIN.'/models','JVisualcontentModel');
JLoader::register('JVisualContentController', JVISUALCONTENT_COMPONENT_ADMIN.'/controller.php');
JLoader::register('JVisualContentViewShortCodes', JVISUALCONTENT_COMPONENT_ADMIN.'/views/shortcodes/view.html.php');
JHtml::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/helpers/html');

class JVisualContentShortCodeAdmin extends JVisualContentShortCode{
    protected static $mapper            = '';
    protected static $e_name            = '';
    protected static $script_html       = '';
    protected static $count             = 0;
    protected static $element_index     = 0;
//    protected static $css               = null;
//    protected static $js                = array();

    public static function get_mapper(){
        return self::$mapper;
    }

    public static function get_script_html(){
        return self::$script_html;
    }

    public static function do_shortcode_admin($content,$e_name = null){
        $jscontent_shortcode    = new self;
        $jscontent_shortcode -> __construct();
        self::$e_name   = $e_name;
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
//        $_content   = trim($_content);
//        if(preg_match_all('/(\<\w+>)(\[\/\w+.*?\])(\<\/\w+>)/ms',$_content)){
//            $_content   = preg_replace('/(\<\w+>)(\[\/\w+.*?\])(\<\/\w+>)/ms','$2',$_content);
//        }
//        $_content   = trim($_content);


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

        while(preg_match("/$pattern/s",$_content)){
            $_content    = preg_replace_callback( "/$pattern/s", function($m){
                return self::do_shortcode_tag($m);
            }, $_content );
        }
//        die();
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
//            if($tag == 'text_block'){
//                var_dump($m); die();
//            }
//            if(preg_match('/^(\[\w+.*?\])(\<\/\w+>)/ms',$m5)){
//                $m5 = preg_replace('/^(\[\w+.*?\])(\<\/\w+>)/ms','$1',$m5);
//            }
//            if(preg_match('/^(\{tz_element.*?\})(\<\/\w+>)/ms',$m5)){
//                $m5 = preg_replace('/^(\{tz_element.*?\})(\<\/\w+>)/ms','$1',$m5);
//            }
//            if(preg_match('/(\<\w+>)(\[\/\w+.*?\])$/ms',$m5)){
//                $m5 = preg_replace('/(\<\w+>)(\[\/\w+.*?\])$/ms','$2',$m5);
//            }
//            if(preg_match('/(\<\w+>)(\{\/tz_element.*?\})$/ms',$m5)){
//                $m5 = preg_replace('/(\<\w+>)(\{\/tz_element.*?\})$/ms','$1',$m5);
//            }

            // enclosing tag - extra parameter
            return $m[1] . self::shortcode_parse_html($shortcode_tags, $tag, $attr, $m5) . $m[6];
        } else {
            // self-closing tag
            return $m[1] . self::shortcode_parse_html($shortcode_tags,$tag,$attr,null) . $m[6];
        }
    }

    public static function get_custom_css(){
       return parent::get_custom_css();
    }

    public static function getFieldsCss($onlycode=true){
        return parent::getFieldsCss($onlycode);
    }

    public static function getCss($onlycode=true){
        return parent::getCss($onlycode);
    }

    public static function getJavascript($onlycode=true){
        return parent::getJavascript($onlycode);
    }

    public static function get_shortcode_tags($tagNames){
        $model = JModelLegacy::getInstance('Elements', 'JVisualContentModel', array('ignore_request' => true));
        $model -> setState('filter.name',$tagNames);
        $shortcode_tags = array();
        if($types      = $model -> getItems()){
            foreach($types as &$type){
                $type -> data_model_id          = JHtml::_('JVisualContent.guidV4');
                $type -> element_id             = $type -> id;
                $shortcode_tags[$type -> name]  = $type;

                self::$css -> shortcode[$type -> name]  = $type -> css_code;
                self::$js[]                             = $type -> js_code;

                self::$sTypes[$type -> name]    = $type;
            }
            if(!isset($shortcode_tags['readmore'])){
                $row                    = new stdClass();
                $row -> id              = 'readmore';
                $row -> name            = 'readmore';
                $row -> data_model_id   = JHtml::_('JVisualContent.guidV4');
                $row -> html            = '<hr id="system-readmore" />';
                $shortcode_tags['readmore']       = $row;
            }
            if(!isset($shortcode_tags['tz_row'])){
                $row                    = new stdClass();
                $row -> id              = 'row';
                $row -> name            = 'tz_row';
                $row -> data_model_id   = JHtml::_('JVisualContent.guidV4');
                $row -> html            = '<div class="jvc_row {css_class} {el_class}" style="text-align: {text_align}">[/type]</div>';
                $shortcode_tags['tz_row']       = $row;
            }
            if(!isset($shortcode_tags['tz_column'])){
                $column                     = new stdClass();
                $column -> id               = 'column';
                $column -> name             = 'tz_column';
                $column -> data_model_id    = JHtml::_('JVisualContent.guidV4');
                $column -> html             = '<div class="{css_class} {el_class}">[/type]</div>';

                $shortcode_tags['tz_column']    = $column;
            }
        }
        return $shortcode_tags;
    }

    protected static function shortcode_parse_html($shortcode_tags,$tag_name,$attr,$shortcode = null){
        $sTags  = self::get_shortcode_tags($shortcode_tags);
        $unix   = str_replace('.','',microtime(true)).'-0-'.rand(0,9);
        if(isset($sTags[$tag_name])){

            $sTags[$tag_name] -> data_model_id          = JHtml::_('JVisualContent.guidV4');

            $_attr  = $attr;

            if($tag_name == 'tz_column'){
                if(isset($attr['width']) && $attr['width']){
                    $attr['data_width'] = $attr['width'];
                    $value              = $attr['width'];
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

                        $attr['width']= 'jvc_col-sm-'.($width * 12);
                    }
                }
            }

            $controller = JControllerLegacy::getInstance('JVisualContentController',array('name' => 'ShortCodes'));
            $view       =    $controller -> getView('ShortCodes','html','JVisualContentView');
            $view -> addTemplatePath(JVISUALCONTENT_COMPONENT_ADMIN.'/views/shortcodes/tmpl');

            $view = $controller->getView('ShortCodes', 'html','JVisualContentView');

            if($shortcode && trim($shortcode) && strlen(trim($shortcode))){
                $pattern    = self::get_shortcode_regex();
                $_pattern   = self::get_shortcode_child_regex();

                $view -> assign('children',true);
                if(!preg_match("/^$pattern/s",$shortcode) && !preg_match("/^$_pattern/s",$shortcode)){
                    $view -> assign('children',false);
                }
            }else{
                $view -> assign('children',false);
            }

            $view -> assign('jscontent_ename',self::$e_name);
            $sTags[$tag_name] -> attributes = $attr;
            $view -> assign('item',$sTags[$tag_name]);

            $tag_html               = self::loadTemplate(null,$view);
            if(!in_array($tag_name,array('tz_row','tz_column','readmore'))) {
                self::$script_html[$sTags[$tag_name]->id]['html'] = self::loadTemplate('script_html_element', $view);
            }

            $child_pattern      = self::get_shortcode_child_regex();
            if(preg_match("/$child_pattern/s",trim($shortcode))){
                self::do_shortcode_child_admin($shortcode,$tag_html,$sTags[$tag_name]);
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
                                $f_content  = stripslashes($f_content);
                                if(!empty($f_content)) {
                                    $tag_html = preg_replace('/\{' . preg_quote($field_name, '/') . '\}/s', $f_content, $tag_html);
                                }
                            }
                            else{
                                $_attr[$field_name] = '';
                            }
//                            else{
                                $tag_html   = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s','',$tag_html);
//                            }
                        }
                        else{
                            $_attr['editor']    = addslashes(preg_replace("/\n+|\n+\s+/ms",'',htmlspecialchars_decode($shortcode)));
                            $tag_html           = preg_replace('/\{'.preg_quote($field_name,'/').'\}/s',stripslashes(htmlspecialchars_decode($shortcode)),$tag_html);
                        }
                    }
                }
            }
            while(preg_match_all('/<img.*?src=[\"|\']([^http]+.*?)[\"|\'].*?\/?\>/s',$tag_html,$imgs)){
                $tag_html   = preg_replace('/(<img.*?src=[\"|\'])([^http]+.*?[\"|\'].*?\/?\>)/s','$1'.JUri::root().'$2',$tag_html);
            }

//            if(preg_match('/(\<p\>)(.*?\<p\>.*?\<\/p\>.*?)(\<\/p\>)/ms',$tag_html)){
//                while(preg_match('/(\<p\>)(.*?\<p\>.*?\<\/p\>.*?)(\<\/p\>)/ms',$tag_html)){
//                    $tag_html   = preg_replace('/(\<p\>)(.*?\<p\>.*?\<\/p\>.*?)(\<\/p\>)/ms','$2',$tag_html);
//                }
//            }

            if(preg_match('/\[\/type\]/',$tag_html)){
                $tag_html = preg_replace('/\[\/type\]/i', $shortcode, $tag_html);
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
//                foreach($typeids as $typeid){
//                    $time   = explode(' ',microtime());
//                    $time   = str_replace('.','',$time[1].$time[0]);
//                    $time   = substr($time,0,strlen($time) - 2);
//                    $tag_html           = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$typeid).')/msi',$time.'-0-'.rand(0,9),$tag_html);
//                }
//            }

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
                    $parentids[$pmatch3]    = $parent_unix;
                    $tag_html               = preg_replace('/('.str_replace(array('[',']','/'),array('\\[','\\]','\\/'),$pmatch3).')/msi',
                        $parent_unix,$tag_html);
                }
            }

//            if(count($parentids)){
//                $parentids  = json_encode($parentids);
//            }else{
//                $parentids  = '{}';
//            }

//            if(preg_match('/(\[\/typeid\])|(\[typeid\]\[\/typeid\])/',$tag_html,$typeids)){
//
//                $tag_html   = preg_replace('/(\[\/typeid\])|(\[typeid\]\[\/typeid\])/',$unix,$tag_html);
//            }

//            if($tag_name == 'tz_row' && isset($attr['width'])){
//                $tag_html   = '<div class="'.$attr['width'].'">'.$tag_html.'</div>';
//            }
            // Create item for mapper
            if($tag_name == 'readmore'){
                self::$mapper[$sTags[$tag_name]->data_model_id] =
                    array('id' => $sTags[$tag_name]->data_model_id,
                        'element_id' => $sTags[$tag_name]->id,
                        'name' => $sTags[$tag_name]->name,
                        'parent_id' => '',
                        'params' => $_attr,
                        'shortcode' => '<hr id="system-readmore" />');
            }else {
                self::$mapper[$sTags[$tag_name]->data_model_id] =
                    array('id' => $sTags[$tag_name]->data_model_id,
                        'element_id' => $sTags[$tag_name]->id,
                        'name' => $sTags[$tag_name]->name,
                        'parent_id' => '',
                        'params' => $_attr,
                        'parentids' => $parentids,
                        'shortcode' => '[' . $sTags[$tag_name]->name . '][/' . $sTags[$tag_name]->name . ']');
            }

            return $tag_html;
        }
        return null;
    }

    public static function do_shortcode_child_admin(&$shortcode,&$tag_html,$type=null) {
        if ( false === strpos( $shortcode, '{' ) ) {
            return $tag_html;
        }
        $child_pattern      = self::get_shortcode_child_regex();

        // {tz_element}{/tz_element}
        while(preg_match("/$child_pattern/s",trim($shortcode))){
            $i  = 0;
            $shortcode = preg_replace_callback( "/$child_pattern/s", function($m) use (&$tag_html,$type,&$i){
                self::do_shortcode_tag_child($m,$tag_html,$type,$i);
                return $m[1].$m[6];
            }, trim($shortcode) );
            self::$element_index++;
        }

        $tag_html   = preg_replace( "/".self::get_shortcode_regex(array('loop'))."/s",'',$tag_html);

        return $tag_html;
    }

    protected static function do_shortcode_tag_child($m,&$tag_html=null,$type=null,&$i = 0){
        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '{' && $m[6] == '}' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = self::shortcode_parse_atts( $m[3] );
        $attr   = self::shortcode_prepare_atts($tag,$attr);

        if ( isset( $m[5] ) ) {
            $m[5]   = trim($m[5]);
            // enclosing tag - extra parameter
            return $m[1] .self::shortcode_child_parse_html($tag_html,$attr,$m[5],$type,$i). $m[6];
        }else {
            // self-closing tag
            return $m[1] . self::shortcode_child_parse_html($tag_html,$attr,$m[5],$type,$i) . $m[6];
        }
    }
    protected static function shortcode_child_parse_html(&$tag_html,$attr,$shortcode = null,$type=null,$i = 0){
        $unix               = str_replace('.','',microtime(true)).'-0-'.rand(0,9);
        $data_el_model_id   = JHtml::_('JVisualContent.guidV4');
        $count              = 1;
        if(preg_match_all('/.*?(\[loop\].*?\[\/loop\]).*?/msi',$tag_html,$loop)){
            $loop   = $loop[count($loop) - 1];
            $count  = count($loop);
        }
        $tag_html   = preg_replace_callback( "/".self::get_shortcode_regex(array('loop'))."/ms", function($m2) use (&$tag_html,$attr,$shortcode,$unix,$type,$data_el_model_id,&$i,$count){
            if ( isset( $m2[5] ) ) {
                $child_tag_html = $m2[5];
                $script_html    = $child_tag_html;

                if(preg_match_all('/\{(.*?)\}/',$child_tag_html,$match)){
                    $match  = $match[count($match) - 1];
                    if(count($match)){
                        foreach($match as $field_name){
                            if(isset($attr[$field_name]) && !empty($attr[$field_name])){
                                $child_tag_html   = preg_replace('/\{'.$field_name.'\}/s',htmlspecialchars_decode($attr[$field_name]),$child_tag_html);
                            }
                            else{
                                $child_tag_html   = preg_replace('/\{'.$field_name.'\}/s','',$child_tag_html);
                            }
                        }
                    }
                }

                if(preg_match('/\[modelid\]\[\/modelid\]/',$child_tag_html)){
                    $child_tag_html   = preg_replace('/\[modelid\]\[\/modelid\]/i',$data_el_model_id.'-'.$i,$child_tag_html);
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

//                if(preg_match('/\[typeid\]\[\/typeid\]/',$child_tag_html)){
//                    $child_tag_html   = preg_replace('/\[typeid\]\[\/typeid\]/i',$unix,$child_tag_html);
//                }

                if(preg_match('/\[\/type\]/',$child_tag_html)){
                    $controller = JControllerLegacy::getInstance('JVisualContentController',array('name' => 'ShortCodes'));
                    $view       =    $controller -> getView('ShortCodes','html','JVisualContentView');
                    $view -> addTemplatePath(JVISUALCONTENT_COMPONENT_ADMIN.'/views/shortcodes/tmpl');

                    $view = $controller->getView('ShortCodes', 'html','JVisualContentView');
                    if($shortcode){
                        $view -> assign('children',true);
                    }else{
                        $view -> assign('children',false);
                    }
                    $view -> assign('jscontent_ename',self::$e_name);
                    $item                       = new stdClass();
                    $item -> id                 = 'element';
                    $item -> name               = 'tz_element';
                    $item -> element_id         = $type -> id;
                    $item -> data_model_id      = JHtml::_('JVisualContent.guidV4');
                    $item -> aria_control_id    = $data_el_model_id;
                    $item -> html               = $tag_html;
                    $view -> assign('item',$item);
                    $_child_tag_html    = self::loadTemplate(null,$view);
                    $view -> assign('children',false);
                    $item -> aria_control_id    = null;
                    $view -> assign('item',$item);
                    $_script_html       = self::loadTemplate(null,$view);


                    // Create item for mapper
                    self::$mapper[$item -> data_model_id]   =
                        array('id' => $item -> data_model_id,
                            'element_id' => $type -> id,
                            'name' => $item -> name,
                            'parent_id' => '',
                            'params' => $attr,
                            'shortcode' => '{'.$item -> name.'}{/'.$item -> name.'}');


                    if(preg_match('/\[\/type\]/',$_child_tag_html)){
                        if($shortcode) {
                            $_child_tag_html = preg_replace('/\[\/type\]/i', $shortcode, $_child_tag_html);
                        }
                    }
                    $child_tag_html     = preg_replace('/\[\/type\]/i',$_child_tag_html,$child_tag_html);
                    $script_html        = preg_replace('/\[\/type\]/i',$_script_html,$script_html);
//                    $child_tag_html   = preg_replace('/\[\/type\]/i',$shortcode,$child_tag_html);
                }

//                var_dump($script_html);
                if(!isset(self::$script_html[$type -> id]['children'])){
//                    if($i <= $count){
                        self::$script_html[$type->id]['children'][] = '<script id="template-jscontent-new-element-' . $type->id . '-item-' . $i . '" type="text/html">' . $script_html . '</script>';
//                    }
                }else{
                    $keys   = array_keys(self::$script_html[$type -> id]['children']);
                    if(!in_array($i,$keys) && $i <= $count){
                        self::$script_html[$type -> id]['children'][] = '<script id="template-jscontent-new-element-'.$type -> id.'-item-'.$i.'" type="text/html">'.$script_html.'</script>';
                    }
                }
                $i++;

                return $m2[1].$child_tag_html.$m2[6].$m2[0];

            }else{
                // self-closing tag
                return $m2[1] . self::shortcode_child_parse_html($tag_html,$attr,$m2[5]) . $m2[6];
            }
        }, $tag_html );

        return $tag_html;
    }

    protected static function shortcode_prepare_atts($tag,$attr) {
        if($attr){
            $_attr  = array();
            if(is_array($attr) && count($attr)){
                foreach($attr as $name => $val){
                    $_name  = htmlspecialchars_decode(trim($name));
                    $_name  = str_replace('"','',$_name);
                    $_val   = trim($val);
                    if(strlen($_name)){
                        if($tag == 'tz_row' || $tag == 'tz_column'){
                            if($_name == 'css_class') {
                                $css_class  = $attr['css_class'];
                                $css_class  = preg_replace('/(.*?\{)(.*?)(\})/m','$2',$_val);
                                $css_class  = explode('; ',$css_class);
                                if(count($css_class)){
                                    foreach($css_class as $cs){
                                        $_cs    = str_replace(array(';','; '),'',$cs);
                                        $_cs    = explode(': ',$cs);

                                        if($_cs && is_array($_cs)) {
                                            if(count($_cs)) {
                                                if(count($_cs) > 1) {
                                                    list($key, $value) = $_cs;
                                                }else{
                                                    $key    = $_cs[0];
                                                    $value  = '';
                                                }
                                                $key  = htmlspecialchars_decode(trim($key));
                                                $key  = str_replace('"','',$key);
                                                if(strlen($key)) {
                                                    $key = str_replace('-', '_', $key);
                                                    $value = str_replace(array(';', '; '), '', $value);
                                                    if ($key == 'background_image') {
                                                        $value = str_replace(array('url(', ')', JUri::root()), '', $value);
                                                    }

                                                    $_attr[$key] = htmlspecialchars_decode(str_replace(array('&quot;','&nbsp;'), array('',' '), trim($value)));
                                                }
                                            }
                                        }
                                    }
                                }
                            }else{
                                $_attr[$_name]   = htmlspecialchars_decode(str_replace(array('&quot;','&nbsp;'),array('',' '),$_val));
                            }
                        }else{
                            if(preg_match('/^&quot;/s',$_val)){
                                $_val   = preg_replace('/^&quot;/s','',$_val);
                            }
                            if(preg_match('/&quot;$/s',$_val)){
                                $_val   = preg_replace('/&quot;$/s','',$_val);
                            }
                            $_attr[$_name]   = htmlspecialchars_decode(str_replace('&nbsp;','',$_val));
                        }
                    }

                }
            }
            return $_attr;
        }
        return $attr;
    }

//    protected static function shortcode_prepare_atts_admin($attr){
//        if(is_array($attr)){
//            if(count($attr)){
//                foreach($attr as &$_attr){
//                    $_attr  = htmlspecialchars_decode($_attr);
//                }
//            }
//        }else{
//            $attr   = htmlspecialchars_decode($attr);
//        }
//        return $attr;
//    }

    protected static function loadTemplate($tpl,$view,$layout=null){
        $html    = null;
        if($layout){
            $view -> setLayout($layout);
        }
        ob_start();
        $view -> display($tpl);
        $html    = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}