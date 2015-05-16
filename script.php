<?php
/*------------------------------------------------------------------------

# TZ Shortcode Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.installer.installer');
//jimport('joomla.base.adapterinstance');

class com_jvisualcontentInstallerScript{

    protected $status;

    function postflight($type, $parent){

        $db     = JFactory::getDbo();

        $manifest   = $parent -> get('manifest');
        $params     = new JRegistry();

        $query  = 'SELECT params FROM #__extensions'
            .' WHERE `type`="component" AND `name`="'.strtolower($manifest -> name).'"';
        $db -> setQuery($query);
        $db -> query();

        $paramNames = array();

        if($db -> loadResult()){
            $params -> loadString($db ->loadResult());
            if(count($params -> toArray())>0){
                foreach($params -> toArray() as $key => $val){
                    $paramNames[]   = $key;
                }
            }
        }

        $src = $parent->getParent()->getPath('source');

        $fields     = $manifest -> xPath('config/fields/field');

        foreach($fields as $field){
            $attribute  = $field -> attributes();
            if(!in_array((string)$attribute -> name,$paramNames)){
                if($attribute -> multiple == 'true'){
                    $arr   = null;
                    foreach($field -> option as $option){
                        $opAttr = $option -> attributes();
                        $arr[]  = (string)$opAttr -> value;
                    }

                    $params -> set((string) $attribute -> name,$arr);
                }
                else
                    $params -> set((string)$attribute -> name,(string)$attribute ->default);
            }
        }

        $params = $params -> toString();

        $query  = 'UPDATE #__extensions SET `params`=\''.$params.'\''
            .' WHERE `name`="'.strtolower($manifest -> name).'"'
            .' AND `type`="component"';

        $db -> setQuery($query);
        $db -> query();

        //Install plugins
        $status             = new stdClass;
        $status->modules    = array();
        $status->plugins    = array();
        $plugin_names       = array();
        $lang               = JFactory::getLanguage();

        $plugins = $parent->getParent()->manifest->xpath('plugins/plugin');
        foreach($plugins as $plugin){
            $result         = null;
            $folder         = null;
            $pname          = $plugin->attributes() -> plugin;
            $group          = $plugin->attributes() -> group;
            $folder         = $plugin -> attributes() -> folder;
            $plugin_names[] = $pname;
            if(isset($folder)){
                $folder = $plugin -> attributes() -> folder;
            }
            $path               = $src.'/'.'plugins'.'/'.$pname;

            $installer          = new JInstaller();
            $result             = $installer->install($path);
            $status->plugins[]  = array('name'=>$pname,'group'=>$group, 'result'=>$result);
        }

        if(count($plugin_names)){
            $query  = 'UPDATE #__extensions SET `enabled`=1 WHERE `type`="plugin" AND `element`="jvisualcontent" AND `folder`="editors-xtd"';
            $db -> setQuery($query);
            $db -> query();
            $query  = 'UPDATE #__extensions SET `enabled`=1 WHERE `type`="plugin" AND `element`="jvisualcontent" AND `folder`="system"';
            $db -> setQuery($query);
            $db -> query();
        }

        $this -> installationResult($status);
    }
    function install($parent){

        $db     = JFactory::getDbo();

        $src = $parent->getParent()->getPath('source');

        //Install plugins
        $status             = new stdClass;
        $status->modules    = array();
        $status->plugins    = array();
        $plugin_names       = array();

        $plugins = $parent->getParent()->manifest->xpath('plugins/plugin');
        foreach($plugins as $plugin){
            $result         = null;
            $folder         = null;
            $pname          = $plugin->attributes() -> plugin;
            $group          = $plugin->attributes() -> group;
            $folder         = $plugin -> attributes() -> folder;
            $plugin_names[] = $pname;
            if(isset($folder)){
                $folder = $plugin -> attributes() -> folder;
            }
            $path               = $src.'/'.'plugins'.'/'.$pname;

            $installer          = new JInstaller();
            $result             = $installer->install($path);
            $status->plugins[]  = array('name'=>$pname,'group'=>$group, 'result'=>$result);

        }

        if(count($plugin_names)){
            $query  = 'UPDATE #__extensions SET `enabled`=1 WHERE `type`="plugin" AND `element`="jvisualcontent" AND `folder`="editors-xtd"';
            $db -> setQuery($query);
            $db -> query();
            $query  = 'UPDATE #__extensions SET `enabled`=1 WHERE `type`="plugin" AND `element`="jvisualcontent" AND `folder`="system"';
            $db -> setQuery($query);
            $db -> query();
        }
        $this -> status = $status;
    }
    function uninstall($parent){
        $_parent    = $parent -> getParent();
        $plugins = $_parent -> manifest -> xpath('plugins/plugin');

        //Install plugins
        $status             = new stdClass;
        $status->plugins    = array();
        $status->modules    = array();

        if($plugins){
            foreach ($plugins as $plugin) {
                $result         = null;

                $pname = (string)$plugin->attributes() -> plugin;
                $pgroup = (string)$plugin->attributes() -> group;

                $db     = JFactory::getDbo();
                $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND `name` = "
                    .$db->quote($pname)." AND `folder` = ".$db->quote($pgroup);
                $db->setQuery($query);
                $IDs = $db->loadColumn();
                if (count($IDs)) {
                    foreach ($IDs as $id) {
                        $installer = new JInstaller;
                        $result = $installer->uninstall('plugin', $id,1);
                    }
                }
                $status->plugins[] = array ('name'=>$pname, 'group'=>$pgroup, 'result'=>$result);
            }
        }
        $this -> uninstallationResult($status);
    }

    public function installationResult($status){
        $lang   = JFactory::getLanguage();
        $lang -> load('com_jvisualcontent');
        $rows   = 0;
        ?>
        <h2><?php echo JText::_('COM_JVISUALCONTENT'); ?></h2>
        <span style="font-weight: normal"><?php echo JText::_('COM_JVISUALCONTENT_DESCRIPTION');?></span>
        <table style="margin-top: 15px;">
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_LIKE_FB');?>: </td>
                <td>
                    <div id="fb-root"></div>
                    <script type="text/javascript">
                        (function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) {return;}
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/en_US/all.js#appId=177111755694317&xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                    </script>
                    <div class="fb-like" data-send="false" data-width="200" data-show-faces="true"
                         data-layout="button_count" data-href="http://www.templaza.com"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_FOLLOW_TWITTER');?>: </td>
                <td>
                    <!-- Twitter Button -->
                    <a href="http://www.templaza.com/" class="twitter-share-button"
                       data-count="horizontal" data-size="small"></a>
                    <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>

                    <a href="https://twitter.com/templazavn" class="twitter-follow-button" data-show-count="false">Follow @Templaza</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_GOOGLE_PLUS_LIKE');?>: </td>
                <td>
                    <!-- Google +1 Button -->
                    <!-- Place this tag where you want the +1 button to render. -->
                    <div class="g-plusone" data-size="medium" data-href="http://www.templaza.com/"></div>

                    <!-- Place this tag after the last +1 button tag. -->
                    <script type="text/javascript">
                        (function() {
                            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                            po.src = 'https://apis.google.com/js/plusone.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                        })();
                    </script>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_PINTEREST');?>: </td>
                <td>
                    <!-- Pinterest Button -->
                    <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode('http://www.templaza.com/');?>"
                       data-pin-do="buttonPin" data-pin-config="beside">
                        <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="" />
                    </a>
                    <script type="text/javascript">
                        (function(d){
                            var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
                            p.type = 'text/javascript';
                            p.async = true;
                            p.src = '//assets.pinterest.com/js/pinit.js';
                            f.parentNode.insertBefore(p, f);
                        }(document));
                    </script>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_LINKEDIN');?>: </td>
                <td>
                    <!-- Linkedin Button -->
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                    <script type="IN/Share" data-url="http://www.templaza.com/" data-counter="right"></script>
                </td>
            </tr>
        </table>
        <h3 style="margin-top: 20px;"><?php echo JText::_('COM_JVISUALCONTENT_INSTALLATION_STATUS'); ?></h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="title" colspan="2"><?php echo JText::_('COM_JVISUALCONTENT_EXTENSION'); ?></th>
                <th width="30%"><?php echo JText::_('COM_JVISUALCONTENT_STATUS'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="3"></td>
            </tr>
            </tfoot>
            <tbody>
            <tr class="row0">
                <td class="key" colspan="2"><?php echo JText::_('COM_JVISUALCONTENT').' '.JText::_('COM_JVISUALCONTENT_COMPONENT'); ?></td>
                <td><span style="color: green; font-weight: bold;"><?php echo JText::_('COM_JVISUALCONTENT_INSTALLED'); ?></span></td>
            </tr>
            <?php if (count($status->modules)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_MODULE'); ?></th>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_CLIENT'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($status->modules as $module): ?>
                    <?php
                    if($lang -> exists($module['name'])):
                        $lang -> load($module['name']);
                    endif;
                    ?>
                    <tr class="row<?php echo (++ $rows % 2); ?>">
                        <td class="key"><?php echo JText::_($module['name']); ?></td>
                        <td class="key"><?php echo ucfirst($module['client']); ?></td>
                        <td><span style="color: green; font-weight: bold;"><?php echo ($module['result'])?JText::_('COM_JVISUALCONTENT_INSTALLED'):JText::_('COM_JVISUALCONTENT_NOT_INSTALLED'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (count($status->plugins)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_PLUGIN'); ?></th>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_GROUP'); ?></th>
                    <th></th>
                </tr>
                <?php
                foreach ($status->plugins as $plugin):
                ?>
                    <?php
                    if(JFile::exists($lang -> getLanguagePath().'/'.$lang -> getTag().'/'
                        .$lang -> getTag().'.'.$plugin['name'].'.sys.ini')) {
                        $lang->load($plugin['name'].'.sys',JPATH_BASE,null,true);
                    }
                    ?>
                    <tr class="row<?php echo (++ $rows % 2); ?>">
                        <td class="key"><?php echo JText::_(mb_strtoupper($plugin['name'])); ?></td>
                        <td class="key"><?php echo $plugin['group']; ?></td>
                        <td><span style="color: green; font-weight: bold;"><?php echo ($plugin['result'])?JText::_('COM_JVISUALCONTENT_INSTALLED'):JText::_('COM_JVISUALCONTENT_NOT_INSTALLED'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($status -> languages) AND count($status->languages)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_LANGUAGES'); ?></th>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_COUNTRY'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($status->languages as $language): ?>
                    <tr class="row<?php echo (++ $rows % 2); ?>">
                        <td class="key"><?php echo ucfirst($language['language']); ?></td>
                        <td class="key"><?php echo ucfirst($language['country']); ?></td>
                        <td><span style="color: red; font-weight: bold;"><?php echo ($language['result'])?JText::_('COM_JVISUALCONTENT_INSTALLED'):JText::_('COM_JVISUALCONTENT_NOT_INSTALLED'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>
    <?php
    }
    function uninstallationResult($status){
        $lang   = JFactory::getLanguage();
        $lang -> load('com_jvisualcontent');
        $rows   = 0;
        ?>
        <h2><?php echo JText::_('COM_JVISUALCONTENT'); ?></h2>
        <span style="font-weight: normal"><?php echo JText::_('COM_JVISUALCONTENT_DESCRIPTION');?></span>
        <table style="margin-top: 15px;">
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_LIKE_FB');?>: </td>
                <td>
                    <div id="fb-root"></div>
                    <script type="text/javascript">
                        (function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) {return;}
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/en_US/all.js#appId=177111755694317&xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                    </script>
                    <div class="fb-like" data-send="false" data-width="200" data-show-faces="true"
                         data-layout="button_count" data-href="http://www.templaza.com"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_FOLLOW_TWITTER');?>: </td>
                <td>
                    <!-- Twitter Button -->
                    <a href="http://www.templaza.com/" class="twitter-share-button"
                       data-count="horizontal" data-size="small"></a>
                    <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>

                    <a href="https://twitter.com/templazavn" class="twitter-follow-button" data-show-count="false">Follow @Templaza</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_GOOGLE_PLUS_LIKE');?>: </td>
                <td>
                    <!-- Google +1 Button -->
                    <!-- Place this tag where you want the +1 button to render. -->
                    <div class="g-plusone" data-size="medium" data-href="http://www.templaza.com/"></div>

                    <!-- Place this tag after the last +1 button tag. -->
                    <script type="text/javascript">
                        (function() {
                            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                            po.src = 'https://apis.google.com/js/plusone.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                        })();
                    </script>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_PINTEREST');?>: </td>
                <td>
                    <!-- Pinterest Button -->
                    <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode('http://www.templaza.com/');?>"
                       data-pin-do="buttonPin" data-pin-config="beside">
                        <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="" />
                    </a>
                    <script type="text/javascript">
                        (function(d){
                            var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
                            p.type = 'text/javascript';
                            p.async = true;
                            p.src = '//assets.pinterest.com/js/pinit.js';
                            f.parentNode.insertBefore(p, f);
                        }(document));
                    </script>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JVISUALCONTENT_LINKEDIN');?>: </td>
                <td>
                    <!-- Linkedin Button -->
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                    <script type="IN/Share" data-url="http://www.templaza.com/" data-counter="right"></script>
                </td>
            </tr>
        </table>
        <h3 style="margin-top: 20px;"><?php echo JText::_('COM_JVISUALCONTENT_REMOVE_STATUS'); ?></h3>
        <table class="table table-striped" style="margin-top: 20px;">
            <thead>
            <tr>
                <th class="title" colspan="2"><?php echo JText::_('COM_JVISUALCONTENT_EXTENSION'); ?></th>
                <th width="30%"><?php echo JText::_('COM_JVISUALCONTENT_STATUS'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="3"></td>
            </tr>
            </tfoot>
            <tbody>
            <tr class="row0">
                <td class="key" colspan="2"><?php echo JText::_('COM_JVISUALCONTENT').' '.JText::_('COM_JVISUALCONTENT_COMPONENT'); ?></td>
                <td><strong><?php echo JText::_('COM_JVISUALCONTENT_REMOVED'); ?></strong></td>
            </tr>
            <?php if (count($status->modules)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_MODULE'); ?></th>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_CLIENT'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($status->modules as $module): ?>
                    <?php
                    if($lang -> exists($module['name'])):
                        $lang -> load($module['name']);
                    endif;
                    ?>
                    <tr class="row<?php echo (++ $rows % 2); ?>">
                        <td class="key"><?php echo JText::_($module['name']); ?></td>
                        <td class="key"><?php echo ucfirst($module['client']); ?></td>
                        <td><span style="color: red; font-weight: bold;"><?php echo ($module['result'])?JText::_('COM_JVISUALCONTENT_REMOVED'):JText::_('COM_JVISUALCONTENT_NOT_REMOVED'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (count($status->plugins)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_PLUGIN'); ?></th>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_GROUP'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($status->plugins as $plugin): ?>
                    <?php
                    if($lang -> exists($plugin['name'].'.sys')):
                        $lang -> load($plugin['name'].'.sys');
                    endif;
                    ?>
                    <tr class="row<?php echo (++ $rows % 2); ?>">
                        <td class="key"><?php echo JText::_(ucfirst($plugin['name'])); ?></td>
                        <td class="key"><?php echo $plugin['group']; ?></td>
                        <td><span style="color: green; font-weight: bold;"><?php echo ($plugin['result'])?JText::_('COM_JVISUALCONTENT_REMOVED'):JText::_('COM_JVISUALCONTENT_NOT_REMOVED'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($status -> languages) AND count($status->languages)): ?>
                <tr>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_LANGUAGES'); ?></th>
                    <th><?php echo JText::_('COM_JVISUALCONTENT_COUNTRY'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($status->languages as $language): ?>
                    <tr class="row<?php echo (++ $rows % 2); ?>">
                        <td class="key"><?php echo ucfirst($language['language']); ?></td>
                        <td class="key"><?php echo ucfirst($language['country']); ?></td>
                        <td><strong><?php echo ($language['result'])?JText::_('COM_JVISUALCONTENT_REMOVED'):JText::_('COM_JVISUALCONTENT_NOT_REMOVED'); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    <?php
    }
}