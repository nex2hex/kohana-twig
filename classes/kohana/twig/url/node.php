<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Parses a {% url %} tag
 *
 * @package kohana-twig
 * @author Jonathan Geiger
 */
class Kohana_Twig_Url_Node extends Twig_Node
{
	/**
	 * Compiles the tag
	 *
	 * @param object $compiler 
	 * @return void
	 * @author Jonathan Geiger
	 */
	public function compile(Twig_Compiler $compiler)
	{
		$params = $this->getNode('params');

		$urlconf = $this->getNode('urlconf');

		if ($params)
		{
			$compiler
				->write('$route_params = ')
				->subcompile($params)
				->raw(";\n");
		}
		else
		{
			$compiler
				->write('$route_params = array()')
				->raw(";\n");
		}

		// Output the route
		$compiler
			->write('echo Route::url(')
			->subcompile($this->getNode('route'))
			->write(', $route_params');

		if ($urlconf)
		{
			$compiler
				->write(', NULL, ')
				->subcompile($urlconf);
		}
		$compiler->raw(");\n");
	}
}
