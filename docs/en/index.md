# Forms

Add ability to create forms in better way in [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/forms is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/forms
```

After that you have to register extension in config.neon.

```neon
extensions:
	extendedForms: IPub\Forms\DI\FormsExtension
```

## Usage

This extension brings you a factory for creating forms, even forms filled from doctrine entity.

In place where you want to create your form, just inject this form factory

```php
class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @inject
     * @var \IPub\Forms\IFormFactory
     */
    public $formFactory;

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentUserAccountForm() : \Nette\Application\UI\Form
    {
        // Create form with factory
        $form = $this->formFactory->create(\Nette\Application\UI\Form::class);

        // Now you can add form elements like in classic nette way
        $form->addText('username', 'Username')
            ->setRequired();

        $form->addPassword('password', 'Password')
            ->setRequired();

        $form->addText('name', 'Name');

        // ...
        
        return $form;
    }
}
```

And that's it. Nothing more you need to do. This factory will create new instance of your form. Of course for this type of forms it will be not much useful. So lets take a look on advances example:

```php
cass MySuperForm
{
    public function __construct(UserEntity $user, SomeService $someService, OtherService $otherService)
    {
        // ....
    }
}

class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @inject
     * @var \IPub\Forms\IFormFactory
     */
    public $formFactory;

    /**
     * @inject
     * @var UserRepository
     */
    public $userRepository;

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentUserAccountForm() : \Nette\Application\UI\Form
    {
        $user = $this->repository->findLoggedIn();

        // Create form with factory
        $form = $this->formFactory->create(MySuperForm::class, $user);

        // Now you can add form elements like in classic nette way
        $form->addText('username', 'Username')
            ->setRequired();

        $form->addPassword('password', 'Password')
            ->setRequired();

        $form->addText('name', 'Name');

        // ...
        
        return $form;
    }
}
```

And as you can seen, now our form is dependent on some variables and services. Variable have to be pass to factory and the registered services will be autowired automatically.

## Extended forms

This extension has two classes for creating forms. This classes extend from basic nette form `\Nette\Application\UI\Form\`.

### Class EntityForm

If you are using Doctrine entities, now you do not need to transform them to the arrays if you want to fill created form with entity values.
Just create new EntityForm and pass your entity:

```php
class MySuperForm extends \IPub\Forms\Forms\EntityForm {

}

$form = $this->formFactory->create(MySuperForm::class, $user);
$form->setDefaults($userEntity);
```

### Class Form

This is classic form like from nette but with little sugar around :]

Have you ever wonder how to easy mark all required fields? This class add some special class names to fields and labels.

* **ipub-field-required** for required fields
* **ipub-field-error** for fields with validation error

And in case you want to add your custom class for error you could do it this way:

```php
$form = $this->formFactory->create(\IPub\Forms\Forms\Form::class, $user);
$form->setErrorClass('custom-error-class');
```

or even in template:

```html
// ...
{php $form->setErrorClass('custom-error-class')}

<form n:name="formName">
    //...
</form>
```

## Latte bonus

You know in Nette isn't support for form buttons with captions. So as a bonus this extension gives you new macro for creating buttons like other form elements

```html

<form n:name="formName">
    {label username} {input username}

    {button submit}{caption}{/button}
</form>
```

Code above will generate:

```html
<form>
    <label>Username</label> <input type="text" name="username">
    
    <button name="submit">Send</button>
</form>
```
