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

$state  = $this -> state;

$action = $state -> get('shortcode.action','');

if($action == 'custom_column' || $action == 'edit_column' || $action == 'edit_row' || $action == 'edit_element'){

    $state  = $this -> state;
    $data   = $state -> get('shortcode.data');
?>
<div class="jvc_bootstrap3 jscontent_options">
    <?php
    if($action != 'custom_column'):
    $shortcode  = '';
//    $doc    = JFactory::getDocument();
//    $doc -> addCustomTag('<script type="text/javascript">
//(function($){
//    $(document).ready(function(){
//        $(\'#jvc_element-toolbar [data-toggle="jvc_tab"]\').on("show.bs.jvc_tab", function (e) {
//            var $button = e.target;
//            if($($button).attr("data-button") == "preview"){
//                var $data   = $("#adminForm").serializeArray(),
//                    $_html   = "'.addslashes($this -> item -> html).'",
//                    $html;
//                if($data.length){
//                var $attrs  = $_html.match(/\{(\w+)\}/gi);
//                if($attrs.length){
//                    if(typeof $attrs == "object" || typeof $attrs == "array"){
//                        $attrs  = $.map($attrs,function(value){
//                            return value.replace(/\{|\}/g,"");
//                        });
//                    }else{
//                        $attrs  = $attrs.replace(/\{|\}/g,"");
//                    }
//                }
//                    $.each($data,function(key,obj){
//                        if(typeof obj == "object"){
//                            if(Object.keys(obj).length){
//                            var $name   = obj["name"].split("][");
//                                if($attrs[$name]){
//                                var $reg  = new RegExp($name);
//                                }
//                            }
//                        }
//                    });
//                }
//            }
//        });
//    });
//})(jQuery);
//</script>');
    ?>
    <form method="post" id="adminForm"
          action="index.php?option=com_jvisualcontent&view=type&task=element.listfields&tmpl=component">
        <div class="tz_add-element-dialog-heading">
            <h5 class="modal-title">
                <?php
                switch($action):
                    case 'edit_column':
                        echo JText::_('COM_JVISUALCONTENT_HEADING_COLUMN_SETTING');
                        break;
                    case 'edit_row':
                        echo JText::_('COM_JVISUALCONTENT_HEADING_ROW_SETTING');
                        break;
                    case 'edit_element':
                        echo JText::sprintf('COM_JVISUALCONTENT_HEADING_SETTING',$this -> item -> title);
                        break;
                endswitch;
                ?>
            </h5>
        </div>

        <div class="jvc_container-fluid">
            <div class="jscontent_edit_form_elements jvc_form-horizontal">
                <div class="jscontent_shortcode-toolbar jvc_form-group jvc_clearfix" role="tablist">
                    <div class="jvc_pull-right">
                        <button data-save="true" class="jvc_btn tz_panel-btn-save jvc_btn-danger"><?php echo JText::_('COM_JVISUALCONTENT_SAVE_CHANGE');?></button>
                        <button data-dismiss="jvc_modal" class="jvc_btn tz_panel-btn-close jvc_btn-default" type="button"><?php echo JText::_('JTOOLBAR_CLOSE');?></button>
                    </div>
                </div>
<!--                <ul id="jvc_element-toolbar" class="jvc_nav jvc_navbar jvc_btn-group" role="tablist">-->
<!--                    <li role="presentation" class="jvc_btn jvc_btn-info jvc_active">-->
<!--                        <span role="tab" data-toggle="jvc_tab" data-target="#jvc_element-content">Content</span>-->
<!--                    </li>-->
<!--                    <li role="presentation" class="jvc_btn jvc_btn-info">-->
<!--                        <span role="tab" data-toggle="jvc_tab"-->
<!--                              data-button="preview"-->
<!--                              data-target="#jvc_element-preview">Preview</span>-->
<!--                    </li>-->
<!--                </ul>-->
<!--                <div class="jvc_tab-content">-->
<!--                    <div id="jvc_element-content" role="tabpanel" class="jvc_tab-pane jvc_active">-->
    <?php endif;?>
                <?php
                switch($action):
                    case 'custom_column':
                        echo $this -> loadTemplate('custom_column');
                        break;
                    case 'edit_column':
                        echo $this -> loadTemplate('column');
                        break;
                    case 'edit_row':
                        echo $this -> loadTemplate('row');
                        break;
                    case 'edit_element':
                        echo $this -> loadTemplate('element');
                        break;
                endswitch;

    if($action != 'custom_column'):
    ?>
<!--                    </div>-->
<!--                    <div id="jvc_element-preview" role="tabpanel" class="jvc_tab-pane">-->
<!--                        Test-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </form>
    <?php endif;?>

</div>
<?php
}