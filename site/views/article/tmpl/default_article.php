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

     defined('_JEXEC') or die('Restricted access');

     $config = JFactory::getConfig();

     $offset = $config->getValue( 'config.offset' );

     date_default_timezone_set($offset);

     $user = JFactory::getUser();
     $uri = JFactory::getURI();
     $link_new_article = JRoute::_( 'index.php?option=com_userarticle&view=article&task=add',false);
     $link_cancel_article = JRoute::_( 'index.php?option=com_userarticle&view=article',false);


        // Assign data

        $data=$this->data;
        $tot=$this->total;
        $rows=ceil($tot/10);

        $doc = JFactory::getDocument();
        $content=" function tableOrdering( order, dir, task )
         {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
         } ";
        $doc->addScriptDeclaration( $content );

        ?>
        <!--Filter search Form starts -->
        <form method="get" action="<?php echo $uri; ?>" >
        <p style="float:left;">
            <label> <?php echo JTEXT::_( 'UA_FILTER' ); ?> </label>
        <input type="hidden" name="option" value="<?php echo JRequest::getVar('option',null,'GET'); ?>" />
        <input type="hidden" name="view" value="<?php echo JRequest::getVar('view',null,'GET'); ?>" />
            <input type="input" name="filter_text" value="<?php echo JRequest::getVar('filter_text',null,'GET'); ?>" />
            <select name="filter_category" style="*width:120px;">
                <option value="">-Select Category-</option>
                 <?php
                        foreach ($this->category as $val){
                      ?>
                        <option <?php if( $val->id == JRequest::getVar('filter_category',null,'GET')) { echo "selected='selected'"; } ?> value="<?php echo $val->id;?>"><?php echo $val->title;?></option>

                  <?php } ?>

            </select>
            <select name="filter_select" style="*width:110px;">
                <option value="">-Select Status-</option>
                <option <?php if(JRequest::getVar('filter_select',null,'GET')==1) echo "selected='selected'"; ?> value="1">Published</option>
                <option  <?php if(JRequest::getVar('filter_select',null,'GET')==2) echo "selected='selected'"; ?> value="2">Unpublished</option>
            </select>

            <input class="button" type="submit" value="<?php echo JTEXT::_( 'UA_FILTER' ); ?>" name="submit" />
            <button type="button" class="button" name="reset" value="Reset" onclick="window.location.href='<?php echo $link_cancel_article; ?>'" ><?php echo JText::_( 'UA_RESET' ); ?></button>

        </p>
         <!--Add Icon -->
        <p style="float:right">
            <?php if($user->id)
                    {
            ?>
            <a href="<?php echo $link_new_article; ?>" class="add_grid">
                <span class="add_btn"><?php echo JTEXT::_( 'UA_NEW_ARTICLE' ); ?></span>

                    </a>

            <?php } ?>
         </p>
        </form>
        <!--Filter search Form ends -->



         <br clear="all" />

         <form id="adminForm"  method="post" name="adminForm">
          <table  width="100%" cellpadding="0" cellspacing="0" border="1" id="table_frontend_user_article_list" class="category">
                <tr  class="sortable">
                    <th width="10%"  height="30"  align="center" valign="center"><strong><?php echo JTEXT::_( 'UA_SNO' ); ?></strong></th>
                     <th width="25%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_TITLE' ), 'title', $this->sortDirection, $this->sortColumn); ?></th>
                     <th width="20%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_CATEGORY' ), 'catid', $this->sortDirection, $this->sortColumn); ?></th>
                     <th width="15%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_PUBLISHDATE' ), 'publish_up', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="10%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_STATUS' ) , 'state', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="10%" align="center" valign="center"><strong><?php echo JTEXT::_( 'UA_ACTION' );  ?></strong></th>
                    <th width="10%" align="center" valign="center"><strong><?php echo JTEXT::_( 'UA_HITS' ); ?></strong></th>
                </tr>
                <?php

                if($data)
                {

                        $i=1;  //increment
                        $limitstart=JRequest::getint( 'limitstart' );
                        $limit=JRequest::getint( 'limit' );
                        if($limitstart)
                        {
                            $lim=0+$limitstart;
                        }
                        else {
                            $lim=0;
                        }

                        foreach($data as $val) //show rows
                        {

                  ?>
                        <tr>
                               <td  height="30" align="center"><?php echo $i+$lim;?></td>
                                <td>
                                    <?php
                                    if($val->state==1)
                                     {
                                     if($val->publish_down!='0000-00-00 00:00:00')
                                     {
                                     $down=$val->publish_down;
                                     }
                                     else {

                                     $down=date("Y-m-d H:i:s");

                                    }
                                    if($val->state==1 &&  (strtotime(date("Y-m-d H:i:s")) >= strtotime($val->publish_up) && strtotime(date("Y-m-d H:i:s")) <= strtotime($down) )  )
                                     {
                                      // Generating URL
                                    require_once 'components/com_content/helpers/route.php';
                                    $a=$val->cid.':'.$val->con_alias;
                                    error_reporting(0);
                                    $newUrl = JRoute::_(ContentHelperRoute::getArticleRoute($a,$val->catid));
                                     }
                                     else
                                     {
                                      //edit url
                                      $newUrl=JRoute::_('index.php?option=com_userarticle&view=article&task=edit&id='.$val->cid ,false);
                                     }
                                     }
                                         else {
                                         $newUrl=JRoute::_('index.php?option=com_userarticle&view=article&task=edit&id='.$val->cid ,false);
                                        }


                                     ?>
                                    <a title="<?php echo $val->title; ?>" href="<?php echo $newUrl; ?>" >
                                    <?php echo $val->title; ?>
                                    </a>
                                </td>
                                <td> <?php echo $val->cattitle; ?> </td>
                                <td align="center">
                                    <?php if(strtotime($val->publish_up) !=null) { echo date("d-m-Y",strtotime($val->publish_up));} else { echo JTEXT::_( 'Null'); } ?>
                                </td>
                                <td align="center">
                                 <?php
                                 if($val->state==1)
                                 {
                                 if($val->publish_down!='0000-00-00 00:00:00')
                                 {
                                 $down=$val->publish_down;
                                 }
                                 else {

                                 $down=date("Y-m-d H:i:s");

                                }
                                 if($val->state==1 &&  (strtotime(date("Y-m-d H:i:s")) >= strtotime($val->publish_up) && strtotime(date("Y-m-d H:i:s")) <= strtotime($down) ) )
                                 {
                                  echo '<img src='.JURI::base().'components/com_userarticle/assets/images/tick.png title="'.JText::_( 'UA_PUBLISHED' ).'" />';
                                 }
                                 else {

                                     echo '<img src='.JURI::base().'components/com_userarticle/assets/images/pending.png title="'.JText::_( 'UA_PUBLISHED_ERROR' ).'" />';

                                     }

                                 }
                                 if($val->state=='0')
                                  {
                                   echo '<img src='.JURI::base().'components/com_userarticle/assets/images/publish_x.png title="'.JText::_( 'UA_UNPUBLISHED' ).'" />';
                                  }
                                 ?>
                                 </td>
                                 <td align="center">
                                    <div class="imgfloat">
                                    <a class="article_img_icon_edit" title="Edit" href="<?php echo JRoute::_('index.php?option=com_userarticle&view=article&task=edit&id='.$val->cid);?>">
                                        <img src="<?php echo JURI::base()?>/components/com_userarticle/assets/images/pencil.png" />
                                    </a>
                                    <a class="article_img_icon_delete" title="Delete" onclick="return confirm('<?php echo JText::_('UA_ARE_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_userarticle&view=article&task=delete&id='.$val->cid ,false);?>">
                                       <img src="<?php echo JURI::base()?>/components/com_userarticle/assets/images/trash.png" />
                                    </a>
                                    </div>
                                </td>
                                <td align="center"><?php echo $val->hits; ?> </td>
                            </tr>
                        <?php $i++;

                           }

                    }
                    else
                    {
                    echo '<tr><td colspan=\'7\' align=\'center\'>'.JTEXT::_( 'UA_NOT_FOUND' ).'</td></tr>'; //if not found

                    }
                    ?>
        </table>
           <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />

<input type="hidden" value="" name="task">

</form>





            <!-- page number Form starts-->

            <form method="get" action="<?php echo $uri; ?>">


                <input type="hidden" name="option" value="<?php echo JRequest::getVar('option',null,'GET'); ?>" />
              <input type="hidden" name="view" value="<?php echo JRequest::getVar('view',null,'GET'); ?>" />
              <?php if(JRequest::getVar('filter_text',null,'GET')) { ?>
             <input type="hidden" name="filter_text" value="<?php echo JRequest::getVar('filter_text',null,'GET'); ?>" />
             <?php } ?>
             <?php if(JRequest::getVar('filter_select',null,'GET')) { ?>
             <input type="hidden" name="filter_select" value="<?php echo JRequest::getVar('filter_select',null,'GET'); ?>" />
             <?php } ?>
              <?php if(JRequest::getVar('filter_category',null,'GET')) { ?>
             <input type="hidden" name="filter_category" value="<?php echo JRequest::getVar('filter_category',null,'GET'); ?>" />
             <?php } ?>
             <input type="hidden" value="" name="task">

             <div style="text-align: center">
                        <?php echo $this->pagination->getListFooter(); ?>
             </div>
            </form>

           <!-- page number Form ends-->