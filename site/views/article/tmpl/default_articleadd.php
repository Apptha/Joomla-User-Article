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
        jimport( 'joomla.html.html' );
        JHtml::_('behavior.tooltip');
        //load libraries for validation
        JHtml::_ ( 'behavior.formvalidation' );
        JHtml::_('behavior.calendar');
        $user = JFactory::getUser();
        $uri = JFactory::getURI();
        $link_save_article = JRoute::_('index.php?option=com_userarticle&view=article&task=add');
        $link_cancel_article = JRoute::_('index.php?option=com_userarticle&view=article');

        

?>

<!--Article Add Form -->

<div class="article_form">
        <form name="articleform"  method="post" class="form-validate" action="<?php echo $uri; ?>">
           


            <fieldset class="field_article">
                <legend><?php echo JTEXT::_( 'UA_NEW_ARTICLE' ); ?></legend>
                <p style="float:right">
            <button style=" float: left; padding-right: 20px; background: none; border: none; cursor: pointer; color: #7BA428;" title="<?php echo JTEXT::_('UA_SAVE'); ?>" >
                    <span class="save_btn"></span>
                    <?php echo JTEXT::_('UA_SAVE'); ?>
             </button>
            <a style=" float: left; text-decoration: none; cursor:pointer; " href="<?php echo $link_cancel_article; ?>" title="<?php echo JTEXT::_( 'UA_CANCEL' ); ?>">
                <span class="cancel_btn"></span>
                <?php echo JTEXT::_( 'UA_CANCEL' ); ?>
            </a>
            </p>

                <p><label for="article_title"><?php echo JTEXT::_( 'UA_TITLE' ); ?>*</label>
                <input type="text" name="title"  class="required" id="article_title"/>
                </p>
                <p><label><?php echo JTEXT::_( 'UA_ALIAS' ); ?></label>
                <input type="text" name="alias"  />
                </p>
                <p><label><?php echo JTEXT::_( 'UA_CATEGORY' ); ?>*</label>
                    <select name="category" class="required"> 
                     <?php
                        foreach ($this->category as $val){
                      ?>
                        <option value="<?php echo $val->id;?>"><?php echo $val->title;?></option>

                        <?php } ?>
                    </select>
                </p>
                <p class="article_content_edit"><label> <?php echo JTEXT::_( 'UA_CONTENT' ); ?></label>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('content','', '550', '300', '60', '20', false );  //to display editor
                ?>
                </p>
                <p>
                    <label><?php echo JTEXT::_( 'UA_START_PUBLISHING' ); ?> </label>

                     <?php echo JHTML::_('calendar','', 'start', 'start', $format = '%d-%m-%Y %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </p>

                 <p>
                    <label><?php echo JTEXT::_( 'UA_FINISH_PUBLISHING' ); ?></label>
                     <?php echo JHTML::_('calendar','', 'end', 'end', $format = '%d-%m-%Y %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </p>
            </fieldset>
            <input type="hidden" name="task" value="add" />
           </form>
</div>