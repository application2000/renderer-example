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
use Joomla\Renderer\Mustache;

/**
 * Mustache renderer service provider
 *
 * @since  1.0
 */
class MustacheRendererProvider implements ServiceProviderInterface
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

				$loaderOptions = ['extension' => $config->get('template.extension')];

				$options = [
					'loader'          => new \Mustache_Loader_FilesystemLoader($config->get('template.path'), $loaderOptions),
					'partials_loader' => new \Mustache_Loader_FilesystemLoader($config->get('template.partials'), $loaderOptions),
				];

				$options = array_merge($options, $this->config);

				return new Mustache($options);
			},
			true,
			true
		);
	}
}
