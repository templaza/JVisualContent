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

jimport('joomla.application.component.modeladmin');

class JVisualContentModelExtraField extends JModelAdmin{

    public function getTable($type = 'ExtraFields', $prefix = 'JVisualContentTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = NULL){
        if($item = parent::getItem($pk)){
            if(property_exists($item,'option_value')){
                if(!empty($item -> option_value)){
                    if(property_exists($item,'type')){
                        $type               = $item -> type;
                        $_array             = array();
                        $_array['name']     = $item -> type;

                        $registry2  = new JRegistry;
                        $registry2 -> loadString($item -> option_value);
                        $_array['option']   = $registry2 -> toArray();
                        $item -> type   = array();
                        $item -> type   = $_array;

                    }
                }
            }
            return $item;
        }
        return false;
    }

    public function getForm($data = array(), $loadData = true){
        $form = $this->loadForm('com_jvisualcontent.extrafield', 'extrafield', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function save($data){

        $dispatcher = JEventDispatcher::getInstance();
        $table = $this->getTable();

        $key = $table->getKeyName();
        $pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
        $isNew = true;

        // Include the content plugins for the on save events.
        JPluginHelper::importPlugin('content');

        // Allow an exception to be thrown.
        try
        {
            // Load the row if saving an existing record.
            if ($pk > 0)
            {
                $table->load($pk);
                $isNew = false;
            }

            // Bind the data.
            if (!$table->bind($data))
            {
                $this->setError($table->getError());

                return false;
            }

            // Prepare the row for saving
            $this->_prepareTable($table);

            // Check the data.
            if (!$table->check())
            {
                $this->setError($table->getError());
                return false;
            }


            // Alter the title & alias
//            if(!$table -> id){
//                $_data = $this->generateNewTitle($table -> id, $table -> name, $table -> title);
//            var_dump($_data); die();
//                $table->title = $_data['0'];
//                $table->name = $_data['1'];
//            }

            // Trigger the onContentBeforeSave event.
            $result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, $table, $isNew));

            if (in_array(false, $result, true))
            {
                $this->setError($table->getError());
                return false;
            }

            // Store the data.
            if (!$table->store())
            {
                $this->setError($table->getError());
                return false;
            }

            // Clean the cache.
            $this->cleanCache();

            // Trigger the onContentAfterSave event.
            $dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, $table, $isNew));
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());

            return false;
        }

        $pkName = $table->getKeyName();

        if (isset($table->$pkName))
        {
            $this->setState($this->getName() . '.id', $table->$pkName);
        }
        $this->setState($this->getName() . '.new', $isNew);

        return true;
    }

    protected function _prepareTable(&$table){
        if($table){
            if(isset($table -> type) && is_array($table -> type)){
                $types          = $table -> type;
                $table -> type  = $types['name'];

                $registry       = new JRegistry();
                $registry -> loadArray($types['option']);
                $table -> option_value  = $registry -> toString();
            }
            if(isset($table ->protected) && $table ->protected){
                $table -> published = 1;
            }
        }
        return $table;
    }


    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_jvisualcontent.edit.extrafield.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_jvisualcontent.extrafield', $data);

        return $data;
    }

    protected function generateNewTitle($extrafield_id, $name, $title)
    {
        // Alter the title
        $table  = $this->getTable();

        $db     = $this -> getDbo();
        $query  = $db -> getQuery(true);

        $query -> select('COUNT(*)');
        $query -> from($db -> quoteName('#__tz_jvisualcontent_extrafields'));
        $query -> where('name ='.$db -> quote($name));
        $query -> where('NOT id ='.$extrafield_id);

        $db -> setQuery($query);
        if($db -> loadResult()){
            while ($table->load(array('name' => $name,'title' => $title)))
            {
                $title = JString::increment($title);
                $name = JString::increment($name, 'dash');
            }
        }

        return array($title, $name);
    }
}