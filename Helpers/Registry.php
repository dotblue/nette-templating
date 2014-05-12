<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Templating\Helpers;

use InvalidArgumentException;
use Nette;


class Registry
{

	/** @var IHelper[] */
	private $helpers;

	/** @var Caller */
	private $caller;

	/** @var mixed|NULL */
	private $locale;



	/**
	 * @return mixed|NULL
	 */
	public function getLocale()
	{
		return $this->locale;
	}



	/**
	 * @param  mixed|NULL
	 */
	public function setLocale($locale = NULL)
	{
		$this->locale = $locale;
	}



	/**
	 * @return Caller
	 */
	public function getCaller()
	{
		if (!isset($this->caller)) {
			$this->caller = new Caller($this);
		}
		return $this->caller;
	}



	public function addHelper(IHelper $helper)
	{
		$name = $this->normalizeName($helper->getName());
		if ($this->isRegisteredGlobally($name)) {
			throw new InvalidArgumentException("Helper with name '$name' is already registered.");
		}
		$this->helpers[$name] = $helper;
	}



	/**
	 * @param  string
	 * @return callable|FALSE
	 */
	public function loader($name)
	{
		$name = $this->normalizeName($name);
		if ($this->isRegisteredLocally($name)) {
			return $this->createCallback($name);
		}
		return FALSE;
	}



	/**
	 * @param  string
	 * @return callable
	 */
	public function createCallback($name)
	{
		return function ($value) use ($name) {
			$args = func_get_args();
			array_shift($args);
			$options = new Options($name, $args, $this->getCaller());
			if ($this->locale) {
				$options->setLocale($this->locale);
			}
			return call_user_func(
				array($this->helpers[$name], 'execute'),
				$value,
				$options
			);
		};
	}



	/**
	 * @internal
	 */
	public function setCaller(Caller $caller)
	{
		$this->caller = $caller;
	}



	private function isRegisteredLocally($name)
	{
		return isset($this->helpers[$name]);
	}



	private function isRegisteredGlobally($name)
	{
		return $this->isRegisteredLocally($name) || Nette\Templating\Helpers::loader($name) !== NULL;
	}



	private function normalizeName($name)
	{
		return strtolower($name);
	}

}
