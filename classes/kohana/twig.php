<?php defined('SYSPATH') or die('No direct script access.');

// Load the Twig class autoloader
require DOCROOT.'../../vendor/twig-1.6/lib/Twig/Autoloader.php';

// Register the Twig class autoloader
Twig_Autoloader::register();

/**
 * Class for managing Twig contexts as arrays
 *
 * @package kohana-twig
 * @author Jonathan Geiger
 */
class Kohana_Twig extends Kohana_View {

	/**
	 * Factory for Twigs
	 *
	 * @param string $file 
	 * @param string $data 
	 * @param string $env 
	 * @return void
	 * @author Jonathan Geiger
	 */
	public static function factory($file = NULL, array $data = NULL,
		$env = 'default')
	{
		return new Twig($file, $data, $env);
	}

	/**
	 * @var string The environment the view is attached to
	 */
	protected $_environment;

	/**
	 * Constructor
	 *
	 * @param array $data 
	 * @author Jonathan Geiger
	 */
	public function __construct($file = NULL, $data = NULL, $env = 'default')
	{
		parent::__construct($file, $data);

		// Allow passing a Twig_Environment
		if (is_string($env))
		{	
			$env = Kohana_Twig_Environment::instance($env);
		}

		$this->_environment = $env;
	}
	
	/**
	 * Sets the view filename.
	 *
	 * @throws  View_Exception
	 * @param   string  filename
	 * @return  View
	 */
	public function set_filename($file)
	{
		// Store the file path locally
		$this->_file = $file;

		return $this;
	}

	/**
	 * Returns the environment this view is attached to
	 *
	 * @return Twig_Environment
	 * @author Jonathan Geiger
	 */
	public function environment()
	{
		return $this->_environment;
	}

	/**
	 * Renders the view object to a string. Global and local data are merged
	 * and extracted to create local variables within the view file.
	 *
	 * Note: Global variables with the same key name as local variables will be
	 * overwritten by the local variable.
	 *
	 * @throws   View_Exception
	 * @param    view filename
	 * @return   string
	 */
	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
		}

		$template = $this->_environment->loadTemplate($this->_file);

		// Combine local and global data and capture the output
		return $template->render($this->_data + self::$_global_data);
	}
}
