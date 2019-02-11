<?php
/**
 * @name          : User Article.
 * @version       : 1.0
 * @package       : apptha
 * @since         : Joomla 1.7
 * @subpackage    : User Article.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @abstract      : Users the ability to create, edit ,delete and publish Article.
 * @Creation Date : August 17 2012
 * @Modified Date : August 23 2012
 **/


//No direct access
defined('_JEXEC') or die('Restricted access');
error_reporting(0);
// Imports

jimport('joomla.installer.installer');


////count checked cid

$count = count( JRequest::getVar('cid', 'post', 'array'));


$db = JFactory::getDBO();

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'module' AND element = 'mod_userarticle' LIMIT 1");

$id = $db->loadResult();

////////////check module uninstall or not

if($count==1)
{

if ($id) {

    $installer = new JInstaller();
    $installer->uninstall('module', $id);
}


}

?>

