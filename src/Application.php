<?php
/**
 * Application for working on the Joomla! RendererInterface
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev;

use Joomla\Application\AbstractWebApplication;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\Router\Router;

use Joomla\Status\Model\DefaultModel;
use Joomla\Status\View\DefaultHtmlView;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Web application class
 *
 * @since  1.0
 */
final class Application extends AbstractWebApplication
{
	/**
	 * Method to run the application routines
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Instantiate the router
			$router = (new Router($this->input))
				->setControllerPrefix('\\BabDev')
				->setDefaultController('\\Controller\\DefaultController');

			// Fetch the controller
			/* @type  \Joomla\Controller\AbstractController  $controller */
			$controller = $router->getController($this->get('uri.route'));

			// Inject the application into the controller and execute it
			$controller->setApplication($this)->execute();
		}
		catch (\Exception $exception)
		{
			// Set the appropriate HTTP response
			switch ($exception->getCode())
			{
				case 404 :
					$this->setHeader('HTTP/1.1 404 Not Found', 404, true);

					break;

				case 500 :
				default  :
					$this->setHeader('HTTP/1.1 500 Internal Server Error', 500, true);

					break;
			}

			// Render the message based on the format
			switch (strtolower($this->input->getWord('format', 'html')))
			{
				case 'json' :
					$data = [
						'code'    => $exception->getCode(),
						'message' => $exception->getMessage(),
						'error'   => true
					];

					$body = json_encode($data);

					break;

				case 'html' :
				default :
					$body = '';

					break;
			}

			$this->setBody($body);
		}
	}

	/**
	 * Custom initialisation method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initialise()
	{
		// Set the MIME for the application based on format
		switch (strtolower($this->input->getWord('format', 'html')))
		{
			case 'json' :
				$this->mimeType = 'application/json';

				break;

			// Don't need to do anything for the default case
			default :
				break;
		}
	}
}
