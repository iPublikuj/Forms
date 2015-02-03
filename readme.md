# Forms

[![Build Status](https://img.shields.io/travis/iPublikuj/forms.svg?style=flat-square)](https://travis-ci.org/iPublikuj/forms)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/forms.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/forms/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/forms.svg?style=flat-square)](https://packagist.org/packages/ipub/forms)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/forms.svg?style=flat-square)](https://packagist.org/packages/ipub/forms)

This extension extend classic forms with forms factories, so forms could be created in better way under [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/forms is using  [Composer](http://getcomposer.org/):

```json
{
	"require": {
		"ipub/forms": "dev-master"
	}
}
```

or

```sh
$ composer require ipub/forms:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	forms: IPub\Forms\DI\FormsExtension
```