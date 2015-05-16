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
?>

<!-- Panel layout box -->
<div class="tz_add-element-dialog-heading">
    <h5 class="modal-title"><?php echo JText::_('COM_JVISUALCONTENT_ADD_ELEMENT');?></h5>
</div>

<div class="jvc_container-fluid">
    <div class="jvc_row jscontent_edit_form_elements">
        <div class="jvc_col-sm-12 tz_column tz_layout-panel-switcher tz_sortable">
            <div class="element_label"><?php echo JText::_('COM_JVISUALCONTENT_ROW_LAYOUT');?></div>
            <a title="1/1" data-cells-mask="12" data-cells="11" class="tz_layout-btn l_11"><span
                    class="icon"></span></a>
            <a title="1/2 + 1/2" data-cells-mask="26" data-cells="12_12" class="tz_layout-btn l_12_12"><span
                    class="icon"></span></a>
            <a title="2/3 + 1/3" data-cells-mask="29" data-cells="23_13" class="tz_layout-btn l_23_13"><span
                    class="icon"></span></a>
            <a title="1/3 + 1/3 + 1/3" data-cells-mask="312" data-cells="13_13_13"
               class="tz_layout-btn l_13_13_13"><span class="icon"></span></a>
            <a title="1/4 + 1/4 + 1/4 + 1/4" data-cells-mask="420" data-cells="14_14_14_14"
               class="tz_layout-btn l_14_14_14_14"><span class="icon"></span></a>
            <a title="1/4 + 3/4" data-cells-mask="212" data-cells="14_34"
               class="tz_layout-btn l_14_34"><span class="icon"></span></a>
            <a title="1/4 + 1/2 + 1/4" data-cells-mask="313" data-cells="14_12_14"
               class="tz_layout-btn l_14_12_14"><span class="icon"></span></a>
            <a title="5/6 + 1/6" data-cells-mask="218" data-cells="56_16"
               class="tz_layout-btn l_56_16"><span class="icon"></span></a>
            <a title="1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6" data-cells-mask="642"
               data-cells="16_16_16_16_16_16" class="tz_layout-btn l_16_16_16_16_16_16"><span
                    class="icon"></span></a>
            <a title="1/6 + 4/6 + 1/6" data-cells-mask="319" data-cells="16_23_16"
               class="tz_layout-btn l_16_46_16"><span class="icon"></span></a>
            <a title="1/6 + 1/6 + 1/6 + 1/2" data-cells-mask="424" data-cells="16_16_16_12"
               class="tz_layout-btn l_16_16_16_12"><span class="icon"></span></a>
            <small class="description text-muted clearfix"><em><?php echo JText::_('COM_JVISUALCONTENT_ROW_LAYOUT_DESCRIPTION');?></em></small>
        </div>
        <div class="jvc_col-sm-12 tz_column tz_sortable">
            <div class="element_label"><?php echo JText::_('COM_JVISUALCONTENT_CUSTOM_LAYOUT_FOR_ROW');?></div>
            <div class="edit_form_line">
                <input type="text" id="tz_row-layout" value="" class="textinput tz_row_layout"
                       name="padding">
                <button class="jvc_btn jvc_btn-primary" id="tz_row-layout-update"><?php echo JText::_('COM_JVISUALCONTENT_UPDATE');?></button>
                <small class="description jvc_text-muted jvc_clearfix"><em><?php echo JText::_('COM_JVISUALCONTENT_CUSTOM_LAYOUT_FOR_ROW_DESCRIPTION');?></em></small>
            </div>
        </div>
    </div>
</div>
<!-- End Panel layout box -->