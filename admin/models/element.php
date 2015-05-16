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

class JVisualContentModelElement extends JModelAdmin{

    protected function populateState()
    {
        parent::populateState();

        $app    = JFactory::getApplication();
        $input  = $app -> input;

        $this -> setState($this -> getName().'.element_type',$input -> get('element_type'));
        $this -> setState($this -> getName().'.element_source',$input -> get('element_source'));
        $this -> setState($this -> getName().'.id',$input -> getInt('id'));

        $exData     = $input -> get('extrafield_data',null,'post');
        if($input -> get('element_id',null,'post')){
            $this -> setState($this -> getName().'.id',$input -> get('element_id',null,'post'));
        }
        if($exData){
            if($input -> get('element_type',null,'post')){
                $this -> setState($this -> getName().'.element_type',$input -> get('element_type',null,'post'));
            }
        }
        $this -> setState($this -> getName().'.params',$exData);
    }

    public function getTable($type = 'Elements', $prefix = 'JVisualContentTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true){
        $form = $this->loadForm('com_jvisualcontent.element', 'element', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_jvisualcontent.edit.element.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_jvisualcontent.element', $data);

        return $data;
    }

    public function getItem($pk = null){

        if($item = parent::getItem($pk)){

            $model  = JModelList::getInstance('ExtraFields','JVisualContentModel',array('ignore_request' => true));
            $model -> setState('filter.published',1);
            $type_extrafields   = null;
            if($type_extrafields = $this -> getState($this -> getName().'.params')){
                $type_extrafields   = array_keys($type_extrafields);
                $type_extrafields   = array_unique($type_extrafields);
            }
            if($type_extrafields){
                $model -> setState('filter.name',$type_extrafields);
            }

            $item -> fields = null;

            if($fields = $model -> getData()){
                $item -> fields = $fields;
//
                if($this -> getState($this -> getName().'.params')){
//
//                    $html           = $item -> html;
                    $extrafields    = $this -> getState($this -> getName().'.params');
//
                    foreach($fields as $i => $field){
//                        if($field -> published == 0){
//                            unset($fields[$i]);
//                        }
//
                        $field -> value = null;
                        $name   = $field -> name;
                        if(isset($extrafields[$name])){
                            $field -> value     = stripslashes($extrafields[$name]);
                        }
//
//                        if(!$this -> getState($this -> getName().'.element_type')
//                            || ($this -> getState($this -> getName().'.element_type')
//                                && $this -> getState($this -> getName().'.element_type') == 'tz_element')){
//                            if(preg_match('/\[loop\].*?\[\/loop\]/msi',$html)){
//                                $_html  = preg_replace('/(.*?)(\[loop\].*?\[\/loop\])(.*?)/msi','$2',$html);
//                                if(!preg_match('/\[loop\].*?\{'.$field -> name.'\}.*?\[\/loop\]/msi',$_html)){
//                                    unset($fields[$i]);
//                                }
//                            }
//                        }elseif($this -> getState($this -> getName().'.element_type')
//                            && $this -> getState($this -> getName().'.element_type') != 'tz_element'){
//                            if(preg_match_all('/(.*?)\[loop\].*?\[\/loop\](.*?)/msi',$html)){
//                                $_html  = preg_replace('/(\[loop\].*?\[\/loop\])/msi','',$html);
//                                if(!preg_match_all('/(\{'.$field -> name.'\})/msi',$_html)){
//                                    unset($fields[$i]);
//                                }
//                            }
//                        }
                    }
                    if($fields != $item -> fields){
                        $fields = array_reverse($fields);
                        $item -> fields = array_reverse($fields);
                    }
                }
            }

            return $item;

        }
        return false;
    }

    public function insertDataEditor(){
//        $doc    = JFactory::
        var_dump(JRequest::getInt('id'),$this -> getItem(JRequest::getInt('id'))); die();
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
//            $this->_prepareTable($table);

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
}