<?php
/*------------------------------------------------------------------------

# JVisual Content Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
if(JFile::exists(JPATH_ROOT . '/administrator/components/com_jvisualcontent/includes/framework.php')) {
    require_once JPATH_ROOT . '/administrator/components/com_jvisualcontent/includes/framework.php';
}

class PlgSystemJVisualContent extends JPlugin{
    public $editor_buttons  = array();
    protected $jvc_head     = false;

    public function __construct(&$subject,$config=array()){
//        $app                = JFactory::getApplication();
//        if($app -> isSite()){
//            $doc    = JFactory::getDocument();
//            if($app -> isSite()){
//                $doc -> addStyleSheet(JVisualContentUri::root(true,null,true).'/bootstrap/'.JVISUALCONTENT_BOOTSTRAP_NAME.'/css/bootstrap.min.css');
//                $doc -> addStyleSheet(JVisualContentUri::root(true,null,true).'/css/jvisualcontent.min.css');
//                $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jvisualcontent-bootstrap3-conflict.min.js"></script>');
//                $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/bootstrap/'.JVISUALCONTENT_BOOTSTRAP_NAME.'/js/bootstrap.min.js"></script>');
//                $doc -> addCustomTag('<script>
//                    (function ($){
//                        $(document).ready(function(){
//                            if( $( \'[data-ride="jvc_carousel"]\' ).length ){
//                                $(\'[data-ride="jvc_carousel"]\').each(function(index, element) {
//                                    $(this)[index].slide = null;
//                                });
//                                $(\'[data-ride="jvc_carousel"]\').jvc_carousel(\'cycle\');
//                            }
//
//                            $("[data-toggle~=\'jvc_popover\']").jvc_popover("destroy")
//                                .jvc_popover({"html": true});
//                        });
//                    } )(jQuery);
//                </script>');
//            }
//        }

        parent::__construct($subject,$config);

        // Require jhtml classes from jvisualcontent
        JLoader::import('loader',JVISUALCONTENT_SYSTEM_PLUGIN.'/libraries');
        jvisualcontentimport('shortcode_admin');

    }
	
    public function onContentPrepare($context, &$article, &$params, $page = 0)
    {
        $app                = JFactory::getApplication();
        if($app -> isSite()){
            $content    = $article -> text;
            if(preg_match('/'.JVisualContentShortCode::get_shortcode_regex(JVisualContentShortCode::get_shortcode_tags_name($content)).'/s',$content)){
                // Load bootstrap library
                if(!$this -> jvc_head) {
                    $doc    = JFactory::getDocument();
                    $doc -> addStyleSheet(JVisualContentUri::root(true,null).'/css/jvisualcontent.min.css');
                    $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null).'/js/jvisualcontent-bootstrap3-conflict.min.js"></script>');
                    $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null).'/js/jvisualcontent-bootstrap.min.js"></script>');
                    $doc -> addCustomTag('<script>
                        (function ($){
                            $(document).ready(function(){
                                if( $( \'[data-ride="jvc_carousel"]\' ).length ){
                                    $(\'[data-ride="jvc_carousel"]\').each(function(index, element) {
                                        $(this)[index].slide = null;
                                    });
                                    $(\'[data-ride="jvc_carousel"]\').jvc_carousel(\'cycle\');
                                }

                                $("[data-toggle~=\'jvc_popover\']").jvc_popover("destroy")
                                    .jvc_popover({"html": true});
                            });
                        } )(jQuery);
                    </script>');
                    $this -> jvc_head = true;
                }

                $content    = preg_replace('/(^<p>)|(<\/p>$)/','',$content);
                $article -> text = '<div class="jvc_bootstrap3">'.JVisualContentShortCode::do_shortcode($content).'</div>';
//                $article -> text = JVisualContentShortCode::do_shortcode($content);

                // Add field's and shortcode's css
                if($css = JVisualContentShortCode::getCss()) {
                    $article -> text .= '<style type="text/css">' . $css . '</style>';
                }
                // Add shortcode's js
                if($js = JVisualContentShortCode::getJavascript()){
                    $article -> text    .= '<script type="text/javascript">'.$js.'</script>';
                }
			}
        }

    }
	
    public function onSetEditorName($name,$button){
        $_button    = $button;
        if(is_object($_button)){
            $_button    = '<a class="btn '.$button ->class.'" href="'.$button -> link.'" rel="'.$button -> options.'">'.$button -> text.'</a>';
        }
        $this -> editor_buttons[$name]  = $_button;
    }
    function onBeforeRender(){
        $app                = JFactory::getApplication();
        $input              = $app -> input;
//            'index.php?option=com_media&view=imagesList&tmpl=component&folder=&asset=com_jvisualcontent';
        // Add style for com_media
        if($app -> isAdmin() && $input -> get('option') == 'com_media' && $input -> get('view') == 'imagesList'
            && ($input -> get('asset') == 'com_jvisualcontent' || $input -> get('asset') == 'undefined')){
            $doc    = JFactory::getDocument();
            $doc -> addStyleSheet(JUri::root(true).'/plugins/system/jvisualcontent/css/jmedia.min.css');
        }
//        var_dump($this -> editor_buttons); die();
    }
    function onAfterRender(){
        $app                = JFactory::getApplication();
        $input              = $app -> input;

        if($app -> isAdmin() && ($input -> get('option') != 'com_jvisualcontent' )){
            if(($input -> get('option') == 'com_content' && $input -> get('view') == 'article')
                || ($input -> get('option') == 'com_tz_portfolio' && $input -> get('view') == 'article')
                || ($input -> get('option') == 'com_modules' && $input -> get('view') == 'module')){

                $c_params   = JComponentHelper::getParams('com_jvisualcontent');
                $context    = $input -> get('option').'.'.$input -> get('view');
//                if(in_array($context,$c_params -> get('context',array()))){
                    $show_switch_button = 0;

                    if($body   = JResponse::getBody()){
                        $matches        = array();
                        $close_tags     = array();

                        $config         = JFactory::getConfig();

                        // Require jhtml classes from jvisualcontent
                        JVisualContentLoader::load($body);

                        if(preg_match_all('/(\<(\w+)\s.*?\>)/s',$body,$matches)){
                            $close_tags = $matches[2];
                            $matches    = $matches[1];
                        }
                        if(count($this -> editor_buttons)){
                            $lang   = JFactory::getLanguage();
                            $lang -> load('com_jvisualcontent',JPATH_ADMINISTRATOR);

                            foreach($this -> editor_buttons as $name => $button){

                                if(preg_match('/(\<textarea.*?id\=[\'|\"]'.preg_quote($name,'/').'[\'|\"].*?\>)(.*?<\/textarea\>)/ms',$body,$match)){
                                    $mapper         = '';
                                    $text_area_html = '';
                                    $item           = null;
                                    $model          = null;

                                    $js_doc = new JVisualContentDocument();

                                    if(isset($match[2]) && $content    = $match[2]){
                                        $content    = str_replace('</textarea>','',$content);
                                        $content    = trim($content);
                                        $content    = htmlspecialchars_decode($content);

                                        if(strlen($content)){
                                            if(preg_match('/'.JVisualContentShortCodeAdmin::get_shortcode_regex().'/s',$content)){
                                                if(preg_match('/(<\w+>)(\[\w+.*?\])/s',$content)){
                                                    $content    = preg_replace('/(<\w+>)(\[\w+.*?\])/s','$2',$content);
                                                }
                                                if(preg_match('/(\[\w+.*?\])(<\/\w+>)/s',$content)){
                                                    $content    = preg_replace('/(\[\w+.*?\])(<\/\w+>)/s','$1',$content);
                                                }

                                                if(preg_match('/(<\w+>)(\[\/\w+.*?\])/s',$content)){
                                                    $content    = preg_replace('/(<\w+>)(\[\/\w+.*?\])/s','$2',$content);
                                                }

                                                if(preg_match('#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i',$content)){
                                                    $content_split  = preg_split('#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i', $content, 2);
                                                    if(count($content_split)) {
                                                        $content = $content_split[0] . '[readmore]' . $content_split[1];
                                                    }
                                                }

                                                $text_area_html = JVisualContentShortCodeAdmin::do_shortcode_admin($content,$name);
                                                if($_mapper = JVisualContentShortCodeAdmin::get_mapper()){
                                                    $mapper = json_encode($_mapper);
                                                }

                                                if($css = JVisualContentShortCodeAdmin::getCss()) {
                                                    $css    = trim($css);
                                                    if(!empty($css)) {
                                                        $js_doc->addCustomTag('<style type="text/css" id="jvc_style_' . $name . '">' . $css . '</style>');
                                                    }
                                                }
                                                if($_script_html    = JVisualContentShortCodeAdmin::get_script_html()){
                                                    $script_html    = array();
                                                    foreach($_script_html as $sc){
                                                        $script_html[]  = $sc['html'];
                                                        if(isset($sc['children'])){
                                                            $script_html[]  = implode("\n",$sc['children']);
                                                        }
                                                    }

                                                    if(count($script_html)) {
                                                        $js_doc->addCustomTag(implode("\n",$script_html));
                                                    }
                                                }
                                            }else{
                                                $model = JModelLegacy::getInstance('Elements', 'JVisualContentModel', array('ignore_request' => true));
                                                $model -> setState('filter.name','text_block');
                                                if(preg_match('#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i',$content)){
                                                    $content_split  = preg_split('#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i', $content, 2);
                                                    if(count($content_split)) {
                                                        $content = '[tz_row][tz_column width="1/1"][text_block editor=""]'
                                                            . $content_split[0] . '[/text_block][/tz_column][/tz_row][readmore]'
                                                            . '[tz_row][tz_column width="1/1"][text_block editor=""]'
                                                            . $content_split[1] . '[/text_block][/tz_column][/tz_row]';
                                                    }
                                                }else{
                                                    $content    = '[tz_row][tz_column width="1/1"][text_block editor=""]'.$content.'[/text_block][/tz_column][/tz_row]';
                                                }
    //                                            $content    = '[tz_row][tz_column width="1/1"][text_block editor=""]'.$content.'[/text_block][/tz_column][/tz_row]';
                                                $text_area_html = JVisualContentShortCodeAdmin::do_shortcode_admin($content,$name);

                                                if($css = JVisualContentShortCodeAdmin::getCss()) {
                                                    $css    = trim($css);
                                                    if(!empty($css)) {
                                                        $js_doc->addCustomTag('<style type="text/css" id="jvc_style_' . $name . '">' . $css . '</style>');
                                                    }
                                                }

                                                if($_mapper = JVisualContentShortCodeAdmin::get_mapper()){
                                                    $item   = JVisualContentShortCodeAdmin::get_shortcode_tags('text_block');
                                                    $item   = $item['text_block'];
                                                    $item -> fields = null;
                                                    $mapper = json_encode($_mapper);
                                                }
                                            }
                                        }
                                    }

                                    $parent     = '';
                                    $cur_key    = array_search($match[1],$matches);
                                    if($cur_key){
                                        $parent = $matches[$cur_key - 1];
                                    }
                                    if($parent){
                                        $doc    = JFactory::getDocument();

                                        // Require controllers from com_jvisualcontent
                                        JLoader::register('JVisualContentController', JVISUALCONTENT_COMPONENT_ADMIN.'/controller.php');
                                        JLoader::register('JVisualContentViewShortCode', JVISUALCONTENT_COMPONENT_ADMIN.'/views/shortcode/view.html.php');

                                        $controller     = JControllerLegacy::getInstance('JVisualContentController',array('name' => 'ShortCode'));
                                        $controllers    = JControllerLegacy::getInstance('JVisualContentController',array('name' => 'ShortCodes'));

                                        $view           =    $controller -> getView('ShortCode','html','JVisualContentView');
                                        $view -> addTemplatePath(JVISUALCONTENT_COMPONENT_ADMIN.'/views/shortcode/tmpl');

                                        $views          =    $controllers -> getView('ShortCodes','html','JVisualContentView');
                                        $views -> addTemplatePath(JVISUALCONTENT_COMPONENT_ADMIN.'/views/shortcodes/tmpl');

                                        $model_ids      = $view -> model_ids;

                                        $view -> assign('jscontent_ename',$name);
                                        $views -> assign('jscontent_ename',$name);

                                        $shortcode  = '[tz_row css_class="" el_class=""][tz_column  offset="jvc_col-sm-12"][/tz_column][/tz_row]';
                                        $html       = JVisualContentShortCode::do_shortcode($shortcode);

                                        $js_doc -> addCustomTag($this -> loadTemplate('script_html_row',$view));
                                        $js_doc -> addCustomTag($this -> loadTemplate('script_html_column',$view));
                                        if($item) {
                                            $view -> assign('item',$item);
                                            $js_doc -> addCustomTag($this -> loadTemplate('script_html_element_original',$view));
                                        }

                                        if(!$mapper){
                                            $mapper = '{
                                                    "'.$model_ids -> tz_row.'":{
                                                        id          : "'.$model_ids -> tz_row.'",
                                                        element_id  : "row",
                                                        name       : "tz_row",
                                                        parent_id  : "",
                                                        params     : {
                                                            "background_color"      : "rgb(106, 168, 79)",
                                                            "background_image"      : "images/headers/raindrops.jpg",
                                                            "background_style"      : "contain",
                                                            "border_color"          : "rgb(241, 194, 50)",
                                                            "border_style"          : "dashed",
                                                            "el_class"              : "test",
                                                            "margin_top"            : "1px",
                                                            "margin_right"          : "1px",
                                                            "margin_bottom"         : "1px",
                                                            "margin_left"           : "1px",
                                                            "padding_top"           : "1px",
                                                            "padding_right"         : "1px",
                                                            "padding_bottom"        : "1px",
                                                            "padding_left"          : "1px",
                                                            "border_top_width"      : "1px",
                                                            "border_right_width"    : "1px",
                                                            "border_bottom_width"   : "1px",
                                                            "border_left_width"     : "1px",
                                                            "width"                 : "jvc_container",
                                                            "text_align"            : "right"
                                                        },
                                                        shortcode  : "[tz_row][/tz_row]"
                                                    },
                                                    "'.$model_ids -> tz_column.'":{
                                                        id          : "'.$model_ids -> tz_column.'",
                                                        element_id  : "column",
                                                        name       :"tz_column",
                                                        parent_id  : "'.$model_ids -> tz_row.'",
                                                        params     : {
                                                            "background_color"      : "rgb(106, 168, 79)",
                                                            "background_image"      : "images/headers/raindrops.jpg",
                                                            "background_style"      : "contain",
                                                            "border_color"          : "rgb(241, 194, 50)",
                                                            "border_style"          : "dashed",
                                                            "font_color"            : "rgb(61, 133, 198)",
                                                            "el_class"              : "test",
                                                            "margin_top"            : "1px",
                                                            "margin_right"          : "1px",
                                                            "margin_bottom"         : "1px",
                                                            "margin_left"           : "1px",
                                                            "padding_top"           : "1px",
                                                            "padding_right"         : "1px",
                                                            "padding_bottom"        : "1px",
                                                            "padding_left"          : "1px",
                                                            "border_top_width"      : "1px",
                                                            "border_right_width"    : "1px",
                                                            "border_bottom_width"   : "1px",
                                                            "border_left_width"     : "1px",
                                                            "width"                 : "1/1",
                                                            "col_lg_size"           : "",
                                                            "col_md_size"           : "",
                                                            "col_xs_size"           : "",
                                                            "col_lg_offset"         : "jvc_col-lg-offset-12",
                                                            "col_md_offset"         : "jvc_col-md-offset-10",
                                                            "col_sm_offset"         : "jvc_col-sm-offset-8",
                                                            "col_xs_offset"         : "jvc_col-xs-offset-6",
                                                            "hidden_xs"             : "jvc_hidden-xs",
                                                            "hidden_sm"             : "jvc_hidden-sm",
                                                            "hidden_md"             : "jvc_hidden-md",
                                                            "hidden_lg"             : "jvc_hidden-lg"
                                                        },
                                                        shortcode  : "[tz_column][/tz_column]"
                                                    }
                                                }';
                                        }

                                        $js_doc -> addScriptDeclaration('
                                        jQuery(function () {
                                            var jscontent_builder_content = [];
                                            jQuery(window).load(function(){
                                                jQuery(".jvc_bootstrap3").removeClass("jscontent_hidden");

                                            });
                                            jQuery(\'#jscontent_content_'.$name.'\').jvcBuilder({
                                                name                : "'.$name.'",
                                                rootPath            : "'.JUri::root().'",
                                                token               : "'.JSession::getFormToken().'",
                                                once_prepare_mapper : '.($mapper?'true':'false').',
                                                ajax_loading_html   : "'.JHtmlJVisualContent::jsaddslashes($this -> loadTemplate('ajax_loading',$views)).'",
                                                jInsertEditorText   : function(html){
                                                    jInsertEditorText(html,"'.$name.'");
                                                },
                                                mapper: '.$mapper.',
                                                previewer: {
                                                    name: "#jscontent_preview_'.$name.'",
                                                    html: "'.addslashes($html).'"
                                                }
                                            });
                                        });');
                                        $body   = $js_doc -> render();


                                        $body   = preg_replace('/('.preg_quote($parent.$match[1]).')/s',$button.'$1',$body);

                                        if($text_area_html){
                                            $views -> assign('html',$text_area_html);
                                        }

                                        if(preg_match('/'.$parent.'.*?id=[\"|\']'.$name.'[\"|\'].*?<\/'.$close_tags[$cur_key - 1].'>/s',$body,$test)){
                                            if($config -> get('editor') == 'none'){
                                                $body = preg_replace('/(' . $parent . ')(.*?id=[\"|\']' . $name . '[\"|\'].*?)(<\/' . $close_tags[$cur_key - 1] . '>)/s',
                                                    '$1'.$this->loadTemplate('system', $views)
                                                    .'<div class="com_jvisualcontent_editor">$2</div>$3', $body);
                                            }else {
                                                $body = preg_replace('/' . $parent . '.*?id=[\"|\']' . $name . '[\"|\'].*?<\/' . $close_tags[$cur_key - 1] . '>/s',
                                                    $this->loadTemplate('system', $views)
                                                    . '<div class="com_jvisualcontent_editor">$0</div>', $body);
                                            }
        //                                    '<div class="com_jvisualcontent_editor">$0</div>'
                                        }
                                    }
                                }
                            }
                        }
                        JResponse::setBody($body);
                    }
//                }
            }
        }
    }

    protected function loadTemplate($tpl,$view,$layout=null){
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
    function onAfterInitialise(){
        $app    = JFactory::getApplication();
        // Check site location
        if($app -> isSite()){
            return;
        }


    }
}