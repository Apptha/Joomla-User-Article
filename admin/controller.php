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

jimport( 'joomla.application.component.controller' );

/**
 * Login Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_login
 * @since		1.5
 */
class UserarticleController extends JController
{
	
	public function display($cachable = false, $urlparams = false)
	{
		// Special treatment is required for this plugin, as this view may be called
		// after a session timeout. We must reset the view and layout prior to display
		// otherwise an error will occur.

		JRequest::setVar('view', 'userarticle');
		JRequest::setVar('layout', 'default');

		parent::display();
	}

	}
