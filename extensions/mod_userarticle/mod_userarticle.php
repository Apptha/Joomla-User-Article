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

// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';

$helper=new modUserarticleHelper();

$result	= $helper->getLink();

require JModuleHelper::getLayoutPath('mod_userarticle', $params->get('layout', 'default'));   ///get layout

?>