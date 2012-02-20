<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Parses a {% url %} tag
 *
 * @package kohana-twig
 * @author Jonathan Geiger
 */
class Kohana_Twig_URL_TokenParser extends Twig_TokenParser
{
	/**
	 * @param Twig_Token $token 
	 * @return object
	 * @author Jonathan Geiger
	 */
	public function parse(Twig_Token $token)
	{
		$lineno = $token->getLine();
		$stream = $this->parser->getStream();

		// Find the route we're matching
		$route = $this->parser->getExpressionParser()->parseExpression();
		$params = FALSE;
		$urlconf = FALSE;

		// Check for arguments for the route
		if ($stream->test(Twig_Token::PUNCTUATION_TYPE, ','))
		{
			$stream->expect(Twig_Token::PUNCTUATION_TYPE, ',');
			$params = $this->parser->getExpressionParser()->parseExpression();

			if ($stream->test(Twig_Token::PUNCTUATION_TYPE, ','))
			{
				$stream->expect(Twig_Token::PUNCTUATION_TYPE, ',');
				$urlconf = $this->parser->getExpressionParser()->parseExpression();
			}
		}
				
		$stream->expect(Twig_Token::BLOCK_END_TYPE);

		$opt = array('route' => $route, 'params' => $params, 'urlconf' => $urlconf);

		return new Kohana_Twig_URL_Node($opt, array(), $lineno, $this->getTag());
	}
	
	/**
	 * @return string
	 * @author Jonathan Geiger
	 */
	public function getTag()
	{
		return 'url';
	}
}
