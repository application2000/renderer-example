<?php
/**
 * Application for working on the Joomla! RendererInterface
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\View;

/**
 * JSON view class for the Dashboard
 *
 * @since  1.0
 */
class DashboardJsonView
{
	/**
	 * Render and return compiled data.
	 *
	 * @param   string  $template  The template file name
	 * @param   array   $data      The data to pass to the template
	 *
	 * @return  string  Compiled data
	 *
	 * @since   1.0
	 */
	public function render($template = '', array $data = array())
	{
		$output = $data['model']->getData();

		return json_encode($output);
	}
}
