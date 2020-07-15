# yii2-persian-datetimepicker
Jalali Datetime Picker for Bootstrap Yii2 Extension with Tempus Dominus Bootstrap 4 Datetime Picker 

This extension provides a `date`, `time` or `datetime` picker widget for yii2 framework in **Bootstrap 4** style. It's based 
on [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/).
 
## Resources
 * [yii2](https://github.com/yiisoft/yii2) framework
 * [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/)
 
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ composer require --prefer-dist faravaghi/yii2-persian-datetimepicker
```

or add 

```
"faravaghi/yii2-persian-datetimepicker": "~1.0"
```

to the ```require``` section of your `composer.json`

## Example Usage

To include datepicker instance in one of your pages, call the widget like this:
```php
<?php

use faravaghi\persianDatetimePicker\DateTimePicker;

echo $form->field(
	$model,
	'birthday'
)
->widget(DateTimePicker::class, [
	'format' => 'mm/dd/yyyy',
	'type'   => DateTimePicker::TYPE_COMPONENT_APPEND
]);

?>

```

## License

**yii2-persian-datetimepicker** is released under MIT license. See bundled [LICENSE](LICENSE) for details.
