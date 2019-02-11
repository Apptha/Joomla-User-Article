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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * article Component Controller
 *
*/

class UserArticleController extends JController
{
    /**
     * Method to display the view
     *
     * @access    public
     */


function display($cachable = false, $urlparams = false)
    {

        $user = JFactory::getUser();
        $app =JFactory::getApplication();
        /**set error if not logged in  **/
        if(!$user->id)
        {
        $error=JTEXT::_( 'UA_LOGIN_POST' );

        $login_ur=JROUTE::_( 'index.php?option=com_users&view=login' );

        $app->redirect( $login_ur, $error);
        }
      
       $url=JRoute::_( 'index.php?option=com_userarticle&view=article' ,false ); //get url
       $task=JRequest::getVar('task','','POST');

       if($task=='add')
       {
       $model  = $this->getModel('Article');
       $ret=$model->save_article();
       /*****sucess****/
       if($ret)
           {
           $msg=JTEXT::_( 'UA_ARTICLE_APPROVAL' );
           $app->redirect($url,$msg);

           }
       }

       if($task=='edit')
       {
       $model  = $this->getModel('Article');
       $ret=$model->edit_article();
        /*****update****/
       if($ret)
           {
           $msg=JTEXT::_( 'UA_ARTICLE_UPDATED' );
           $app->redirect($url,$msg);
           }
       }

       //delete article
       $task=JRequest::getVar('task',null,'GET');
       if($task=='delete')
       {
       $model  = $this->getModel('Article');
       $del=$model->deleteArticle();
       $msg=JTEXT::_( 'UA_ARTICLE_DELETED' );
       if($del)
           {
               $app->redirect($url,$msg);
           }
       }

      //display

      parent::display();

    }

}