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

class modUserarticleHelper
{
    function getLink()
    {
    $db = JFactory::getDBO();
    $query="SELECT id FROM  #__menu
        WHERE menutype <>'main'
        AND link='index.php?option=com_userarticle&view=article'
        AND type='component'
        AND published=1
        LIMIT 1 ";
 $db->setQuery($query);
 $result=$db->loadResult();
 return $result;
    }
}
