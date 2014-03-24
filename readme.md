## Localizable templates with smooth setup in Nette Framework

#### Requirements

- PHP 5.4+
- [nette/nette](https://github.com/nette/nette) >= 2.1

## Installation

1.  Copy source codes from Github or using [Composer](http://getcomposer.org/):
```sh
$ composer require dotblue/nette-templating@~1.0
```

2.  Register as Configurator's extension:
```
extensions:
    templateHelpers: DotBlue\Templating\Helpers\Extension
```

3.  Register your helpers:
```
templateHelpers:
    - ShoutHelper
```

## How should `MyHelper` look?

```php
use DotBlue\Templating\Helpers;

class ShoutHelper implements Helpers\IHelper
{

    public function getName()
    {
        return 'shout';
    }

    public function execute($value, Helpers\Options $options)
    {
        return $value . '!';
    }

}
```

All arguments are wrapped in `Options` object.

```php
public function execute($value, Helpers\Options $options)
{
    $mark = $options->first('!');
    return $value . $mark;
}
```

```html
{var $text = 'Hi'}
{$text|shout:'!!!'} {* print "Hi!!!" *}
```

## Localization

You can use new macro `{locale $language /}` in your templates (best place is your layout). Provided `$language` variable is then available in helper via `Options` object:

```php
$language = $options->getLocale();
```

`$language` can be anything you wish. You can also place `{locale}` macro in template multiple times, for example to print amount of money in all localized versions (using hypothetical `currency` helper):

```html
{foreach $languages as $language}
    {locale $language}
        {$money|currency}
    {/locale}
{/foreach}
```
