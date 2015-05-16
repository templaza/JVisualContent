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

class JVisualContentModelExtraFields extends JModelList{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'name', 'a.name',
                'published', 'a.published',
                'type', 'a.type',
                'ordering', 'a.ordering'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
    */
    protected function populateState($ordering = null, $direction = null)
    {

        $this -> setState('extrafields.id',null);

//        $this -> setState('filter.ordering',true);

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $published = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '');
        $this->setState('filter.type', $published);

        $this->setState('filter.name', null);

        parent::populateState($ordering,$direction);
        $this->setState('extrafields.name', null);


    }

    // Method to get a JDatabaseQuery object for retrieving the data set from a database.
    protected function getListQuery(){
        $db     = $this -> getDbo();
        $query  = $db -> getQuery(true);

        $query -> select('a.*');
        $query -> from($db -> quoteName('#__tz_jvisualcontent_extrafields').' AS a');

        if($fieldsId = $this -> getState('extrafields.id')){
            if(is_array($fieldsId)){
                $query -> where('a.id IN('.implode(',',$fieldsId).')');
            }elseif(is_numeric($fieldsId)){
                $query -> where('a.id ='.$fieldsId);
            }else{
                $query -> where('a.id IN('.$fieldsId.')');

                $array  = explode(',',$fieldsId);
                if(count($array)){
                    $array  = array_reverse($array);
                    $query -> order('(a.id='.implode('),(a.id =',$array).')');
                }
            }
        }

        if($name = $this -> getState('filter.name')){
            if(is_array($name) && count($name)) {
                $f_name = array();
                foreach($name as $n) {
                    $f_name[] = 'a.name='.$db -> quote($n);
                }
                if(count($f_name)) {
                    $f_name = implode(' OR ',$f_name);
                    $query->where(trim($f_name));
                }
            }else{
                $query->where('a.name=' . $db->quote($name));
            }
        }

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


        // Add the list ordering clause.
        $orderCol = $this->getState('list.ordering','a.id');
        $orderDirn = $this->getState('list.direction','desc');
        if ($orderCol == 'a.ordering')
        {
            $orderCol = 'a.title ' . $orderDirn . ', a.ordering';
        }

        if(!empty($orderCol) && !empty($orderDirn)){
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems(){
        $items = parent::getItems();
        if($items && count($items)){
            foreach($items as $item){
                $item -> options    = array();
                if(!empty($item -> option_value)){
                    $registry   = new JRegistry;
                    $registry -> loadString($item -> option_value);
                    $item -> options    = $registry -> toArray();
                }
            }
        }
        return $items;
    }

    public function getData(){
//        if($this -> getState('extrafields.id')){
            return $this -> getItems();
//        }
//        return false;
    }

}