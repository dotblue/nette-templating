<?php

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

		foreach ($config as $name => $helper) {
			$this->compiler->parseServices($container, [
				'services' => [
					$this->prefix($name) => $helper,
				],
			]);
			$helpers->addSetup('addHelper', [
				$this->prefix('@' . $name),
			]);
		}

		$latte = $container->getDefinition('nette.latte');
		$latte->addSetup('DotBlue\Templating\Helpers\Macros::install(?->compiler)', array('@self'));
	}

}
