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

// No direct access.
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class UserarticleViewUserarticle extends JView
{
     function display($tpl = null)
    {
       ///soting/////
        $items = $this->get('Items');
        $state = $this->get('State');

        $order_f=$this->sortDirection = $state->get('filter_order_Dir');
        $order_s=$this->sortColumn = $state->get('filter_order');


       $model  = $this->getModel('userarticle');
       $user = JFactory::getUser();

     $search_text=JRequest::getVar('filter_text');

     $search_select=JRequest::getVar('filter_select');

     $search_category=JRequest::getVar('filter_category');

     $data=$model->getArticle($search_text,$search_select,$search_category,$order_s,$order_f);
     $this->assignRef( 'data', $data );
     $total= $model->articleCount($search_text,$search_select,$search_category);

       /*******  pagenation ***********/
      $this->assignRef( 'total', $total );
      $this->assignRef( 'option', $option );

      //////////////edit data//////////
      $task=JRequest::getVar('task',null,'GET');
      $id=JRequest::getInt('id',null,'GET');
      if($task && $id )
      {
      $onearticle=$model->getOneArticle($user->id,$id);
      $this->assignRef( 'onearticle', $onearticle );
      }

      $cate=$model->getCategory();
      $this->assignRef( 'category', $cate );

       /////pagenation////////////

// Get data from the model
      $items = $this->get('Data');
       $pagination = $this->get('Pagination');
          // push data into the template
        $this->assignRef('items', $items);
        $this->assignRef('pagination', $pagination);

      parent::display($tpl);
    }
}
