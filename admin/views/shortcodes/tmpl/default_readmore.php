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

<div data-element_id="readmore" data-element_type="readmore" data-model-id="<?php echo $this -> item -> data_model_id;?>"
     class="tz_row-outer content_element tz_row_container  jvc_system-readmore tz_sortable">
    <div class="tz_controls">
        <div class="tz_controls-cc jvc_btn-group jvc_btn-group-xs">
            <a class="tz_control-btn tz_element-name column_move jvc_btn jvc_btn-success ui-sortable-handle">
                    <span class="tz_btn-content" data-toggle="jvc_tooltip" title="" data-original-title="<?php echo JText::_('COM_JVISUALCONTENT_READMORE');?>">
                                            <span class="jvc_fa jvc_fa-font tz_icon"></span>
                    <i class="jvc_fa jvc_fa-arrows"></i> <?php echo JText::_('COM_JVISUALCONTENT_READMORE');?></span>
            </a>
            <a class="tz_control-btn tz_control-btn-delete jvc_btn jvc_btn-success hasTooltip" href="javascript:" title="" data-original-title="<?php echo JText::sprintf('COM_JVISUALCONTENT_REMOVE_ITEM',JText::_('COM_JVISUALCONTENT_READMORE'));?>">
                <span class="tz_btn-content"><span class="jvc_fa jvc_fa-times"></span></span>
            </a>
        </div>
    </div>
    <div class="element_wrapper jvc_text-center column_move ui-sortable-handle">
        <h5 class="jvc_system-readmore-heading">
            <span><?php echo JText::_('COM_JVISUALCONTENT_READMORE');?></span>
        </h5>
    </div>
</div>