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


class JVisualContentTree{

    const NIL                           = -1;

    public static $tree                 = array();
    public static $tzparent             = array();
//    public static $shortcode            = array();
    protected static $shortcode         = '';
    public static $tree_html            = array();
    public static $tree_element_type    = array();
    public static $newElement           = null;
    public static $_newElement          = null;
    public static $tzparent_child_count = array();
    protected static $n_unix            = '';


    public function __construct($tree=array(),$tree_html = array()){
        self::$tree         = $tree;
        self::$tree_html    = $tree_html;
    }

    public static function init_tree($tree = array(),$tree_html = array()){
        if(!count(self::$tree)){
            self::$tree         = $tree;
        }
        if(!count(self::$tree_html)){
            self::$tree_html    = $tree_html;
        }
    }

//    static function general_tree($tree = array(),$tree_html = null){
//        self::init_tree($tree,$tree_html);
////        self::prepare_tree_html();
////        self::prepare_key_tree();
//        self::change_keys_tree();
//        self::postOrder(self::root(self::$tree),self::$tree,self::$tzparent,self::$newElement);
//    }

    static function generate_shortcode($tree = array(),$tree_html = null){
        self::init_tree($tree,$tree_html);
        self::change_keys_tree();
//        self::prepare_tree_html();
//        self::prepare_key_tree();
        self::postOrder(self::root(self::$tree),self::$tree,self::$tzparent,self::$newElement);

        return self::$shortcode;
    }

    static function change_keys_tree(){
        $_tree  = array();
        $_keys  = array();
        if(self::$tree && count(self::$tree)){
            $_keys  = array_keys(self::$tree);
            $i = 1;
            foreach(self::$tree as $item){
                $_item  = $item;
                $key    = array_search($item['parent_id'],$_keys);
                if(is_numeric($key)){
                    $_item['parent_id'] = $key + 1;
                    self::$tzparent[$i] = $key + 1;
                }else{
                    $_item['parent_id'] = self::NIL + 1;
                    self::$tzparent[$i] = self::NIL + 1;
                }
                $_tree[$i]  = $_item;
                $i++;
            }
            if(count($_tree)){
                array_unshift($_tree,array('parent_id' => -1,'shortcode' => ''));
                array_unshift(self::$tzparent,self::NIL);
                self::$tree = $_tree;
            }
        }
    }

    static  function prepare_attrib($tag_name,$attr){
        $attrib = array();
        if($tag_name == 'tz_row' || $tag_name == 'tz_column'){
            $attrib['css_class']    = '';
            $attrib['offset']    = '';
            $css_style  = array('background_color','background_image','background_style','margin_top'
            ,'margin_right','margin_left',' padding_top','padding_right','padding_bottom','padding_left'
            ,'border_color','border_style','border_top_width','border_right_width','border_bottom_width'
            ,'border_left_width','font_color');

            $offset = array('width','col_lg_offset','col_md_offset','col_sm_offset','col_xs_offset'
            ,'col_lg_size','col_md_size','width','col_xs_size','hidden_lg','hidden_md','hidden_sm','hidden_xs');

            if($attr){
                foreach($attr as $key => $val){
                    if($key != 'name' || $key != 'children' || $key != 'parent_id'){
                        if($val){
                            if(in_array($key,$css_style) || in_array($key,$offset)){
                                if(in_array($key,$css_style)){
                                    if($key == 'font_color'){
                                        $attrib['css_class']    .= ' color:"'.$val.'"';
                                    }else{
                                        $attrib['css_class']    .= ' '.str_replace('_','-',$key).': '.$val.';';
                                    }
                                }
                                if(in_array($key,$offset)){
                                    if($key == 'width'){
                                        if(preg_match('/\d{1,2}\/?/',$val)){
                                            $attrib['offset']      .= ' jvc_col-sm-'.($val * 12);
                                        }else{
                                            $attrib['offset']    .= ' '.$val;
                                        }
                                    }else{
                                        $attrib['offset']    .= ' '.$val;
                                    }
                                }
                            }else{
                                $attrib[$key]   = JHtml::_('JVisualContent.jsstripslashes',$val);
                            }
                        }
                    }
                }
                if($attrib['css_class']){
                    $date                   = JFactory::getDate();
                    $unix                   = $date -> toUnix();
                    while($unix == self::$n_unix){
                        $unix   = $date -> toUnix();
                    }
                    self::$n_unix   = $unix;

                    $attrib['css_class']    = '.tz_custom_css_'.$unix
                        .'{'.trim($attrib['css_class']).'}';
                }else{
                    unset($attrib['css_class']);
                }
                if($attrib['offset']){
                    $attrib['offset']   = trim($attrib['offset']);
                }else{
                    unset($attrib['offset']);
                }
            }
        }else{
            $attrib = $attr;
        }

        if(isset($attrib['name'])){
            unset($attrib['name']);
        }
//        if(isset($attrib['children'])){
//            unset($attrib['children']);
//        }
        if(isset($attrib['parent_id'])){
            unset($attrib['parent_id']);
        }

        $str    = '';
        if(count($attrib)){
            foreach($attrib as $key => $val){
                if(is_array($val)){
                    $str    .= ' '.$key.'="'.join(' ',$val).'"';
                }else{
                    $str    .= ' '.$key.'="'.$val.'"';
                }
            }
        }
        return $str;
    }

    public static function prepare_key_tree($tree = array()){
        if(!count($tree)){
            $tree   = self::$tree;
        }
        if(count($tree)){
            $new_tree       = array();
            $new_key_tree   = array();
            if(count($tree)){
                $i = 0;
                foreach($tree as $key => $val){
                    if(isset($val['name'])){
                        $attr   = self::prepare_attrib($val['name'],$val['params']);

                        if($val['name'] == 'tz_element'){
                            self::$shortcode[$i]        = '{tz_element'.$attr.'}{/tz_element}';
                        }else{
                            self::$tree_element_type[]  = $val['name'];
                            if(isset(self::$tree_html[$val['name']])
                                && !preg_match('/\[loop\].*\[\/loop\]/s',self::$tree_html[$val['name']])){
                                self::$shortcode[$i]        = '['.$val['name'].$attr.']';
                            }else{
                                self::$shortcode[$i]        = '['.$val['name'].$attr.'][/'.$val['name'].']';
                            }
                        }
                    }
                    $new_key_tree[$i]   = $key;
                    $new_tree[$i]       = $val;

                    if(isset($val['parent_id'])){
                        $key2    = array_search($val['parent_id'],$new_key_tree);
                        if($val['parent_id'] && gettype($key2) != 'boolean'){
                            $new_tree[$i]['parent_id'] = $key2 + 1;
                            self::$tzparent[$i]     = $key2 + 1;
                        }else{
                            $new_tree[$i]['parent_id'] = self::NIL + 1;
                            self::$tzparent[$i]     = self::NIL + 1;
                        }
                    }
//                    unset($new_tree[$i]['children']);
                    $i ++;
                }
            }

            // Insert root tree
            $parent = array('parent' => -1);
            array_unshift($new_tree,$parent);
            array_unshift(self::$tzparent ,-1);

            if(count($new_tree)){
                self::$tree = $new_tree;
                return $new_tree;
            }
        }
        return $tree;
    }

    static function get_element_types($tree = array()){
        if(!count($tree)){
            $tree   = self::$tree;
        }
        if(count($tree)){
            foreach($tree as $key => $val){
                if($val && isset($val['element_type']) && $val['element_type'] && $val['element_type'] != 'tz_element'){
                    self::$tree_element_type[]  = $val['element_type'];
                }
            }
        }
        return self::$tree_element_type;
    }

    static function prepare_tree_html(){
        if(count(self::$tree_html)){
            $new_tree_html  = array();
            foreach(self::$tree_html as $item){
                $new_tree_html[$item -> name]   = $item -> html;
            }
            self::$tree_html    = $new_tree_html;
        }
    }

    // Read Tree
    function get_parent($Tree, $tzparent)
    {
        if (count($Tree)) {
            foreach ($Tree as $key => $val) {
                if ($key == 0) {
                    self::$tzparent[$key] = self::NIL;
                } else {
                    self::$tzparent[$key] = $val;
                }
            }
        }
    }

    // Find root node on tree
    public static function root($Tree,$tzparent=null)
    {
        if (!self::emptyTree($Tree,$tzparent)){
            return 0;
        }

        return self::NIL;
    }

    // Find parent's node on tree
    static function parent($Node, $Tree, $tzparent)
    {
        if (self::emptyTree($Tree, $tzparent) || ($Node > count($Tree) - 1)) {
            return self::NIL;
        }
        return self::$tzparent[$Node];
    }
     // Check empty tree
    static function emptyTree($Tree,$tzparent=null)
    {
        return (count($Tree) == 0);
    }
    // Find left children of node
    public static function leftMostChild($Node, $Tree, $tzparent)
    {
        $i = null;
        $found = null;
        if ($Node < 0)
            return self::NIL;
        $i = $Node + 1;
        $found = 0;
        while (($i <= count($Tree) - 1) && !$found){
            if ($tzparent[$i] == $Node){
                $found = 1;
            }else {
                $i = $i + 1;
            }
        }
        if ($found){
            return $i;
        }
        return self::NIL;
    }

    // Find right children of node
    public static function rightSibling($Node,$Tree,$tzparent)
    {
        $i=null;
        $parent=null;
        $found=null;

        if ($Node <0)
            return self::NIL;
        $parent=$tzparent[$Node];
        $i=$Node+1;
        $found=0;
        while (($i<=count($Tree)-1) && !$found){
            if ($tzparent[$i]==$parent){
                $found=1;
            }else{
                $i=$i+1;
            }
        }

        if ($found){
            return $i;
        }
        return self::NIL;
    }
    // Browse Tree
    static function preOrder($Node,$Tree,$tzparent,&$newElement=null)
    {

        echo $Node;
        $i  = self::LeftMostChild($Node,$Tree,$tzparent);

        while ($i!=self::NIL) {
//            self::create_shortcode_tree($i,$Tree,$tzparent,$newElement);
            self::preOrder($i,$Tree,$tzparent,$newElement);
            $i  = self::rightSibling($i,$Tree,$tzparent);
        }
    }

    //Duyet hau tu
    static function postOrder($Node,$Tree,$tzparent,&$newElement=null)
    {
        $i = self::leftMostChild($Node,$Tree,$tzparent);
        while ($i != self::NIL){
            self::postOrder($i,$Tree,$tzparent,$newElement);
            $i=self::rightSibling($i,$Tree,$tzparent);
        }

        // Do some thing
        if(is_numeric($tzparent[$Node]) && $tzparent[$Node] != -1){
            if(!isset(self::$_newElement[$tzparent[$Node]])) {
                self::$_newElement[$tzparent[$Node]] = $Tree[$tzparent[$Node]]['shortcode'];
            }
            if(isset(self::$_newElement[$tzparent[$Node]]) && self::$_newElement[$tzparent[$Node]]){
                if(preg_match('/\[\/\w+\]$|\{\/\w+\}$/m',self::$_newElement[$tzparent[$Node]],$match)){
                    if(isset(self::$_newElement[$Node])) {
                        self::$_newElement[$tzparent[$Node]] = preg_replace('/\[\/\w+\]$|\{\/\w+\}$/m', self::$_newElement[$Node] . '$0', self::$_newElement[$tzparent[$Node]]);
                    }
                    elseif($Tree[$Node]){
                        self::$_newElement[$tzparent[$Node]] = preg_replace('/\[\/\w+\]$|\{\/\w+\}$/m', $Tree[$Node]['shortcode'] . '$0', self::$_newElement[$tzparent[$Node]]);
                    }
                }
            }
            if($tzparent[$Node] == 0){
                if(isset(self::$_newElement[$Node])) {
                    self::$shortcode .= self::$_newElement[$Node];
                }
            }

        }
//        var_dump($tzparent[$Node]);
//        var_dump($Node);
//        var_dump('parent='.$tzparent[$Node]);
//        var_dump(self::$_newElement);


        // Create shortcode
//        if($Node != 0 && self::$tree[$Node]['parent'] != 0){
//            if(isset(self::$shortcode[self::$tree[$Node]['parent'] - 1])){
//                $parent_shortcode   = self::$shortcode[self::$tree[$Node]['parent'] - 1];
//                if(preg_match('/(\[\/\w+\])$/',$parent_shortcode)){
//                    self::$shortcode[self::$tree[$Node]['parent'] - 1]  = preg_replace('/(\[\/\w+\])$/',self::$shortcode[$Node - 1].'$1',$parent_shortcode);
//                    unset(self::$shortcode[$Node - 1]);
//                }elseif(preg_match('/(\{\/tz_element\})$/',$parent_shortcode)){
//                    self::$shortcode[self::$tree[$Node]['parent'] - 1]  = preg_replace('/(\{\/tz_element\})$/',self::$shortcode[$Node - 1].'$1',$parent_shortcode);
//                    unset(self::$shortcode[$Node - 1]);
//                }
//            }
//        }
    }
}