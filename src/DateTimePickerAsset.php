<?php
/**
 * @link http://faravaghi.ir
 * @copyright Copyright (c) 2017 faravaghi
 * @license MIT http://opensource.org/licenses/MIT
 * 
 * @copyright Copyright &copy; Mohammad Ebrahim Amini, faravaghi.ir, 2020
 * @package yii2-widgets
 * @subpackage yii2-persian-datetimepicker
 * @version 1.0.0
 */
namespace faravaghi\persianDatetimePicker;

use Yii;
use yii\web\AssetBundle;

/**
 * Asset bundle for Persian DateTimePicker Widget
 *
 * @author Mohammad Ebrahim Amini <info@faravaghi.ir>
 * @link https://www.faravaghi.ir/
 * @link https://www.dkr.co.ir/
 */
class DateTimePickerAsset extends AssetBundle
{
	public $sourcePath = '@vendor/faravaghi/yii2-persian-datetimepicker/assets'; 

	public $css = [
		'css/tempusdominus-bootstrap-4.min.css',
	];

	public $js = [
		'js/moment.min.js',
		'js/tempusdominus-bootstrap-4.min.js',
	];

	public $depends = [
		'yii\bootstrap4\BootstrapPluginAsset',
	];
}
