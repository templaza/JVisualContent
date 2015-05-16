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

jimport('joomla.application.component.controlleradmin');

class JVisualContentControllerShortcodes extends JControllerAdmin
{
    protected $n_unix   = '';

    public function getModel($name = 'ShortCodes', $prefix = 'JVisualContentModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function generate(){

        $app            = JFactory::getApplication();

        $model  = $this -> getModel();
        $input          = $app -> input;

        $data_params    = $input -> get('params',null,'array');
        $shortcode      = JRequest::getVar('shortcodes','','post','string',JREQUEST_ALLOWRAW);
        $ids            = array();

        if(count($data_params)){
            foreach($data_params as $d_param){
                if(isset($d_param['element_id']) && $d_param['element_id'] != 'row' && $d_param['element_id'] != 'column'){
                    $ids[]    = $d_param['element_id'];
                }
            }
        }

        if($shortcode){
//            $shortcode  = htmlspecialchars(stripslashes($shortcode));
            $tags   = JVisualContentShortCode::get_shortcode_tags_name($shortcode);
            if(is_array($tags) && count($tags)){
                $tags   = array_unique($tags);
                $tags   = array_filter($tags);
                $key    = array_search('tz_row',$tags);
                if(is_numeric($key)){
                    unset($tags[$key]);
                }
                $key    = array_search('tz_column',$tags);
                if(is_numeric($key)){
                    unset($tags[$key]);
                }
            }
            $model -> setState('filter.name',$tags);
        }else {
            if (count($ids)) {
                $model->setState('list.limit', count($ids));
            }

            $model->setState('shortcodes.id', $ids);
        }

        if($types = $model -> getItems()){
            if($data_params){
                $data_params    = $this -> prepare_attr($data_params);

                if(count($data_params)){
                    foreach($data_params as &$d_param){
                        if(isset($types[$d_param['element_id']])){
                            $d_param['html']    = $types[$d_param['element_id']] -> html;
                        }
                    }
                }

                $shortcode  = JVisualContentTree::generate_shortcode($data_params);
            }

            if($html       = JVisualContentShortCode::do_shortcode($shortcode)) {

                $js = null;

                if($css = JVisualContentShortCode::getCss()) {
                    $html .= '<style type="text/css">' . $css . '</style>';
                }

                if($_js = JVisualContentShortCode::getJavascript()) {
                    $js = $_js;
                }

                $html   .= '<script type="text/javascript">
                    '.$js.'
                    (function ($){
                        $(document).ready(function(){
                            if( $( \'[id*=jscontent_preview] [data-ride="jvc_carousel"]\' ).length ){
                                $(\'[id*=jscontent_preview] [data-ride="jvc_carousel"]\').each(function(index, element) {
                                    $(this)[index].slide = null;
                                });
                                $(\'[id*=jscontent_preview] [data-ride="jvc_carousel"]\').jvc_carousel(\'cycle\');
                            }

                            $("[data-toggle~=\'jvc_popover\']").jvc_popover("destroy")
                                .jvc_popover({"html": true});
                        });
                    } )(jQuery);
                    </script>';
            }else{
                $html   = '';
            }
            echo $html;
        }else{
            echo '';
        }

        die();
    }

    public function prepare_attr($params){
        if($params && count($params)){
            $_params            = $params;
            $date               = JFactory::getDate();
            $default_css_class  = array('background_image','background_color','background_style',
                'border_top_width','border_bottom_width','border_right_width','border_left_width','border_color'
            ,'border_style','margin_top','margin_bottom','margin_right','margin_left','padding_top','padding_bottom'
            ,'padding_right','padding_left');
            $default_el_class  = array('el_class','col_lg_offset','col_md_offset','col_sm_offset','col_xs_offset'
            ,'col_lg_size','col_md_size','col_xs_size','hidden_lg','hidden_md','hidden_sm','hidden_xs');

            foreach($_params as &$param){
                $param['prepare_params']    = array();
                $param['docs']['css']       = array();

                if($param['element_id'] == 'row' || $param['element_id'] == 'column'){
                    $css_class                  = '';
                    $el_class                   = '';

                    if($param['params'] && count($param['params'])){
                        foreach($param['params'] as $name => $value){
                            if(in_array($name,$default_css_class) || in_array($name,$default_el_class)){
                                if(in_array($name,$default_css_class) && $value){
                                    if($name == 'background_image'){
                                        $url        = $value;
                                        if(!preg_match('/http/m',$url)){
                                            $url    = JUri::root().$value;
                                        }
                                        $css_class  .= str_replace('_', '-', $name) . ': url(' . $url . '); ';
                                    }elseif($name == 'background_style') {
                                        if($value == 'cover' || $value == 'contain'){
                                            $css_class .= 'background-size: ' . $value . '; ';
                                        }elseif($value == 'repeat' || $value == 'no-repeat') {
                                            $css_class .= 'background-repeat: ' . $value . '; ';
                                        }
                                    }else{
                                        $css_class  .= str_replace('_', '-', $name) . ': ' . $value . '; ';
                                    }

                                }
                                if(in_array($name,$default_el_class)){
//                                    else{
                                    $el_class .= $value.' ';
//                                    }
                                }
                            }
                            else{
                                if($param['element_id'] == 'column' && $name == 'width') {
                                    $width  = 1;
                                    if(strpos($value,'/')){
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
                                    }
                                    $param['prepare_params'][$name] = 'jvc_col-sm-'.($width * 12);
                                }else{
                                    $param['prepare_params'][$name] = $value;
                                }
                            }
                        }
                    }

                    if(strlen(trim($css_class))){
                        $unix   = str_replace('.','',microtime(true));

                        // Create sole unix
                        while($unix == $this -> n_unix){
                            $unix   = str_replace('.','',microtime(true));
                        }
                        $this -> n_unix   = $unix;

                        $param['prepare_params']['css_class']   = '.tz_custom_css_'.$unix.'{'.trim($css_class).'}';
                        $param['docs']['css']                   = '.tz_custom_css_'.$unix.'{'.trim($css_class).'}';
                    }else{
                        $param['prepare_params']['css_class']   = '';
                    }

                    $param['prepare_params']['el_class']   = trim($el_class);

//                    if(preg_match('/(\[\w+)(.*?)(\].*?\[\/\w+\])/m',$param['shortcode'],$match)){
//                        $param['shortcode'] = $match[1].' css_class="'.$param['docs']['css']
//                            .'" el_class="'.$param['prepare_params']['el_class'].'"'.$match[3];
//                    }
                }else{
                    if(isset($param['params'])) {
                        $param['prepare_params'] = $param['params'];
                    }
                }
                if(is_array($param['prepare_params']) && count($param['prepare_params'])) {
                    $attr   = null;
                    foreach($param['prepare_params'] as $name => $value){
                        if($value && is_array($value)){
                            $attr   .= ' '.$name.'="'.implode(' ',$value).'"';
                        }else{
                            $attr   .= ' '.$name.'="'.$value.'"';
                        }
                    }
                    if(preg_match('/([\[|\{]\w+)(.*?)([\]|\}].*?[\[|\{]\/\w+[\]|\}])/m',$param['shortcode'],$match)){
                        $param['shortcode'] = preg_replace('/([\[|\{]\w+)(.*?)([\]|\}].*?[\[|\{]\/\w+[\]|\}])/m','$1'.$attr.'$3',$param['shortcode']);
//                        $param['shortcode'] = preg_replace('/([\[|\{]\w+)(.*?)([\]|\}].*?[\[|\{]\/\w+[\]|\}])/m','$1'.$attr.'$3',$param['shortcode']);
                    }
                }
            }
            return $_params;
        }
        return $params;
    }
}