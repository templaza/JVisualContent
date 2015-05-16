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

$modelId    = $this -> item -> data_model_id;

$edit_link  = 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=edit_row&tmpl=component';
?>

<div data-element_type="tz_row" data-model-id="<?php echo $modelId;?>"
     data-element_id="row"
     class="tz_row-outer tz_sortable">
    <!-- Element row 0 level 0 -->
    <!-- Element row toolbar -->
    <div class="controls controls_row clearfix">
        <a data-toggle="jvc_tooltip" title="<?php echo JText::_('COM_JVISUALCONTENT_DRAG_ROW');?>"
           href="javascript:" class="tz_control column_move">
            <i class="jvc_fa jvc_fa-arrows"></i>
        </a>
        <span class="tz_row_layouts tz_control">
            <a data-toggle="jvc_tooltip" title="1/1" data-cells-mask="12" data-cells="11"
               class="tz_control-set-column set_columns l_11 tz_active"></a>
            <a data-toggle="jvc_tooltip" title="1/2 + 1/2" data-cells-mask="26" data-cells="12_12"
               class="tz_control-set-column set_columns l_12_12"></a>
            <a data-toggle="jvc_tooltip" title="2/3 + 1/3" data-cells-mask="29"
               data-cells="23_13"
               class="tz_control-set-column set_columns l_23_13"></a>
            <a data-toggle="jvc_tooltip" title="1/3 + 1/3 + 1/3" data-cells-mask="312" data-cells="13_13_13"
               class="tz_control-set-column set_columns l_13_13_13"></a>
            <a data-toggle="jvc_tooltip" title="1/4 + 1/4 + 1/4 + 1/4"
               data-cells-mask="420" data-cells="14_14_14_14"
               class="tz_control-set-column set_columns l_14_14_14_14"></a>
            <a data-toggle="jvc_tooltip" title="1/4 + 3/4" data-cells-mask="212" data-cells="14_34"
               class="tz_control-set-column set_columns l_14_34"></a>
            <a data-toggle="jvc_tooltip" title="1/4 + 1/2 + 1/4" data-cells-mask="313"
               data-cells="14_12_14"
               class="tz_control-set-column set_columns l_14_12_14"></a>
            <a data-toggle="jvc_tooltip" title="5/6 + 1/6" data-cells-mask="218" data-cells="56_16"
               class="tz_control-set-column set_columns l_56_16"></a>
            <a data-toggle="jvc_tooltip" title="1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6"
               data-cells-mask="642"
               data-cells="16_16_16_16_16_16"
               class="tz_control-set-column set_columns l_16_16_16_16_16_16"></a>
            <a data-toggle="jvc_tooltip" title="1/6 + 4/6 + 1/6" data-cells-mask="319" data-cells="16_23_16"
               class="tz_control-set-column set_columns l_16_46_16"></a>
            <a data-toggle="jvc_tooltip" title="1/6 + 1/6 + 1/6 + 1/2"
               data-cells-mask="424" data-cells="16_16_16_12"
               class="tz_control-set-column set_columns l_16_16_16_12"></a>
            <br>
            <a title="<?php echo JText::_('COM_JVISUALCONTENT_CUSTOM_LAYOUT');?>" data-cells-mask="custom" data-cells="custom"
               href="<?php echo 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=custom_column&tmpl=component'?>"
               class="tz_control-set-column set_columns custom_columns fancybox hasTooltip"><?php echo JText::_('COM_JVISUALCONTENT_CUSTOM');?></a>
        </span>
        <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_ADD_ITEM',mb_strtolower(JText::_('COM_JVISUALCONTENT_COLUMN')));?>" href="javascript:" class="tz_control column_add">
            <i class="jvc_fa jvc_fa-plus"></i>
        </a>
        <span class="tz_row_edit_clone_delete">
            <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_THIS_ROW'));?>" href="javascript:"
               class="tz_control column_delete">
                <i class="jvc_fa jvc_fa-times"></i>
            </a>
            <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_CLONE_ITEM',JText::_('COM_JVISUALCONTENT_THIS_ROW'));?>" href="javascript:" class="tz_control column_clone">
                <i class="jvc_fa jvc_fa-files-o"></i>
            </a>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',JText::_('COM_JVISUALCONTENT_THIS_ROW'));?>" href="javascript:"
               data-fancybox-href="<?php echo $edit_link;?>"
               class="tz_control column_edit fancybox fancybox-medium hasTooltip">
                <i class="jvc_fa jvc_fa-pencil"></i>
            </a>
            <a title="<?php echo JText::_('COM_JVISUALCONTENT_TOGGLE_ROW');?>" href="javascript:"
               class="tz_control column_toggle hasTooltip"
               data-toggle="jvc_collapse" data-parent="#tz_content"
               data-target="[data-model-id='<?php echo $modelId;?>'] .jvc_panel-collapse"
               aria-expanded="true">
                <i class="jvc_fa jvc_fa-caret-up"></i>
                <i class="jvc_fa jvc_fa-caret-down"></i>
            </a>
            <?php
            if($attributes = $this -> item -> attributes):
                if(isset($attributes['background_color']) && $bg_color = $attributes['background_color']):
            ?>
            <span class="tz_row_color" style="background-color: <?php echo $bg_color;?>"
                  title="<?php echo JText::_('COM_JVISUALCONTENT_ROW_BACKGROUND_COLOR');?>" data-toggle="jvc_tooltip"></span>
            <?php
                endif;
                if(isset($attributes['background_image']) && $bg_image = $attributes['background_image']):
            ?>
            <span class="tz_row_image" style="background-image: url(<?php echo JUri::root().$bg_image?>);"
                  title="<?php echo JText::_('COM_JVISUALCONTENT_ROW_BACKGROUND_IMAGE');?>" data-toggle="jvc_tooltip"></span>
            <?php
                endif;
            endif;?>
        </span>
    </div>
    <!-- End element row toolbar -->

    <div class="element_wrapper">
        <!-- Element row 0 content level 0 -->
        <div class="jvc_row tz_row_container tz_container_for_children">
            [/type]
        </div>
        <!-- End element row 0 content level 0 -->
    </div>
    <!-- End element row 0 level 0 -->
</div>