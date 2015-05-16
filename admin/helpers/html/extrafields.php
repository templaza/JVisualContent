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

abstract class JHtmlExtraFields
{
    // Returns a published state on a grid
    public static function state($value, $i, $enabled = true, $checkbox = 'cb')
    {
        $states = array(1 => array('unpublish', 'JPUBLISHED', 'JLIB_HTML_UNPUBLISH_ITEM', 'JPUBLISHED', true, 'publish', 'publish'),
            0 => array('publish', 'JUNPUBLISHED', 'JLIB_HTML_PUBLISH_ITEM', 'JUNPUBLISHED', true, 'unpublish', 'unpublish'),
            2 => array('', 'COM_JVISUALCONTENT_EXTRAFIELD_PROTECTED', '', 'COM_JVISUALCONTENT_EXTRAFIELD_PROTECTED', true, 'protected', 'protected'),
            -2 => array('publish', 'JTRASHED', 'JLIB_HTML_PUBLISH_ITEM', 'JTRASHED', true, 'trash', 'trash'));

        return JHtml::_('jgrid.state', $states, $value, $i, 'extrafields.', $enabled, true, $checkbox);
    }

    // Renders extrafield to form field
    public static function formRender($field,$nameEnable = true,$fieldsDenied = array(),$optionCount = null,$namePrefix = 'jform'){
        $html   = null;
        $id     = null;
        $name   = null;
        $value  = null;

        if($nameEnable){
            $id     = $namePrefix.'_'.$field -> name;
            $name   = ' name="'.$namePrefix.'['.$field -> name.']';
            if(!$namePrefix || ($namePrefix && empty($namePrefix))){
                $id     = 'tz_formfield_'.$field -> name;
                $name   = ' name="'.$field -> name;
            }
            if(($field -> type == 'multiselect') || ($field -> type == 'checkbox')){
                $name   .= '[]';
            }
            $name   .= '"';
        }

        if(count($fieldsDenied)){
            if(in_array($field -> type,$fieldsDenied)){
                return $html;
            }
        }

        ob_start();
        switch($field -> type):
            case 'textfield':
                ?>
                <input type="<?php echo $field -> options['type'];?>"<?php echo $name;?>
                       id="<?php echo $id;?>" value=""/>
                <?php
                break;
            case 'textarea':
                ?>
                <textarea id="<?php echo $id;?>"<?php echo $name;?>></textarea>
                <?php
                break;
            case 'select':
            case 'multiselect':
                ?>
                <select id="<?php echo $id;?>"<?php echo $name;?>
                    <?php echo ($field -> type == 'multiselect')?'multiple="true"':'';?>>
                    <?php
                    if(!empty($field -> options)):
                        foreach($field -> options['title'] as $j => $optTitle):
                            if($optionCount && $j > ($optionCount - 1)){
                                break;
                            }
                            ?>
                            <option value="<?php echo htmlspecialchars($field -> options['value'][$j]);?>"><?php echo $optTitle;?></option>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <?php
                break;
            case 'radio':
            case 'checkbox':
                if(!empty($field -> options)):
                    foreach($field -> options['title'] as $j => $optTitle):
                        if($optionCount && $j > ($optionCount - 1)){
                            break;
                        }
                        ?>
                        <label class="<?php echo $field -> type;?>">
                            <input type="<?php echo $field -> type;?>"<?php echo $name;?> value="<?php echo htmlspecialchars($field -> options['value'][$j]);?>"/>
                            <?php echo $optTitle;?>
                        </label>
                    <?php
                    endforeach;
                endif;
                break;
            case 'file':
                ?>
                <input type="file"<?php echo $name;?> id="<?php echo $id;?>">
                <?php
                break;
            case 'editor':
                $editor = JFactory::getEditor();
                $editor ->initialise();
                echo $editor -> display($field -> name,'','100%','100',10,10,false,$id);
                break;
            case 'calendar':
                echo JHtml::_('calendar','',$name,$id);
                break;
        endswitch;

        $html   = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}