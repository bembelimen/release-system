<?php
/**
 * @package AkeebaReleaseSystem
 * @copyright Copyright (c)2010-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id$
 */

defined('_JEXEC') or die('Restricted Access');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/views/base.view.html.php';

class ArsViewCategories extends ArsViewBase
{
	protected function onDisplay()
	{
		$app = JFactory::getApplication();
		$hash = $this->getHash();
		
		// ...filter states
		$this->lists->set('fltTitle',	$app->getUserStateFromRequest($hash.'filter_title',
			'title', null));
		$this->lists->set('fltType',	$app->getUserStateFromRequest($hash.'filter_type',
			'type', null));
		$this->lists->set('fltPublished',$app->getUserStateFromRequest($hash.'filter_published',
			'published', null));
		$this->lists->set('fltVgroups',$app->getUserStateFromRequest($hash.'filter_published',
			'vgroup', null));
		
		$vgModel = JModel::getInstance('Vgroups','ArsModel');
		$raw = $vgModel->getItemList(true);
		$vgroups = array();
		if(!empty($raw)) foreach($raw as $r) {
			$vgroups[$r->id] = $r->title;
		}
		$this->assign('vgroups', $vgroups);

		// Add toolbar buttons
		if($this->perms->editstate) {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::divider();
		}
		if($this->perms->create) {
			JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'ARS_GLOBAL_COPY', false);
		}
		if($this->perms->delete) {
			JToolBarHelper::deleteList();
		}
		if($this->perms->edit) {
			JToolBarHelper::editListX();
		}
		if($this->perms->create) {
			JToolBarHelper::addNewX();
		}
		JToolBarHelper::divider();
		JToolBarHelper::back(version_compare(JVERSION,'1.6.0','ge') ? 'JTOOLBAR_BACK' : 'Back', 'index.php?option='.JRequest::getCmd('option'));

		// Add submenus (those nifty text links below the toolbar!)
		// -- Categories
		$link = JURI::base().'?option='.JRequest::getCmd('option').'&view=categories';
		JSubMenuHelper::addEntry(JText::_('ARS_TITLE_CATEGORIES'), $link, (JRequest::getCmd('view','cpanel') == 'categories'));
		// -- Releases
		$link = JURI::base().'?option='.JRequest::getCmd('option').'&view=releases';
		JSubMenuHelper::addEntry(JText::_('ARS_TITLE_RELEASES'), $link, (JRequest::getCmd('view','cpanel') == 'releases'));
		// -- Items
		$link = JURI::base().'?option='.JRequest::getCmd('option').'&view=items';
		JSubMenuHelper::addEntry(JText::_('ARS_TITLE_ITEMS'), $link, (JRequest::getCmd('view','cpanel') == 'items'));
		if($this->perms->create) {
			// -- Import
			$link = JURI::base().'?option='.JRequest::getCmd('option').'&view=impjed';
			JSubMenuHelper::addEntry(JText::_('ARS_TITLE_IMPORT_JED'), $link, (JRequest::getCmd('view','cpanel') == 'impjed'));
		}
		
		// Load the select box helper
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/select.php';

		// Run the parent method
		parent::onDisplay();
	}

	protected function onAdd()
	{
		// Load the select box helper
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/select.php';
		parent::onAdd();
	}
}