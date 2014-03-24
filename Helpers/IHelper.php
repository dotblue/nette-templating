<?php

namespace DotBlue\Templating\Helpers;


interface IHelper
{

	/**
	 * Should name of the helper.
	 *
	 * @return string
	 */
	function getName();



	/**
	 * Should contain specific implementation of the helper.
	 *
	 * @param  mixed
	 * @param  Options
	 * @return mixed
	 */
	function execute($value, Options $options);

}
