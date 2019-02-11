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


// No direct access

defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();

  /* declare css file*/
  $doc= JFactory::getDocument();
  $doc->addStyleSheet('components/com_userarticle/assets/article.css');

      /******* Get Task *****/

        $task=JRequest::getVar('task',null,'GET');
        
        switch($task)
        {

         /***************Included add Article**********************/
        case 'add':
            echo $this->loadTemplate('articleadd');
            break;
        /***************Included edit Article**********************/
        case 'edit':
            echo $this->loadTemplate('articleedit');
            break;
        /***************Included Default Article  views ***********/
        default:
            echo $this->loadTemplate('article');
            break;

        }


?>

