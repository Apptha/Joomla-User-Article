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
$option=JRequest::getVar('option');
$view=JRequest::getVar('view');
?>
<ul class="menu">
    <li <?php if($option == 'com_userarticle' && $view == 'article') { echo 'class="current active"'; } ?> >
<?php
if($result)
 {
$link=JROUTE::_( 'index.php?option=com_userarticle&view=article&Itemid='.$result ,false );
 ?>
<a href="<?php echo $link ?>"><?php echo JTEXT::_( 'MOD_UA' ); ?></a>
<?php } else {  ?>
 <a href="javascript:void(0);">
 <?php echo JText::_( 'MOD_UA_NOT' ); ?>
 </a>
 <?php  } ?>
    </li>
</ul>