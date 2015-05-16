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

jimport('joomla.application.component.view');

class JVisualContentViewElements extends JViewLegacy{

    protected $items        = null;
    protected $state        = null;
    protected $sidebar      = null;
    protected $pagination   = null;

    public function display($tpl=null){

        // Assign data for state variable
        $this -> state      = $this -> get('State');
        // Assign data items
        $this -> items      = $this -> get('Items');
        // Assign data pagination
        $this -> pagination = $this -> get('Pagination');

        JVisualContentHelper::addSubmenu('elements');

        // Call function addtoolbar
        $this -> addToolbar();

        // Call submenu
        if(version_compare(JVERSION,'3.0','>=')){
            $this->sidebar = JHtmlSidebar::render();
        }

        $doc    = JFactory::getDocument();
        $doc -> addStyleSheet(JVisualContentUri::root(true,null,true).'/css/jvisualcontent-admin.min.css');

        parent::display($tpl);
    }

    // This is function to add toolbar
    protected function addToolbar(){
        $canDo	= JHelperContent::getActions('com_jvisualcontent');
        $user	= JFactory::getUser();

        JToolbarHelper::title(JText::_('COM_JVISUALCONTENT_ELEMENTS_MANAGER'));

        // Create new button
        if($canDo -> get('core.create')){
            JToolbarHelper::addNew('element.add');
            JToolbarHelper::divider();
        }

        // Create edit button
        if($canDo -> get('core.edit') || $canDo -> get('core.edit.own')){
            JToolbarHelper::editList('element.edit');
        }

        // Create publish, unpublish buttons
        if($canDo -> get('core.edit.state')){
            JToolbarHelper::publish('elements.publish');
            JToolbarHelper::unpublish('elements.unpublish');
            JToolbarHelper::divider();
        }

        if ($this->state->get('filter.published') == -2 && $user->authorise('core.delete'))
        {
            JToolbarHelper::deleteList('', 'elements.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('elements.trash');
        }

        // Create options button
        if ($canDo -> get('core.admin'))
        {
            JToolbarHelper::preferences('com_jvisualcontent');
        }

        if(version_compare(JVERSION,'3.0','>=')){
            JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_PUBLISHED'),
                'filter_published',
                JHtml::_('select.options', JHtml::_('jgrid.publishedOptions',array('archived' => false)), 'value', 'text', $this->state->get('filter.published'), true)
            );
        }
    }

    protected function getSortFields()
    {
        return array(
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.published' => JText::_('JSTATUS'),
            'a.title' => JText::_('JGLOBAL_TITLE'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }
}