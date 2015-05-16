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

abstract class JHtmlTZ_Image
{
    public static function media($value,$name,$id,$attribs = null,$directory=null,$preview=null,$disabled=null,$asset=null,$link=null){

        if ($asset == '')
        {
            $asset = JFactory::getApplication()->input->get('option');
        }

        // Load the modal behavior script.
        JHtml::_('behavior.modal','.jscontent_modal');

        // Include jQuery
        JHtml::_('jquery.framework');

        // Build the script.
        $script = array();
        $script[] = '	function jInsertFieldValue(value, id) {';
        $script[] = '		var $ = jQuery.noConflict();';
        $script[] = '		var old_value = $("#" + id).val();';
        $script[] = '		if (old_value != value) {';
        $script[] = '			var $elem = $("#" + id);';
        $script[] = '			$elem.val(value);';
        $script[] = '			$elem.trigger("change");';
        $script[] = '			if (typeof($elem.get(0).onchange) === "function") {';
        $script[] = '				$elem.get(0).onchange();';
        $script[] = '			}';
        $script[] = '			jMediaRefreshPreview(id);';
        $script[] = '		}';
        $script[] = '	}';

        $script[] = '	function jMediaRefreshPreview(id) {';
        $script[] = '		var $ = jQuery.noConflict();';
        $script[] = '		var value = $("#" + id).val();';
        $script[] = '		var $img = $("#" + id + "_preview");';
        $script[] = '		if ($img.length) {';
        $script[] = '			if (value) {';
        $script[] = '				$img.attr("src", value);';
        $script[] = '				$("#" + id + "_preview_empty").hide();';
        $script[] = '				$("#" + id + "_preview_img").show()';
        $script[] = '			} else { ';
        $script[] = '				$img.attr("src", "")';
        $script[] = '				$("#" + id + "_preview_empty").show();';
        $script[] = '				$("#" + id + "_preview_img").hide();';
        $script[] = '			} ';
        $script[] = '		} ';
        $script[] = '	}';

        $script[] = '	function jMediaRefreshPreviewTip(tip)';
        $script[] = '	{';
        $script[] = '		var $ = jQuery.noConflict();';
        $script[] = '		var $tip = $(tip);';
        $script[] = '		var $img = $tip.find("img.media-preview");';
        $script[] = '		$tip.find("div.tip").css("max-width", "none");';
        $script[] = '		var id = $img.attr("id");';
        $script[] = '		id = id.substring(0, id.length - "_preview".length);';
        $script[] = '		jMediaRefreshPreview(id);';
        $script[] = '		$tip.show();';
        $script[] = '	}';

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

        $attribs['class']   = isset($attribs['class']) ? 'jvc_input-small jvc_form-control '.$attribs['class'] : 'jvc_input-small jvc_form-control';

        $html = array();

        if (is_array($attribs))
        {
            $attribs = JArrayHelper::toString($attribs);
        }

        // The text field.
        $html[] = '<div class="jvc_input-group">';

        // The Preview.
        $showPreview = true;
        $showAsTooltip = false;

        if(!$preview || ($preview && !isset($preview['preview']))){
            $preview['preview'] = '';
        }

        switch ($preview['preview'])
        {
            case 'no': // Deprecated parameter value
            case 'false':
            case 'none':
                $showPreview = false;
                break;

            case 'yes': // Deprecated parameter value
            case 'true':
            case 'show':
                break;

            case 'tooltip':
            default:
                $showAsTooltip = true;
                $options = array(
                    'onShow' => 'jMediaRefreshPreviewTip',
                );
                JHtml::_('behavior.tooltip', '.hasTipPreview', $options);
                break;
        }

        if ($showPreview)
        {
            if ($value && file_exists(JPATH_ROOT . '/' . $value))
            {
                $src = JUri::root() . $value;
            }
            else
            {
                $src = '';
            }

            if(!$preview || ($preview && !isset($preview['previewWidth']))){
                $preview['previewWidth']    = 300;
            }
            if(!$preview || ($preview && !isset($preview['previewHeight']))){
                $preview['previewHeight']    = 200;
            }

            $width = $preview['previewWidth'];
            $height = $preview['previewHeight'];
            $style = '';
            $style .= ($width > 0) ? 'max-width:' . $width . 'px;' : '';
            $style .= ($height > 0) ? 'max-height:' . $height . 'px;' : '';

            $imgattr = array(
                'id' => $id . '_preview',
                'class' => 'media-preview',
                'style' => $style,
            );

            $img = JHtml::image($src, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $imgattr);
            $previewImg = '<div id="' . $id . '_preview_img"' . ($src ? '' : ' style="display:none"') . '>' . $img . '</div>';
            $previewImgEmpty = '<div id="' . $id . '_preview_empty"' . ($src ? ' style="display:none"' : '') . '>'
                . JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';

            if ($showAsTooltip)
            {
                $html[] = '<div class="media-preview jvc_input-group-addon'.($preview && isset($preview['class'])?' '.$preview['class']:'').'">';
                $tooltip = $previewImgEmpty . $previewImg;
                $options = array(
                    'title' => JText::_('JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE'),
                    'text' => '<i class="icon-eye"></i>',
                    'class' => 'hasTipPreview'
                );

                $html[] = JHtml::tooltip($tooltip, $options);
                $html[] = '</div>';
            }
            else
            {
                $html[] = '<div class="media-preview jvc_add-on" style="height:auto">';
                $html[] = ' ' . $previewImgEmpty;
                $html[] = ' ' . $previewImg;
                $html[] = '</div>';
            }
        }

        $html[] = '	<input type="text" name="' . $name . '" id="' . $id . '" value="'
            . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" readonly="readonly"' . $attribs . ' />';

        if ($value && file_exists(JPATH_ROOT . '/' . $value))
        {
            $folder = explode('/', $value);
            $folder = array_diff_assoc($folder, explode('/', JComponentHelper::getParams('com_media')->get('image_path', 'images')));
            array_pop($folder);
            $folder = implode('/', $folder);
        }
        elseif (file_exists(JPATH_ROOT . '/' . JComponentHelper::getParams('com_media')->get('image_path', 'images') . '/' . $directory))
        {
            $folder = $directory;
        }
        else
        {
            $folder = '';
        }

        // The button.
        if ($disabled != true)
        {
            JHtml::_('bootstrap.tooltip');

            $html[] = '<span class="jvc_input-group-btn"><a class="jscontent_modal jvc_btn jvc_btn-default" title="' . JText::_('JLIB_FORM_BUTTON_SELECT') . '" href="'
                . (isset($attribs['readonly']) && $attribs['readonly'] ? ''
                    : ($link ? $link
                        : 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=' . $asset . '&amp;author=') . '&amp;fieldid=' . $id . '&amp;folder=' . $folder) . '"'
                . ' rel="{handler: \'iframe\', size: {x: 800, y: 500},classWindow:\'tz_sbox-window\'}">';
            $html[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a><a class="jvc_btn jvc_btn-default hasTooltip" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '" href="#" onclick="';
            $html[] = 'jInsertFieldValue(\'\', \'' . $id . '\');';
            $html[] = 'return false;';
            $html[] = '">';
            $html[] = '<i class="icon-remove"></i></a></span>';
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }
}