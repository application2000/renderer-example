<?php
/**
 * Application for working on the Joomla! RendererInterface
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Model;

use Joomla\Model\AbstractModel;

/**
 * Default model class for the application
 *
 * @since  1.0
 */
class DefaultModel extends AbstractModel
{
	public function getData()
	{
		return [
			'name'  => 'Michael',
		    'title' => 'Developer'
		];
	}
}
