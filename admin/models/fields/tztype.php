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

JFormHelper::loadFieldClass('list');

class JFormFieldTZType extends JFormFieldList
{
    protected $type = 'TZType';

    public function __construct($form = null){
        parent::__construct($form);

        $doc    = JFactory::getDocument();
        $doc -> addStyleSheet(JUri::root().'administrator/components/com_jvisualcontent/css/jvisualcontent-admin.min.css');
        $doc -> addScript(JUri::root().'administrator/components/com_jvisualcontent/js/tztype.min.js');

        // Depends on jQuery UI
        JHtml::_('jquery.ui', array('core', 'sortable'));
    }

    protected function getInput(){
        $doc    = JFactory::getDocument();
        $script = array();
        $html   = array();
        $_html  = null;
        $value  = $this -> value;

        $attr = '';

        // Initialize some field attributes.
        $attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $attr .= $this->multiple ? ' multiple' : '';
        $attr .= $this->required ? ' required aria-required="true"' : '';
        $attr .= $this->autofocus ? ' autofocus' : '';

        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
        {
            $attr .= ' disabled="disabled"';
        }

        // Initialize JavaScript field attributes.
        $attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

        // Get the field options.
        $options = (array) $this->getOptions();

        // Create a read-only list (no name) with a hidden input to store the value.
        if ((string) $this->readonly == '1' || (string) $this->readonly == 'true')
        {
            $html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
            $html[] = '<input type="hidden" name="' . $this->name.'[name]' . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
        }
        else
            // Create a regular list.
        {
            $html[] = JHtml::_('select.genericlist', $options, $this->name.'[name]', trim($attr), 'value', 'text', $this->value, $this->id);
        }


        ob_start();
        ?>
        <div>
            <div id="<?php echo $this -> id;?>_textfield">
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('COM_JVISUALCONTENT_TEXT_TYPE');?>
                    </div>
                    <div class="controls">
                        <?php
                        $textType   = null;
                        if($value && is_array($value) && isset($value['option'])
                            && isset($value['option']['type'])){
                            $textType   = $value['option']['type'];
                        }
                        ?>
                        <select name="<?php echo $this -> name;?>[option][type]">
                            <option value="text"<?php echo ($textType == 'text')?' selected="selected"':'';?>>text</option>
                            <option value="password"<?php echo ($textType == 'password')?' selected="selected"':'';?>>password</option>
                            <option value="datetime"<?php echo ($textType == 'datetime')?' selected="selected"':'';?>>datetime</option>
                            <option value="datetime-local"<?php echo ($textType == 'datetime-local')?' selected="selected"':'';?>>datetime-local</option>
                            <option value="date"<?php echo ($textType == 'date')?' selected="selected"':'';?>>date</option>
                            <option value="month"<?php echo ($textType == 'month')?' selected="selected"':'';?>>month</option>
                            <option value="time"<?php echo ($textType == 'time')?' selected="selected"':'';?>>time</option>
                            <option value="week"<?php echo ($textType == 'week')?' selected="selected"':'';?>>week</option>
                            <option value="number"<?php echo ($textType == 'number')?' selected="selected"':'';?>>number</option>
                            <option value="email"<?php echo ($textType == 'email')?' selected="selected"':'';?>>email</option>
                            <option value="url"<?php echo ($textType == 'url')?' selected="selected"':'';?>>url</option>
                            <option value="search"<?php echo ($textType == 'search')?' selected="selected"':'';?>>search</option>
                            <option value="tel"<?php echo ($textType == 'tel')?' selected="selected"':'';?>>tel</option>
                            <option value="color"<?php echo ($textType == 'color')?' selected="selected"':'';?>>color</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--            <div id="--><?php //echo $this -> id;?><!--_textarea">-->
            <!--                <div class="control-group">-->
            <!--                    <div class="control-label">-->
            <!--                        --><?php //echo JText::_('COM_JVISUALCONTENT_TEXTAREA');?>
            <!--                    </div>-->
            <!--                    <div class="controls">-->
            <!--                        <textarea name="--><?php //echo $this -> name;?><!--[textarea]"></textarea>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <div id="<?php echo $this -> id;?>_select">
                <div class="control-group">
                    <button type="button" class="btn add" id="<?php echo $this -> id;?>_select_add"><i class="icon-plus"></i> <?php echo JText::_('COM_JVISUALCONTENT_ADD');?></button>
                </div>
                <div id="<?php echo $this -> id;?>_select_option" class="tz-ui-sortable">
                    <?php
                    if($value && is_array($value) && isset($value['option'])
                        && isset($value['option']['title']) && isset($value['option']['value'])){
                        if(isset($value['option'])){
                            if( isset($value['option']['value'])){
                                $_value = $value['option']['value'];
                            }

                            if(isset($value['option']['title'])){
                                foreach($value['option']['title'] as $i => $title){
                                    ?>
                                    <div class="tz-control-group ui-state-default">
                                        <div class="tz-toolbar text-right">
                                            <i class="icon-move hasTooltip" title="<?php echo JText::_('COM_JVISUALCONTENT_MOVE');?>"></i>
                                            <i class="icon-remove hasTooltip" title="<?php echo JText::_('JTOOLBAR_REMOVE');?>"></i>
                                        </div>
                                        <div class="control-group">
                                            <div class="control-label">
                                                <?php echo JText::_('COM_JVISUALCONTENT_OPTION_TITLE');?>
                                            </div>
                                            <div class="controls">
                                                <input type="text" name="<?php echo $this -> name;?>[option][title][]"
                                                       value="<?php echo $title;?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div class="control-label">
                                                <?php echo JText::_('COM_JVISUALCONTENT_OPTION_VALUE');?>
                                            </div>
                                            <div class="controls">
                                                <input type="text" name="<?php echo $this -> name;?>[option][value][]"
                                                       value="<?php echo htmlspecialchars($_value[$i]);?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                        }
                    }else{
                        ?>
                        <div class="tz-control-group ui-state-default">
                            <div class="tz-toolbar text-right">
                                <i class="icon-move hasTooltip" title="<?php echo JText::_('COM_JVISUALCONTENT_MOVE');?>"></i>
                                <i class="icon-remove hasTooltip" title="<?php echo JText::_('JTOOLBAR_REMOVE');?>"></i>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo JText::_('COM_JVISUALCONTENT_OPTION_TITLE');?>
                                </div>
                                <div class="controls">
                                    <input type="text" name="<?php echo $this -> name;?>[option][title][]">
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo JText::_('COM_JVISUALCONTENT_OPTION_VALUE');?>
                                </div>
                                <div class="controls">
                                    <input type="text" name="<?php echo $this -> name;?>[option][value][]">
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
            <!--            <div id="--><?php //echo $this -> id;?><!--_editor">-->
            <!--                --><?php
            //                $editor = JFactory::getEditor();
            //                echo $editor -> display($this -> name.'[editor]','','80%', '300', '60', '20',array('pagebreak', 'readmore'),$this -> id);
            //                ?>
            <!--            </div>-->
        </div>
        <?php
        $_html  = ob_get_contents();
        ob_end_clean();

        $html[] = '<div class="control-group">';
//        $html[] = parent::getInput();
        $html[] = '</div>';

        ob_start();
        ?>

        <div id="<?php echo $this -> id;?>_tztype_sample" class="control-group">
        </div>

        <div id="<?php echo $this -> id;?>_tztype">
            <?php if($value):
                echo $_html;
            endif;?>
        </div>
        <?php
        $html[] = ob_get_contents();
        ob_end_clean();

        $doc -> addScriptDeclaration('
        jQuery(document).ready(function(){
            jQuery("#'.$this -> id.'").tzExtraFieldsTypes({
                '.(($value && is_array($value))?'\'value\': \''.$value['name'].'\',':'').'
                addButtonString: \''.$this -> id.'_select_add\',
                sample: false,
                sampleIdPrefix: \'_sample\',
                deleteText: \''.JText::_('COM_JVISUALCONTENT_DELETE_ROW_QUESTION').'\',
                htmlForm: \''.$this -> jsaddslashes($_html).'\',
                htmlSample: \''.$this -> jsaddslashes($this ->HtmlSample()).'\',
                AfterHtmlAppend: function(valSelected){
                    '.(version_compare(JVERSION,'3.0','>=')?'jQuery("select").chosen();':'').'
                }
            });
        });
        ');

        return implode($html);
    }

    protected function HtmlSample(){
        ob_start();
        ?>
        <div>
            <div id="<?php echo $this -> id;?>_textfield_sample">
                <div class="control-label"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE');?></div>
                <div class="controls"><input type="text" value=""/></div>
            </div>
            <div id="<?php echo $this -> id;?>_textarea_sample">
                <div class="control-label"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE');?></div>
                <div class="controls"><textarea></textarea></div>
            </div>
            <div id="<?php echo $this -> id;?>_select_sample">
                <div class="control-label"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE');?></div>
                <div class="controls">
                    <select>
                        <option>Example 1</option>
                        <option>Example 2</option>
                        <option>Example 3</option>
                    </select>
                </div>
            </div>
            <div id="<?php echo $this -> id;?>_multiselect_sample">
                <div class="control-label"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE');?></div>
                <div class="controls">
                    <select multiple="true">
                        <option>Example 1</option>
                        <option>Example 2</option>
                        <option>Example 3</option>
                    </select>
                </div>
            </div>
            <div id="<?php echo $this -> id;?>_radio_sample">
                <div class="control-label"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE');?></div>
                <div class="controls">
                    <label class="radio"><input type="radio" name="test"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE1');?></label>
                    <label class="radio"><input type="radio" name="test"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE2');?></label>
                </div>
            </div>
            <div id="<?php echo $this -> id;?>_checkbox_sample">
                <div class="control-label"><?php echo JText::_('Example');?></div>
                <div class="controls">
                    <label class="checkbox"><input type="checkbox" value=""><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE1');?></label>
                    <label class="checkbox"><input type="checkbox" value=""><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE2');?></label>
                </div>
            </div>
            <!--            <div id="--><?php //echo $this -> id;?><!--_calendar_sample">-->
            <!--                <input type="checkbox" value="Example 1">-->
            <!--                <input type="checkbox" value="Example 2">-->
            <!--            </div>-->
            <div id="<?php echo $this -> id;?>_file_sample">
                <div class="control-label"><?php echo JText::_('COM_JVISUALCONTENT_EXAMPLE');?></div>
                <div class="controls"><input type="file"></div>
            </div>
        </div>
        <?php
        $sample = ob_get_contents();
        ob_end_clean();
        return $sample;
    }

    protected function jsaddslashes($s)
    {
        $o="";
        $l=strlen($s);
        for($i=0;$i<$l;$i++)
        {
            $c=$s[$i];
            switch($c)
            {
                case '<': $o.='\\x3C'; break;
                case '>': $o.='\\x3E'; break;
                case '\'': $o.='\\\''; break;
                case '\\': $o.='\\\\'; break;
                case '"':  $o.='\\"'; break;
                case "\n": $o.='\\n'; break;
                case "\r": $o.='\\r'; break;
                default:
                    $o.=$c;
            }
        }
        return $o;
    }
}
