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


defined('_JEXEC') or die;

jimport('joomla.application.component.model');

jimport('joomla.html.pagination');

class UserArticleModelArticle extends JModel
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
               //********** Sorting ***********///

                 if($sorting && $ord)
        {
        $or= "c.".$sorting." ".$ord;
        }
        else
        {
            $or= "c.id DESC";
        }

/************build query***********/

            $query="SELECT c.title,c.publish_up,c.publish_down,c.state,c.id as cid,cat.title as cattitle,c.hits ,c.catid,c.alias as con_alias,cat.alias as cat_alias
                    FROM  #__content AS c
                    INNER JOIN  #__categories AS cat
                    ON c.catid=cat.id
                    WHERE c.created_by='$user->id,' AND (c.state=0 OR c.state=1 ) $wh
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
            WHERE c.created_by='$user->id'
            AND (c.state=0 OR c.state=1 ) ".$wh ;


    $db->setQuery($query);
    $article_count=$db->loadResult();
    $limit=$article_count;
   return $article_count;


  }


  /*********** Delete article ***********/


 function deleteArticle()
     {
     $task=JRequest::getVar('task',null,'GET');
     $id=JRequest::getInt('id',null,'GET');
         if($task=='delete')
         {
         $db = JFactory::getDBO();
         $db->setQuery("UPDATE #__content SET state='-2' WHERE id=$id ");
         $db->query();
         $delete=$db->getAffectedRows ();
         }
    return $delete;
    }


/*********************Save Article***************************/

    function  save_article()
    {
        $insert=null; //insert variable
        $db = JFactory::getDBO();
        $title = JRequest::getvar('title',null,'POST' );
        $contents = JRequest::getvar('content','', 'post', 'string', JREQUEST_ALLOWRAW);
        $content = $db->getEscaped( $contents );
        $aliaing = JRequest::getvar('alias',null,'POST');
        if($aliaing)
        {
        $aliaing_r = str_replace('-', ' ', $aliaing);
        $aliaing_prase = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $aliaing_r);
        // lowercase and trim
        $alias = trim(strtolower($aliaing_prase));
        }
        else {
        $aliaing_r = str_replace('-', ' ', $title);
        $aliaing_prase = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $aliaing_r);
        // lowercase and trim
        $alias = trim(strtolower($aliaing_prase));
        }
        $start_b = JRequest::getvar('start',null,'POST');
        if(strtotime($start_b) > 0)
        {
        $start=date('Y-m-d H:i:s', strtotime($start_b));
        }
        else
        {
        $start=date('Y-m-d H:i:s');
        }
        $end_b = JRequest::getvar('end',null,'POST');

        if(strtotime($end_b) > 0)
        {
        $end=date('Y-m-d H:i:s', strtotime($end_b));
        }
        else {
        $end='';
        }

        $task = JRequest::getvar('task',null,'POST');
        $category = JRequest::getvar('category',null,'POST');
        
        /***********Task Add*************/
        if($title && $task=='add')
        {



        $user = JFactory::getUser();
        $user_id=$user->id;


       /**********Insert data**************************/
        $db->setQuery("INSERT INTO #__content
                (title,alias,introtext,catid,state,created_by,created,access,publish_up,publish_down)
                VALUES
                ('$title','$alias','$content','$category','0','$user_id',NOW(),'1','$start','$end' )");

        $db->query();
        $lastid=$db->insertid();
        
        if($lastid)  //if inserted
        {
        $mail_article_link=JURI::base()."administrator/index.php?option=com_userarticle";
        $mailer = JFactory::getMailer();

        $config = JFactory::getConfig();

        $recipient = $config->getValue( 'config.mailfrom' );

        $hi = $config->getValue( 'config.fromname' );

        $user = JFactory::getUser();

        $sender = array( $user->email, $user->username );

        $body   = "
        Dear ".$hi.", <br>

        Hi, I am  Added New Article in Our Site. Kindly Publish the Article
                  <br>
                  To View Article Please Click Here <a href='$mail_article_link' target='_blank' > $title </a> ";

        $mailer->setSubject('Article form User');

        $mailer->isHTML(true);       

        $mailer->setBody($body);

        $mailer->addRecipient($recipient);
        
        $mailer->setSender($sender);

        $send = $mailer->Send();

        }

        }

    

    return $lastid ;

   }

 /*********************Edit Article***************************/

    function  edit_article()
    {
        $db = JFactory::getDBO();
        $title = JRequest::getvar('title',null,'POST' );
        $contents = JRequest::getvar('content','', 'post', 'string', JREQUEST_ALLOWRAW);
        $content = $db->getEscaped( $contents );
        $aliaing = JRequest::getvar('alias',null,'POST');
        if($aliaing)
        {
        $aliaing_r = str_replace('-', ' ', $aliaing);
        $aliaing_prase = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $aliaing_r);
        // lowercase and trim
        $alias = trim(strtolower($aliaing_prase));
        }
        else {
        $aliaing_r = str_replace('-', ' ', $title);
        $aliaing_prase = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $aliaing_r);
        // lowercase and trim
        $alias = trim(strtolower($aliaing_prase));
        }
        $start_b = JRequest::getvar('start',null,'POST');
        if(strtotime($start_b) > 0)
        {
        $start=date('Y-m-d H:i:s', strtotime($start_b));
        }
        else
        {
        $start=date('Y-m-d H:i:s');
        }
        $end_b = JRequest::getvar('end',null,'POST');

        if(strtotime($end_b) > 0)
        {
        $end=date('Y-m-d H:i:s', strtotime($end_b));
        }
        else {
        $end='';
        }

        $task = JRequest::getvar('task',null,'POST');
        $category = JRequest::getvar('category',null,'POST');

       
        /***********Task Edit*************/

        if($title && $task=='edit')
        {
        $id=JRequest::getInt( 'id' ,null,'GET' );
        $insert= -1;

        $user = JFactory::getUser();
        $user_id=$user->id;

        //DB config//
        $db = JFactory::getDBO();


/**********update data**************************/

        $db->setQuery("UPDATE  #__content
                      SET title='$title', alias='$alias', introtext='$content', catid='$category', modified=now(), publish_up='$start', publish_down='$end', modified_by='$user_id'
                      WHERE id='$id' ");

        $db->query();
        $affected=$db->getAffectedRows ();
        if($affected)
        {
        $mail_article_link=JURI::base()."administrator/index.php?option=com_userarticle";

        $mailer = JFactory::getMailer();

        $config = JFactory::getConfig();

        $recipient = $config->getValue( 'config.mailfrom' );

        $hi = $config->getValue( 'config.fromname' );

        $user = JFactory::getUser();

        $sender = array( $user->email, $user->username );

        $body   = "
        Dear ".$hi.", <br>

        Hi, I am  Edited Article in Our Site
                  <br>
                  To View Article Please Click Here <a href='$mail_article_link' target='_blank' > $title </a> ";


        $mailer->setSubject('Article form User');

        $mailer->isHTML(true);
        
        $mailer->setBody($body);


        $mailer->addRecipient($recipient);

        $mailer->setSender($sender);

        $send = $mailer->Send();

        }

   }

    return $affected ;

   }
   
   /** **********Get Only one Article data **************/

 function getOneArticle($user_id=null,$id=null)
          {
            $db = JFactory::getDBO();

            /*******************Where condition***************************/
                if($id)
                 {
                 $wh="AND id='$id'";
                 }

/************build query***********/

         $query="SELECT title,alias,publish_up,publish_down,state,id,introtext,catid
                 From #__content where  created_by='$user_id' AND (state=0 OR state=1 ) $wh ";

            $db->setQuery($query);
            $result=$db->loadObject();

            return $result;

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

//pagenation

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







?>