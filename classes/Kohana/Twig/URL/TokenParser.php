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
		$expressionParser = $this->parser->getExpressionParser();

		// Find the route we're matching
		$opts = array('route' => $expressionParser->parseExpression());
		
		// Check for arguments for the route
		if ($stream->test(Twig_Token::PUNCTUATION_TYPE, ','))
		{
			$stream->expect(Twig_Token::PUNCTUATION_TYPE, ',');
			$opts['params'] = $expressionParser->parseExpression();

			if ($stream->test(Twig_Token::PUNCTUATION_TYPE, ','))
			{
				$stream->expect(Twig_Token::PUNCTUATION_TYPE, ',');
				$opts['urlconf'] = $expressionParser->parseExpression();
			}
		}
				
		$stream->expect(Twig_Token::BLOCK_END_TYPE);

		return new Kohana_Twig_URL_Node($opts, array(), $lineno, $this->getTag());
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
