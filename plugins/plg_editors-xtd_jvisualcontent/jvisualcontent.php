<?php
/*------------------------------------------------------------------------

# TZ Shortcode Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;


class PlgButtonJVisualContent extends JPlugin{

    protected $autoloadLanguage = true;

    function __construct(&$subject,$config=array()) {

        parent::__construct($subject,$config);

        $params = $this -> params;

        $app                = JFactory::getApplication();
        $input              = $app -> input;
        $c_params   = JComponentHelper::getParams('com_jvisualcontent');
        $context    = $input -> get('option').'.'.$input -> get('view');

//        if(in_array($context,$c_params -> get('context',array()))){

            JLoader::import('framework',JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/includes');

            $doc    = JFactory::getDocument();
            $doc -> addStyleSheet(JUri::root(true).'/plugins/editors-xtd/jvisualcontent/css/jquery.fancybox.css');
            $doc -> addStyleSheet(JVisualContentUri::root(true,null,true).'/css/jvisualcontent-admin.min.css');

            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jquery-ui.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jquery-ui-conflict.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jvisualcontent-bootstrap3-conflict.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/spectrum.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jvisualcontent-bootstrap.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/bootstrap3-typeahead.min.js"></script>');

            $doc -> addCustomTag('<script type="text/javascript">
            (function ($) {
                $(document).ready(function(){
                    $("[data-toggle~=\'jvc_tooltip\'],[data-toggle~=\'tooltip\'],.hasTooltip")
                    .jvc_tooltip({"html": true,"container": "body"});
                    if( $( \'[id^=jscontent_content] [data-ride="jvc_carousel"]\' ).length ){
                        $(\'[id^=jscontent_content] [data-ride="jvc_carousel"]\').each(function(index, element) {
                            $(this)[index].slide = null;
                        });
                        $(\'[id^=jscontent_content] [data-ride="jvc_carousel"]\')
                        .jvc_carousel( {interval: false,pause : "hover"} ).jvc_carousel(\'pause\');
                    }
                });
            })(jQuery);</script>
            ');

            $doc -> addCustomTag('<script type="text/javascript" src="'.JUri::root(true).'/plugins/editors-xtd/jvisualcontent/js/jquery.fancybox.pack.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/libs.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/general-tree.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/shortcode-tree.min.js"></script>');
            $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jvisualcontent.min.js"></script>');

            $width      = null;
            $height     = null;
            $autosize   = null;
            if($params -> get('lightbox_width')){
                if(preg_match('/%|px/',$params -> get('tz_lightbox_width'))){
                    $width  = 'width:\''.$params -> get('tz_lightbox_width').'\',';
                }
                else
                    $width  = 'width:'.$params -> get('tz_lightbox_width').',';
            }
            if($params -> get('lightbox_height')){
                if(preg_match('/%|px/',$params -> get('tz_lightbox_height'))){
                    $height  = 'height:\''.$params -> get('tz_lightbox_height').'\',';
                }
                else
                    $height  = 'height:'.$params -> get('tz_lightbox_height').',';
            }

            if($width || $height){
                $autosize   = 'fitToView: false,autoSize: false,';
            }

            $doc -> addScriptDeclaration('
                jQuery(document).ready(function(){
                    jQuery(\'.fancybox:not(.custom_columns):not(.fancybox-medium)\').fancybox({
                        type        :\'iframe\',
                        '.$width.$height.$autosize.'
                        openEffect  : "elastic",
                        beforeLoad  : function(current, previous){
                            if(this.element.parents("[id^=tz_no-content-helper]").length){
                                if(this.element.parents("[id^=jscontent_content]").length){
                                    var $parent = this.element.parents("[id^=jscontent_content]");
                                    if(!$parent.find("[data-model-id][data-element_type=readmore]").length){
                                        if(!this.href.match(/types\[\]/)){
                                            this.href   += "&types[]=readmore";
                                        }
                                    }
                                }
                            }
                            var $model  = jQuery.jvcBuilder.model.get_model();
                            if($model.length){
                                if($model.parents("[data-model-id][data-aria-control]").length){
                                    if(!this.href.match(/element_id/) && $model.attr("data-element_id")){
                                        this.href   += "&element_id=" + $model.attr("data-element_id");
                                    }
                                }
                            }
                        }
                    });
                    jQuery(\'.fancybox.custom_columns\').fancybox({
                        type        :\'ajax\',
                        width       : \'600px\',
                        autoSize    : false,
                        height      : \'240px\',
                        openEffect  : "elastic",
                        wrapCSS     : "tz_fancybox-wrap",
                        afterShow   : function(){
                            jQuery(".tz_fancybox-wrap").draggable({
                                start: function(event,ui){
                                    ui.helper.width(ui.helper.width());
                                }
                            });
                        }
                    });
//                    jQuery(\'.fancybox.fancybox-medium\').fancybox({
//                        type        :\'iframe\',
//                        openEffect  : "elastic",
//                        wrapCSS     : "jscontent_edit-fancybox-wrap",
//                        beforeLoad  : function(){
//                            if(jQuery.jvcBuilder.model){
//                                var $jscontent_builder  = jQuery.jvcBuilder,
//                                $model_id   = $jscontent_builder.model.get("id"),
//                                $mapper = $jscontent_builder.mapper,
//                                data    = {};
//                                if($mapper && $mapper[$model_id]){
//                                    data["data"]    = $mapper[$model_id];
//                                    this.href   += "&" + jQuery.param(data);
//                                }
//                            }
//                        },
//                        afterLoad   : function(){
//                            var iframe    = this.content;
//                            this.jscontent_fancybox_toolbar  = iframe.contents().find(".jscontent_fancybox-toolbar").prop(\'outerHTML\');
//                            iframe.contents().find(".jscontent_fancybox-toolbar").remove();
//                            this.content    = iframe;
//
//                            var $content    = jQuery(this.content);
//                            if($content.find(".jscontent_fancybox-toolbar").length){
//                                this.jscontent_fancybox_toolbar  = $content.find(".jscontent_fancybox-toolbar").clone(true).prop(\'outerHTML\');
//                                $content.find(".jscontent_fancybox-toolbar").remove();
//                                this.content    = $content.prop("outerHTML");
//                            }
//                        },
//                        beforeShow  : function(){
//                            if(this.jscontent_fancybox_toolbar && this.jscontent_fancybox_toolbar.length){
//                                this.title      = this.jscontent_fancybox_toolbar;
//                                jQuery(".jscontent_fancybox-toolbar .tz_panel-btn-close").die("click")
//                                    .live("click",function(){
//                                    jQuery.fancybox.close();
//                                });
//                            }
//                        },
//                        helpers     : {
//                            title: {
//                                type: "inside"
//                            }
//                        },
//                        afterShow   : function(){
//                            jQuery(".tz_fancybox-wrap").draggable({
//                                start: function(event,ui){
//                                    ui.helper.width(ui.helper.width());
//                                }
//                            });
//                        }
//                    });

                    jQuery(".fancybox.fancybox-medium").fancybox({
                        type        : "iframe",
                        openEffect  : "elastic",
                        wrapCSS     : "jscontent_edit-fancybox-wrap",
                        autoSize    : false,
                        height      : "90%",
                        beforeLoad  : function () {
                            //$.fancybox.hideLoading();
                            this.jscontent_fancybox_html    = "";

                            var $jvc_fancy    = this,
                                $href   = this.href,
                                data = {};
                            if (jQuery.jvcBuilder.model) {
                                var $jscontent_builder = jQuery.jvcBuilder,
                                    $model_id = $jscontent_builder.model.get("id"),
                                    $mapper = $jscontent_builder.mapper;
                                if ($mapper && $mapper[$model_id]) {
                                    data = $mapper[$model_id];
                                }
                            }
                            jQuery.ajax({
                                type: "POST",
                                url:  $href,
                                cache: false,
                                data: {
                                    data: data
                                }
                            }).done(function(html){
//                                    $jvc_fancy.jvc_fancybox_html   = html;
                                    $jvc_fancy.href = "";
                                var iframe = document.getElementById($jvc_fancy.wrap.find("iframe").first().attr("id"));
                                if(iframe && iframe.length){
                                    iframe  = iframe[0];
                                }
                                if(iframe && iframe){
                                    var iframedoc = iframe.document;
                                    if (iframe.contentDocument)
                                        iframedoc = iframe.contentDocument;
                                    else if (iframe.contentWindow)
                                        iframedoc = iframe.contentWindow.document;
                                    iframedoc.open();
                                    iframedoc.write(html);
                                    iframedoc.close();

                                }
                            });
                        },
                        afterLoad   : function () {
                                this.content    = "";
//                            //iframe.contents().empty().append(this.jscontent_fancybox_html);
//
//                            var iframe = document.getElementById(this.wrap.find("iframe").first().attr("id"));
//                            console.log(iframe);
//                                if(iframe && iframe.length){
//                                    iframe  = iframe[0];
//                                }
//                                if(iframe && iframe){
//                                    var iframedoc = iframe.document;
//                                    if (iframe.contentDocument)
//                                        iframedoc = iframe.contentDocument;
//                                    else if (iframe.contentWindow)
//                                        iframedoc = iframe.contentWindow.document;
//                                    iframedoc.open();
//                                    iframedoc.write(this.jscontent_fancybox_html);
//                                    iframedoc.close();
//                                }
//
//
//
////                            var iframe = this.content;
////                            if(iframe && iframe.length) {
//////                                iframe.contents().find("html").html($(this.test).find("html").html());
////                                this.jscontent_fancybox_toolbar = iframe.contents().find(".jscontent_fancybox-toolbar").prop("outerHTML");
////                                iframe.contents().find(".jscontent_fancybox-toolbar").remove();
////                                this.content = iframe;
////                            }
////
////                            var $content = $(this.content);
////                            if ($content && $content.length && $content.find(".jscontent_fancybox-toolbar").length) {
////                                this.jscontent_fancybox_toolbar = $content.find(".jscontent_fancybox-toolbar").clone(true).prop("outerHTML");
////                                $content.find(".jscontent_fancybox-toolbar").remove();
////                                this.content = $content.prop("outerHTML");
////                            }
                        },
                        beforeShow  : function () {
                            if (this.jscontent_fancybox_toolbar && this.jscontent_fancybox_toolbar.length) {
                                this.title = this.jscontent_fancybox_toolbar;
                                jQuery(".jscontent_fancybox-toolbar .tz_panel-btn-close").die("click")
                                    .live("click", function () {
                                        $.fancybox.close();
                                    });
                            }
                        },
                        helpers : {
                            title: {
                                type: "inside"
                            }
                        },
                        afterShow   : function () {
                            jQuery(".tz_fancybox-wrap").draggable({
                                start: function (event, ui) {
                                    ui.helper.width(ui.helper.width());
                                }
                            });
                        }
                    });
                });
            ');
//        }


    }


    public function onDisplay($name)
    {
        $params = $this -> params;
        $button = null;

        JPluginHelper::importPlugin('system','jvisualcontent');
        $dispatcher	= JDispatcher::getInstance();
        $dispatcher -> trigger('onSetEditorName',array($name,$button));
    }
}