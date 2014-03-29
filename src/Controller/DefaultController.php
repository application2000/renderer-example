<?php
/**
 * Application for working on the Joomla! RendererInterface
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Controller;

use Joomla\Controller\AbstractController;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Renderer;

/**
 * Default controller class for the application
 *
 * @since  1.0
 */
class DefaultController extends AbstractController implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * The default view for the application
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $defaultView = 'dashboard';

	/**
	 * State object to inject into the model
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  1.0
	 */
	protected $modelState = null;

	/**
	 * Execute the controller
	 *
	 * This is a generic method to execute and render a view and is not suitable for tasks
	 *
	 * @return  boolean  True if controller finished execution
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		// Initialize the model object
		$model = $this->initializeModel();

		// Initialize the renderer object
		$renderer = $this->initializeRenderer();

		$view   = $this->getInput()->getWord('view', $this->defaultView);
		$layout = $this->getInput()->getWord('layout', 'index');

		try
		{
			// Render our view.
			$this->getApplication()->setBody(
				$renderer->render($view . '.' . $layout . $this->getContainer()->get('config')->get('template.extension'), ['model' => $model])
			);

			return true;
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException(sprintf('Error: ' . $e->getMessage()), $e->getCode());
		}
	}

	/**
	 * Method to initialize the model object
	 *
	 * @return  \Joomla\Model\ModelInterface
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeModel()
	{
		$model = '\\BabDev\\Model\\' . ucfirst($this->getInput()->getWord('view')) . 'Model';

		// If a model doesn't exist for our view, revert to the default model
		if (!class_exists($model))
		{
			$model = '\\BabDev\\Model\\DefaultModel';

			// If there still isn't a class, panic.
			if (!class_exists($model))
			{
				throw new \RuntimeException(sprintf('No model found for view %s', $vName), 500);
			}
		}

		return new $model($this->modelState);
	}


	/**
	 * Method to initialize the renderer object
	 *
	 * @return  mixed  Rendering class based on the request format
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeRenderer()
	{
		// Fetch the renderer based on format and application config
		switch (strtolower($this->getInput()->getWord('format', 'html')))
		{
			case 'json' :
				$view  = ucfirst($this->getInput()->getWord('view', $this->defaultView));
				$class = '\\BabDev\\View\\' . ucfirst($view) . 'JsonView';

				// Make sure the view class exists, otherwise revert to the default
				if (!class_exists($class))
				{
					throw new \RuntimeException('A view class was not found for the JSON format.', 500);
				}

				$renderer = new $class;

				break;

			case 'html' :
			default :
				$type = $this->getContainer()->get('config')->get('template.renderer');

				// Set the class name for the renderer's service provider
				$class = '\\BabDev\\Service\\' . ucfirst($type) . 'RendererProvider';

				// Sanity check
				if (!class_exists($class))
				{
					throw new \RuntimeException(sprintf('Renderer provider for renderer type %s not found.', ucfirst($type)));
				}

				// Add the provider to the DI container
				$this->getContainer()->registerServiceProvider(new $class);

				$renderer = $this->getContainer()->get('renderer');

				break;
		}

		return $renderer;
	}
}
