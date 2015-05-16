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

$modelId    = $this -> model_ids -> tz_column;
$e_name     = ($this -> jscontent_ename)?'_'.$this -> jscontent_ename:'';
$link       = 'index.php?option=com_jvisualcontent&view=elements&layout=modal&element_id=column&tmpl=component&e_name='
    .$e_name.'&'.JSession::getFormToken().'=1';

$edit_link  = 'index.php?option=com_jvisualcontent&view=shortcode&layout=edit&action=edit_column&tmpl=component';
?>
<script id="tmpl-jscontent-new-element-column" type="text/html">
    <div class="tz_column tz_sortable jvc_col-sm-12 content_holder<?php echo (!$this -> item || ($this -> item && !$this -> item -> id)?' tz_empty-column':'')?>"
         data-tz-column-width="11" data-element_type="tz_column" data-model-id="<?php echo $modelId;?>"
         data-element_id="column" data-width="1/1">
        <!-- Column 0 toolbar top of row 0 level 0 -->
        <div class="tz_controls tz_controls-visible controls">
            <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_PREPEND_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
               class="tz_control column_add fancybox" data-fancybox-href="<?php echo $link;?>">
                <i class="jvc_fa jvc_fa-plus"></i>
            </a>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
               data-fancybox-href="<?php echo $edit_link;?>"
               class="tz_control column_edit hasTooltip fancybox fancybox-medium">
                <i class="jvc_fa jvc_fa-pencil"></i>
            </a>
            <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:" class="tz_control column_delete">
                <i class="jvc_fa jvc_fa-times"></i>
            </a>
        </div>
        <!-- End column 0 toolbar top of row 0 level 0 -->
        <!-- Column 0 content of row 0 level 0 -->
        <div class="element_wrapper jvc_panel-collapse jvc_collapse jvc_in">
            <div data-toggle="modal"
                 data-fancybox-href="<?php echo $link;?>"
                 class="tz_column_container tz_container_for_children tz_empty-container fancybox">
                <i class="jvc_fa jvc_fa-plus"></i>
            </div>
        </div>
        <!-- End column 0 content of row 0 level 0 -->
        <!-- Column toolbar bottom -->
        <div class="tz_controls tz_controls-visible controls bottom-controls jvc_panel-collapse jvc_collapse jvc_in">
            <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_APPEND_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
               class="tz_control column_add fancybox" data-fancybox-href="<?php echo $link;?>">
                <i class="jvc_fa jvc_fa-plus"></i>
            </a>
            <a title="<?php echo JText::sprintf('COM_JVISUALCONTENT_EDIT_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:"
               data-fancybox-href="<?php echo $edit_link;?>"
               class="tz_control column_edit hasTooltip fancybox fancybox-medium">
                <i class="jvc_fa jvc_fa-pencil"></i>
            </a>
            <a data-toggle="jvc_tooltip" title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_THIS_COLUMN'));?>" href="javascript:" class="tz_control column_delete">
                <i class="jvc_fa jvc_fa-times"></i>
            </a>
        </div>
    </div>
</script>