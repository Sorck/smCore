<?php

namespace smCore;

use Twig_Extension, Twig_Function_Function, Twig_Filter_Function;

class TwigExtension extends Twig_Extension
{
	public function getFunctions()
	{
		return array(
			'lang' => new Twig_Function_Function(__CLASS__ . '::function_lang', array(
				'is_safe' => array('html'),
			)),
		);
	}

	public function getFilters()
	{
		return array(
			'split' => new Twig_Filter_Function(__CLASS__ . '::filter_split'),
		);
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'smCore';
	}

	public static function function_lang()
	{
		$args = func_get_args();

		if (count($args) < 1)
			return '';

		return Application::get('lang')->get($args[0], array_slice($args, 1));
	}

	/**
	 * Splits a value into an array.
	 *
	 * <pre>
	 *  {{ "one,two,three"|split(',') }}
	 *  {# returns array("one", "two", "three") #}
	 *
	 *  {{ "one,two,three,four,five"|split(',', 3) }}
	 *  {# returns array("one", "two", "three,four,five") #}
	 * </pre>
	 *
	 * @author Tyler King (https://github.com/aibot)
	 *
	 * @param string  $value     A value to split
	 * @param string  $delimiter The delimiter to split with
	 * @param int     $limit     Optional limit
	 *
	 * @return string The split string
	 */
	public static function filter_split($value, $delimiter, $limit = null)
	{
		if (strlen($delimiter) < 1)
			throw new Twig_Error_Runtime();

		if (null === $limit)
			return explode($delimiter, $value);

		return explode($delimiter, $value, $limit);
	}
}