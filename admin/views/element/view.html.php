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

JHtml::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jvisualcontent/helpers/html');

class JVisualContentViewElement extends JViewLegacy{

    protected $state                = null;
    protected $item                 = null;
    protected $form                 = null;
    protected $listFields           = null;
    protected $element              = null;
    protected $elementItem          = null;
    protected $editor               = null;

    function display($tpl=null){

        $this -> editor = JFactory::getEditor();

        $app    = JFactory::getApplication('admin');
        $doc    = JFactory::getDocument();

        $doc -> addStyleSheet(JVisualContentUri::root(true,'',true).'/css/jvisualcontent-admin.min.css');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,'',true).'/js/jquery-ui.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,'',true).'/js/jquery-ui-conflict.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,'',true).'/js/jvisualcontent-bootstrap3-conflict.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,null,true).'/js/jvisualcontent-bootstrap.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,'',true).'/js/spectrum.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,'',true).'/js/libs.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JVisualContentUri::root(true,'',true).'/js/general-tree.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript" src="'.JUri::root(true).'/administrator/components/com_jvisualcontent/js/tz-shortcode.min.js"></script>');
        $doc -> addCustomTag('<script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $("#type-form").tzTypeChooseExtraField({
//                    dragConnectToSortable   : "#tz-shortcode-type",
                    deleteText              : "'.JText::_('COM_JVISUALCONTENT_DELETE_ROW_QUESTION').'"
                });
                $("[data-toggle~=\'jvc_tooltip\'],[data-toggle~=\'tooltip\'],.hasTooltip")
                .jvc_tooltip({"html": true,"container": "body"});
            });
        })(jQuery);
            </script>
        ');
        $doc -> addCustomTag('<script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(\'.edit_form_elements input[data-extrafield-type=color]\').spectrum({
                flat:false,
                color: \'rgba(0,0,0,0)\',
                showInput:true,
                allowEmpty:true,
                preferredFormat: "rgb",
                showButtons:true,
                showAlpha:true,
                showPalette:true,
                clickoutFiresChange:true,
                cancelText:"cancel",
                chooseText:"Choose",
                showPaletteOnly: true,
                togglePaletteOnly: true,
                togglePaletteMoreText: \'more\',
                togglePaletteLessText: \'less\',
                appendTo: "#tz_properties-panel",
                palette : [
                    ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                    ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                    ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                    ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                    ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                    ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                    ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                    ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
                ],
                show: function(color) {
                    if(color && color._a == 1){
                        color.toHex();
                    }
                },
                change: function(color){
                    var currentcolor = \'\';
                    if(color){
                        currentcolor = color.toRgbString();
                        if(currentcolor == \'rgba(0, 0, 0, 0)\'){
                            currentcolor    = \'\';
                        }
                    }
                    jQuery(this).parent().find(\'>[type=hidden][data-name=\'+jQuery(this).attr(\'data-extrafield-name\')+\']\').val(currentcolor);
                }
            });
        });
        </script>');

        // Assign state
        $state          = $this -> get('State');
        $this -> state  = $state;

        // Assign data item
        $this -> item   = $this -> get('Item');

        // Assign form field
        $this -> form   = $this -> get('Form');

        // Assign Extra fields
        $model  = JModelList::getInstance('ExtraFields','JVisualContentModel',array('ignore_request' => true));
        if($fields  = $model -> getItems()){
            $this -> listFields = $fields;
        }

        // Call function addtoolbar
        if ($this->getLayout() !== 'form')
        {
            $this -> addToolbar();
        }

        parent::display($tpl);
    }

    // This is function to add toolbar
    protected function addToolbar(){
        JRequest::setVar('hidemainmenu',true);

        $doc    = JFactory::getDocument();
        $bar    = JToolBar::getInstance();
        $user	= JFactory::getUser();
        $canDo  = JHelperContent::getActions('com_jvisualcontent');

        $isNew  = ($this -> item -> id == 0);

        JToolBarHelper::title(JText::sprintf('COM_JVISUALCONTENT_ELEMENT_MANAGER_TASK',
            JText::_('COM_JVISUALCONTENT_PAGE_'.(($isNew)?'ADD_ELEMENT':'EDIT_ELEMENT'))));
        JToolBarHelper::apply('element.apply');
        JToolBarHelper::save('element.save');
        JToolBarHelper::save2new('element.save2new');

        // If checked out, we can still save
        if (!$isNew && ($user->authorise('core.edit.state', 'com_jvisualcontent') || $canDo->get('core.edit.state'))) {
            JToolBarHelper::save2copy('element.save2copy');
        }

        JToolBarHelper::cancel('element.cancel',JText::_('JTOOLBAR_CLOSE'));

        JToolBarHelper::divider();
    }
}