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

// Require model class of joomla
jimport('joomla.application.component.modellist');

class JVisualContentModelElements extends JModelList{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'name', 'a.name',
                'published', 'a.published',
                'ordering', 'a.ordering'
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState('a.title','ASC');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $published = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '');
        $this->setState('filter.type', $published);

        $this->setState('filter.name', null);
        $app    = JFactory::getApplication();
        $input  = $app -> input;
        $this->setState('filter.element_id', $input -> get('element_id',null));

        $this -> setState('filter.types_enable',JRequest::getVar('types',array(),'array'));
    }

    // Method to get a JDatabaseQuery object for retrieving the data set from a database.
    protected function getListQuery(){
        $app    = JFactory::getApplication();
        $input  = $app -> input;
        $layout = $input -> get('layout');
        $db     = $this -> getDbo();
        $query  = $db -> getQuery(true);

        $query -> select($this -> getState('list.select','a.*'));
        $query -> from($db -> quoteName('#__tz_jvisualcontent_elements').' AS a');

        // Filter by published state
        $published  = $this -> getState('filter.published');
        if (is_numeric($published)){
            $query->where('a.published = ' . (int) $published);
        }elseif ($published === ''){
            $query->where('(a.published = 0 OR a.published = 1)');
        }

        // Filter by search in name.
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }else
            {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where('(a.title LIKE ' . $search . ' OR a.name LIKE ' . $search . ')');
            }
        }

        // Filter by type
        if($type   = $this -> getState('filter.type')){
            $query -> where('a.type = '.$db -> quote($type));
        }

        // Filter by name
        if($name   = $this -> getState('filter.name')){
            if(is_string($name)){
                $query -> where('a.name = '.$db -> quote($name));
            }elseif(is_array($name)){
                $query -> where('a.name IN( \''.join('\',\'',$name).'\')');
            }
        }

        if($element_id = $this -> getState('filter.element_id')){
            if($element_id != 'column'){
                $model  = JModelLegacy::getInstance('Element','JVisualContentModel',array('ignore_request' => true));
                $model -> setState('element.id',$element_id);
                if($model -> getItem()) {
                    $query->where('NOT a.html REGEXP ' . $db->quote('\\[loop\\].*\\[type\\]\\[\\/type\\].*\\[\\/loop\\]'));
                }
            }
//            else{
//                $query -> where('NOT a.html REGEXP '.$db -> quote('\\[loop\\].*\\[type\\]\\[\\/type\\].*\\[\\/loop\\]'));
//            }
        }

        // Add the list ordering clause.
        $orderCol = $this->getState('list.ordering', 'a.title');
        $orderDirn = $this->getState('list.direction', 'asc');
        if ($orderCol == 'a.ordering')
        {
            $orderCol = 'a.title ' . $orderDirn . ', a.ordering';
        }

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    protected function _get_system_types(){
        $system_types   = array();

        $item                = new stdClass();
        $item -> id          = 'row';
        $item -> name        = 'tz_row';
        $item -> published   = '1';
        $item -> html        = '<div class="{width}"><div class="jvc_row {css_class} {el_class}" style="text-align: {text_align}">[type][/type]</div></div>';

        $system_types[] = clone($item);

        if($types_enable = $this -> getState('filter.types_enable',array())) {
            if(in_array('readmore',$types_enable)) {
                $item->id = 'readmore';
                $item->name = 'readmore';
                $item->published = '1';
                $item->html = '<hr id="system-readmore" />';

                $system_types[] = clone($item);
            }
        }

        return $system_types;
    }

    public function getItems(){
        $items  = array();

        $row                = new stdClass();
        $row -> id          = 'row';
        $row -> name        = 'tz_row';
        $row -> published   = '1';
        $row -> html        = '<div class="{width}"><div class="jvc_row {css_class} {el_class}" style="text-align: {text_align}">[type][/type]</div></div>';

        $app    = JFactory::getApplication();
        $input  = $app -> input;

        if($_items = parent::getItems()){
            $items  = $_items;
            if($input -> get('layout') == 'modal'){
                if(!$this -> getState('filter.element_id') && !$input -> get('tzaddnew')){
                    return false;
                }
            }

            if($input -> get('layout') == 'modal'){
                if($element_id = $this -> getState('filter.element_id')){
                    if($element_id != 'column'){
                        $model  = JModelLegacy::getInstance('Element','JVisualContentModel',array('ignore_request' => true));
                        $model -> setState('element.id',$element_id);
                        if($item = $model -> getItem()) {
                            if(!preg_match_all('/.*?(\[type\].*?\[\/type\]).*?/msi',$item -> html)
                            && !preg_match_all('/.*?(\[loop\].*?\[type\].*?\[\/type\].*?\[\/loop\]).*?/msi',$item -> html)){
//                                array_unshift($items,$row);
                                $items  = array_merge($this -> _get_system_types(),$items);
                            }
                        }
                    }
                    else{
//                        array_unshift($items,$row);
                        $items  = array_merge($this -> _get_system_types(),$items);
                    }
                }
                else{
//                    array_unshift($items,$row);
                    $items  = array_merge($this -> _get_system_types(),$items);
                }
            }
            return $items;
        }else{
            if($input -> get('layout') == 'modal') {
//                $items  = array_merge($this -> _get_system_types(),$items);
                $items[] = $row;
                return $items;
            }
        }
        return false;
    }

}