<?php
/**
 * Application for working on the Joomla! RendererInterface
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Service;

use BabDev\Renderer\PlatesRenderer;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use League\Plates\Engine;

/**
 * Plates renderer service provider
 *
 * @since  1.0
 */
class PlatesRendererProvider implements ServiceProviderInterface
{
	/**
	 * Configuration instance
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $config;

	/**
	 * Constructor.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct(array $config = array())
	{
		$this->config = $config;
	}

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

				$engine = (new Engine($config->get('template.path')))
					->addFolder('partials', $config->get('template.partials'));

				return new PlatesRenderer($engine);
			},
			true,
			true
		);
	}
}
