<?php

namespace DotBlue\Templating\Helpers;

use InvalidArgumentException;


class Caller
{

	/** @var Registry */
	private $registry;



	public function __construct(Registry $registry)
	{
		$this->registry = $registry;
	}



	/**
	 * Allows calling registered helpers depending on locale.
	 *
	 * @param  mixed
	 * @param  callable
	 */
	public function callWithLocalization($locale, $callback)
	{
		$originalLocale = $this->registry->getLocale();
		$this->registry->setLocale($locale);
		call_user_func($callback, $this);
		$this->registry->setLocale($originalLocale);
	}



	/**
	 * Calls registered helper.
	 *
	 * @param  string
	 * @return mixed
	 */
	public function call($name)
	{
		$helper = $this->registry->loader($name);
		if ($helper === FALSE) {
			throw new InvalidArgumentException("Helper '$name' cannot be called manually because it's not registered.");
		}
		$args = func_get_args();
		array_shift($args);
		return call_user_func_array($helper, $args);
	}

}
