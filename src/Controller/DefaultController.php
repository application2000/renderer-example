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
use Joomla\Renderer\RendererInterface;
use Joomla\View\ViewInterface;

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
		try
		{
			// Initialize the view object
			$view = $this->initializeView();

			// Render our view.
			$this->getApplication()->setBody($view->render());

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
	 * @return  RendererInterface  Renderer object
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeRenderer()
	{
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

		return $this->getContainer()->get('renderer');
	}

	/**
	 * Method to initialize the view object
	 *
	 * @return  ViewInterface  View object
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeView()
	{
		// Initialize the model object
		$model = $this->initializeModel();

		$view   = ucfirst($this->getInput()->getWord('view', $this->defaultView));
		$format = ucfirst($this->getInput()->getWord('format', 'html'));

		$class = '\\BabDev\\View\\' . $view . $format . 'View';

		// Ensure the class exists, fall back to default otherwise
		if (!class_exists($class))
		{
			$class = '\\BabDev\\View\\Default' . $format . 'View';

			// If we still have nothing, abort mission
			if (!class_exists($class))
			{
				throw new \RuntimeException(sprintf('A view class was not found for the %s view in the %s format.', $view, $format));
			}
		}

		// The view classes have different dependencies, switch it from here
		switch ($format)
		{
			case 'Json' :
				// We can just instantiate the view here
				$object = new $class($model);

				break;

			case 'Html' :
			default     :
				// HTML views require a renderer object too, fetch it
				$renderer = $this->initializeRenderer();

				// Instantiate the view now
				/* @type  \BabDev\View\AbstractHtmlView  $object */
				$object = new $class($model, $renderer);

				// We need to set the layout too
				$object->setLayout(strtolower($view) . '.' . strtolower($this->getInput()->getWord('layout', 'index')));

				break;
		}

		return $object;
	}
}
