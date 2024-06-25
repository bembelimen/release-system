<?php
/**
 * @package   AkeebaReleaseSystem
 * @copyright Copyright (c)2010-2024 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Component\ARS\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\Database\DatabaseInterface;

class ArsCategoriesField extends ListField
{
	protected $type = 'ArsCategories';

	protected function getInput()
	{
		$db    = Factory::getContainer()->get(DatabaseInterface::class);
		$query = (method_exists($db, 'createQuery') ? $db->createQuery() : $db->getQuery(true))
			->select([
				$db->qn('id'),
				$db->qn('title'),
			])->from($db->qn('#__ars_categories'));
		$db->setQuery($query);

		$objectList = $db->loadObjectList() ?? [];

		foreach ($objectList as $o)
		{
			$this->addOption($o->title, [
				'value' => $o->id,
			]);
		}

		return parent::getInput();
	}
}