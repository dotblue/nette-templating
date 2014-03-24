<?php

namespace DotBlue\Templating\Helpers;

use Nette\Latte;


class Macros extends Latte\Macros\MacroSet
{

	/** @var int */
	private $instances = 0;



	public static function install(Latte\Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('locale', array($me, 'macroLocale'), array($me, 'macroLocaleEnd'));
	}



	public function macroLocale(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		$this->instances++;
		return $writer->write('if (!isset($_l->_locales)) { $_l->_locales = array(); } $_locale = %node.word; array_push($_l->_locales, $_locale); $_presenter->getContext()->getByType("DotBlue\Templating\Helpers\Registry")->setLocale($_locale); unset($_locale);');
	}



	public function macroLocaleEnd(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		if ($node->content != NULL) {
			return $writer->write('$_locale = array_pop($_l->_locales); $_presenter->getContext()->getByType("DotBlue\Templating\Helpers\Registry")->setLocale($_locale); unset($_locale);');
		}
	}



	public function finalize()
	{
		$epilog = $prolog = array();

		if ($this->instances > 0) {
			$prolog[] = '$_originalLocale = $_presenter->getContext()->getByType("DotBlue\Templating\Helpers\Registry")->getLocale();';

			$epilog[] = '$_presenter->getContext()->getByType("DotBlue\Templating\Helpers\Registry")->setLocale($_originalLocale);';
		}

		return array(implode("\n\n", $prolog), implode("\n", $epilog));
	}

}
