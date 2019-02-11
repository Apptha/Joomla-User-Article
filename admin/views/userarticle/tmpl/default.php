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

jimport( 'joomla.html.html.grid' );
jimport('joomla.filter.output');
$user = JFactory::getUser();

/* declare css file*/
$doc = JFactory::getDocument();
$doc->addStyleSheet('../components/com_userarticle/assets/article.css');


  $user = JFactory::getUser();

        /**        Get Assign data    **/

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

         $link_cancel_article = JRoute::_( 'index.php?option=com_userarticle' );
        ?>
        <!--Filter search Form starts -->
        <form method="get" action="<?php echo JFACTORY::getURI(); ?>" >
        <p style="float:left;">
            <label> <?php echo JTEXT::_( 'UA_FILTER' ); ?> </label>
        <input type="hidden" name="option" value="<?php echo JRequest::getVar('option',null,'GET'); ?>" />
        <input type="hidden" name="view" value="<?php echo JRequest::getVar('view',null,'GET'); ?>" />
            <input type="input" name="filter_text" value="<?php echo JRequest::getVar('filter_text',null,'GET'); ?>" />
             <select name="filter_category">
                <option value="">-Select Category-</option>
                 <?php
                        foreach ($this->category as $val){
                      ?>
                        <option <?php if( $val->id == JRequest::getVar('filter_category',null,'GET')) { echo "selected='selected'"; } ?> value="<?php echo $val->id;?>"><?php echo $val->title;?></option>

                  <?php } ?>

            </select>
            <select name="filter_select">
                <option value="">-Select Status-</option>
                <option <?php if(JRequest::getVar('filter_select',null,'GET')==1) echo "selected='selected'"; ?> value="1">Published</option>
                <option  <?php if(JRequest::getVar('filter_select',null,'GET')==2) echo "selected='selected'"; ?> value="2">Unpublished</option>
            </select>
            <input class="button" type="submit" value="<?php echo JTEXT::_( 'UA_FILTER' ); ?>" name="submit" />
            <button type="button" class="button" name="reset" value="Reset" onclick="window.location.href='<?php echo $link_cancel_article; ?>'" ><?php echo JText::_( 'UA_RESET' ); ?></button>

        </p>
        </form>
        <!--Filter search Form ends -->

         <br clear="all" />

         <form  id="adminForm" name="adminForm"  method="post" action="<?php echo JFACTORY::getURI(); ?>" >
          <table  width="100%" border="0"  class="adminlist">
              <thead>
              <tr  class="sortable">
                     <th width="30%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_TITLE' ), 'title', $this->sortDirection, $this->sortColumn); ?></th>
                     <th width="20%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_CATEGORY' ), 'catid', $this->sortDirection, $this->sortColumn); ?></th>
                     <th width="15%"  height="30"  align="center" valign="center"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_AUTHOR' ), 'created_by', $this->sortDirection, $this->sortColumn); ?> </th>
                     <th width="15%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_PUBLISHDATE' ), 'publish_up', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="10%"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_STATUS' ) , 'state', $this->sortDirection, $this->sortColumn); ?></th>
                    <th width="10%" align="center" valign="center"><?php echo JHTML::_( 'grid.sort', JTEXT::_( 'UA_ID' ) , 'id', $this->sortDirection, $this->sortColumn); ?></th>
                </tr>
              </thead>
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

                        foreach($data as $val) //////show rows
                        {
                  ?>
                        <tr class=<?php $color='row'.($i%2); echo $color; ?>>

                                <td>
                                    <a title="<?php echo $val->title; ?>" href="<?php echo JRoute::_( 'index.php?option=com_content&task=article.edit&id='.$val->cid ); ?>" >
                                    <?php echo $val->title; ?>
                                    </a>
                                </td>
                                <td> <?php echo $val->cattitle; ?> </td>
                                 <td align="center"><?php $user = JFactory::getUser($val->created_by); echo $user->name;?></td>
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
                                  echo '<img src="../components/com_userarticle/assets/images/tick.png" title="Published" />';
                                 }
                                 else {

                                     echo '<img src="../components/com_userarticle/assets/images/pending.png" title="Error in Publishing" />';

                                     }

                                 }
                                 if($val->state=='0')
                                  {
                                   echo '<img src="../components/com_userarticle/assets/images/publish_x.png" title="Unpublished" />';
                                  }
                                 ?>
                                 </td>

                                <td align="center">
                                  <a href="<?php echo JRoute::_( 'index.php?option=com_content&task=article.edit&id='.$val->cid ); ?>" >
                                  <?php echo $val->cid; ?>
                                  </a>
                                  </td>
                            </tr>
                        <?php $i++;

                           }

                    }
                    else
                    {
                    echo '<tr><td colspan=\'7\' align=\'center\'>'.JTEXT::_( 'No Article Found' ).'</td></tr>'; //if not found

                    }
                    ?>

           <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />





            <!-- page number Form starts-->




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
             <tfoot>
             <tr><td colspan="10" align="center">
 <?php echo $this->pagination->getListFooter(); ?>
             </td>
             </tr>
             </tfoot>
         </table>
         </form>


           <!-- page number Form ends-->