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

$doc    = JFactory::getDocument();
$e_name = ($this -> jscontent_ename)?'_'.$this -> jscontent_ename:'';
$link   = 'index.php?option=com_jvisualcontent&view=elements&layout=modal&tzshortcode=true&tzaddnew=true&tmpl=component&'.JSession::getFormToken().'=1';
?>
<div class="jvc_bootstrap3 jscontent_hidden">

    <?php echo $this -> loadTemplate('ajax_loading');?>

    <ul id="jscontent_toolbar<?php echo $e_name?>" role="tablist" class="jscontent_list-inline jscontent_toolbar jvc_btn-group jvc_form-group">
        <li class="jvc_btn jvc_btn-primary jvc_active" role="presentation">
            <span data-toggle="jvc_tab" class="jvc_btn-block"
                  data-target="#jscontent_content<?php echo $e_name;?>"
                  role="tab"><?php echo JText::_('COM_JVISUALCONTENT_CONTENT_BUILDER');?></span>
        </li>
        <li class="jvc_btn jvc_btn-primary" data-button-type="preview" role="presentation">
            <span data-toggle="jvc_tab" class="jvc_btn-block"
                  data-target="#jscontent_preview<?php echo $e_name?>"
                  role="tab"><?php echo JText::_('JGLOBAL_PREVIEW');?></span>
        </li>
    </ul>

    <div class="jvc_tab-content">
        <div id="jscontent_content<?php echo $e_name;?>" class="jscontent_content jvc_tab-pane jvc_active" role="tabpanel">
            <?php
            if($this -> html) {
                echo $this -> html;
            }
            ?>
<!--            --><?php //echo $this -> loadTemplate('readmore');?>

            <div id="tz_no-content-helper<?php echo $e_name;?>" class="tz_not-empty">
                <div class="tz_buttons">
                    <a href="<?php echo $link;?>" data-toggle="jvc_tooltip" title="<?php echo JText::_('COM_JVISUALCONTENT_ADD_ELEMENT');?>"
                       class="tz_add-element-not-empty-button tz_add-element-action jvc_btn-block fancybox">
                        <i class="jvc_fa jvc_fa-plus-square"></i>
                    </a>
                    <!--<a title="Add element" href="javascript:" class="tz_add-element-button vc_add-element-action tz_btn tz_btn-grace btn-md btn_3d" id="tz_no-content-add-element">Add element</a>-->
                    <!--<a title="Add text block" href="javascript:" class="tz_add-text-block-button tz_btn vc_btn-sky tz_btn-md vc_btn_3d" id="tz_no-content-add-text-block">Add text block</a>-->
                </div>
            </div>
        </div>
        <div id="jscontent_preview<?php echo $e_name;?>" class="jvc_tab-pane" role="tabpanel">
        </div>
    </div>
</div>