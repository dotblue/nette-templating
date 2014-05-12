<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Templating\Helpers;

use Nette\DI;


class Extension extends DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$helpers = $container->addDefinition($this->prefix('registry'))
			->setClass('DotBlue\Templating\Helpers\Registry');

		$container->addDefinition($this->prefix('caller'))
			->setClass('DotBlue\Templating\Helpers\Caller')
			->setFactory($this->prefix('@registry') . '::getCaller');

		$latte = $container->getDefinition('nette.latteFactory');
		foreach ($config as $name => $helper) {
			$this->compiler->parseServices($container, [
				'services' => [
					$this->prefix($name) => $helper,
				],
			]);
			$helpers->addSetup('addHelper', [
				$this->prefix('@' . $name),
			]);
			$latte->addSetup('$service->addFilter(?->getName(), ?->loader(?->getName()))', [
				$this->prefix('@' . $name),
				$this->prefix('@registry'),
				$this->prefix('@' . $name),
			]);
		}

		$latte->addSetup('DotBlue\Templating\Helpers\Macros::install(?->getCompiler())', array('@self'));
	}

}
