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

jimport( 'joomla.application.component.model' );


class UserarticleModelUserarticle extends JModel
{
	var $insert; // return save output

  var $where=array();
  var $limit=null;

  function __construct()
  {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
  }

  /** **********Get Article data **************/

 function getArticle($where_text=null,$where_select=null,$where_category=null,$sorting=null,$ord=null)
          {
     $start = $this->getState('limitstart');
     $end = $this->getState('limit');
      if($start || $end) {
      $limit=" LIMIT $start, $end";
      }
      else {
          $limit='';
      }

      $db= JFactory::getDBO();
             $user = JFactory::getUser();

            $wh=null;

            /*******************Where condition***************************/
                if($where_text || $where_select || $where_category )
                {

                if($where_text)
                {
                   $wh=" AND c.title LIKE '".$where_text."%' ";
                }
               if($where_select==2 && $where_select )
                {
                 $wh .=" AND c.state= '0'";
                }
               if($where_select && $where_select != 2)
                {
                   $wh .=" AND c.state= '$where_select'";
                }
                if($where_category)
                {
                   $wh .=" AND c.catid = '$where_category'";
                }

                 }
               //*********************///

                 if($sorting && $ord)
        {
        $or= "c.".$sorting." ".$ord;
        }
        else
        {
            $or= "c.id DESC";
        }

/************build query***********/

            $query="SELECT c.title,c.publish_up,c.publish_down,c.state,c.id as cid,cat.title as cattitle,c.created_by
                    FROM  #__content AS c
                    INNER JOIN  #__categories AS cat
                    ON c.catid=cat.id
                    WHERE  (c.state=0 OR c.state=1 ) $wh
                    ORDER BY $or $limit";

            $db->setQuery($query);
            $result=$db->loadObjectList();

            return $result;

          }

/*total count of article*/

/****pagenation ***************/

  function articleCount($where_text=null,$where_select=null,$where_category=null)
  {
    $db= JFactory::getDBO();
    $user = JFactory::getUser();
    $wh=null;

           /*******************Where condition***************************/
                if($where_text || $where_select || $where_category )
                {

                if($where_text)
                {
                   $wh=" AND c.title LIKE '".$where_text."%' ";
                }
               if($where_select==2 && $where_select )
                {
                 $wh .=" AND c.state= '0'";
                }
               if($where_select && $where_select != 2)
                {
                   $wh .=" AND c.state= '$where_select'";
                }
                if($where_category)
                {
                   $wh .=" AND c.catid = '$where_category'";
                }

                 }
   /************build select query***********/

   $query="SELECT count(c.id)
            FROM  #__content AS c
            INNER JOIN  #__categories AS cat
            ON c.catid=cat.id
            WHERE (c.state=0 OR c.state=1 ) ".$wh ;


    $db->setQuery($query);
    $article_count=$db->loadResult();
   $limit=$article_count;
   return $article_count;


  }


  /*********** Delete article ***********/


 function deleteArticle()
     {
     $t=0;
     $task=JRequest::getVar('task',null,'GET');
     $id=JRequest::getInt('id',null,'GET');
         if($task=='delete')
         {
         $db = JFactory::getDBO();
         $db->setQuery("UPDATE #__content SET state='-2' WHERE id=$id ");
         $db->query();
         $t=1;
         }
    return $t;
    }






   function getCategory(){

       $db = JFactory::getDBO();
       $query="SELECT id,title
               From #__categories
               WHERE  level='1'
               AND extension='com_content'
               AND published='1' ";

            $db->setQuery($query);
            $result=$db->loadObjectList();

            return $result;


   }


   public function populateState() {
        $filter_order = JRequest::getCmd('filter_order');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');

        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);
        parent::populateState();
}

/////pagenation

 function getPagination()
  {

      $search_text=JRequest::getVar('filter_text');

     $search_select=JRequest::getVar('filter_select');

     $search_category=JRequest::getVar('filter_category');

        $limit = $this->articleCount($search_text,$search_select,$search_category);
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');

            $this->_pagination = new JPagination( $limit, $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_pagination;
  }
}
