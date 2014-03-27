<?php

namespace DotBlue\Templating\Helpers;


class Options
{

	/** @var string */
	private $name;

	/** @var array */
	private $options;

	/** @var mixed */
	private $locale;

	/** @var Caller */
	private $caller;



	public function __construct($name, array $options, Caller $caller)
	{
		$this->name = $name;
		$this->options = array_values($options);
		$this->caller = $caller;
	}



	/**
	 * Returns first argument or provided default value.
	 *
	 * @param  mixed|NULL
	 * @return mixed|NULL
	 */
	public function first($default = NULL)
	{
		return $this->getValueOrDefault(0, $default);
	}



	/**
	 * Returns second argument or provided default value.
	 *
	 * @param  mixed|NULL
	 * @return mixed|NULL
	 */
	public function second($default = NULL)
	{
		return $this->getValueOrDefault(1, $default);
	}



	/**
	 * Returns third argument or provided default value.
	 *
	 * @param  mixed|NULL
	 * @return mixed|NULL
	 */
	public function third($default = NULL)
	{
		return $this->getValueOrDefault(2, $default);
	}



	/**
	 * Returns all arguments.
	 *
	 * @param  array
	 */
	public function all()
	{
		return $this->options;
	}



	/**
	 * @return Caller
	 */
	public function getCaller()
	{
		return $this->caller;
	}



	/**
	 * @return mixed|NULL
	 */
	public function getLocale()
	{
		if (!isset($this->locale)) {
			throw new UnknownLocaleException("Locale isn't set for use of |{$this->name} helper; please use {locale} macro in your template.");
		}
		return $this->locale;
	}



	/**
	 * @internal
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
	}



	private function getValueOrDefault($index, $default)
	{
		return array_key_exists($index, $this->options) ? $this->options[$index] : $default;
	}

}

class UnknownLocaleException extends \Exception {}
