<?php
/**
 * Application for working on the Joomla! RendererInterface
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Renderer\Twig;

/**
 * Configuration service provider
 *
 * @since  1.0
 */
class TwigRendererProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Container  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		$container->set(
			'renderer',
			function (Container $container) {
				/* @type  \Joomla\Registry\Registry  $config */
				$config = $container->get('config');

				$loader = new \Twig_Loader_Filesystem($config->get('template.path'));

				return new Twig($loader);
			},
			true,
			true
		);
	}
}
