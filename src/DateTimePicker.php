<?php
/**
 * @link http://faravaghi.ir
 * @copyright Copyright (c) 2017 faravaghi
 * @license MIT http://opensource.org/licenses/MIT
 */
namespace faravaghi\persianDatetimePicker;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use yii\web\View;

/**
 * DateTimePicker renders a DateTimePicker input.
 * @see https://tempusdominus.github.io/bootstrap-4
 *
 * @author Mohammad Ebrahim Amini <info@faravaghi.ir>
 * @link https://www.faravaghi.ir/
 * @link https://www.dkr.co.ir/
 */
class DateTimePicker extends InputWidget
{
	/**
	 * The markup to render the calendar icon in the date picker button.
	 */
	const CALENDAR_ICON = '<i class="fa fa-calendar"></i>';
	/**
	 * Datepicker rendered as a plain input.
	 */
	const TYPE_INPUT = 1;
	/**
	 * Datepicker with the date picker button rendered as a prepended bootstrap addon component
	 */
	const TYPE_COMPONENT_PREPEND = 2;
	/**
	 * Datepicker with the date picker button rendered as a appended bootstrap addon component
	 */
	const TYPE_COMPONENT_APPEND = 3;
	/**
	 * Datepicker calendar directly rendered inline
	 */
	const TYPE_INLINE = 4;
	/**
	 * Link defines minimum
	 */
	const LINK_MIN = 'min';
	/**
	 * Link defines maximum
	 */
	const LINK_MAX = 'max';

	/**
	 * @var string the markup type of widget markup must be one of the TYPE constants. Defaults to
	 * [[TYPE_COMPONENT_APPEND]]
	 */
	public $type = self::TYPE_COMPONENT_APPEND;
	/**
	 * @var string date, time or datetime ICU format. Alternatively this can be a string prefixed with `php:`
	 * representing a format that can be recognized by the PHP date()-function.
	 * Format also dictates what components are shown, e.g. MM/dd/yyyy will not display the time picker.
	 * @see http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax
	 */
	public $format;
	/**
	 * @var string id of the linked picker
	 */
	public $link;
	/**
	 * @var string defines if linked picker defines min or max value of this picker. Defaults to
	 * [[LINK_MIN]]
	 */
	public $linkType = self::LINK_MIN;
	/**
	 * @var array the options for the underlying JS plugin.
	 */
	public $clientOptions = [];
	/**
	 * @var array the event handlers for the underlying JS plugin.
	 */
	public $clientEvents = [];
	/**
	 * @var array the HTML attributes for the widget container tag.
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $options = [];
	/**
	 * @var array Input group addon options. This value is ignored when type equals [[TYPE_INPUT]] or [[TYPE_INLINE]]
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $inputGroupAddonOptions = [
		'data' => [
			'toggle' => 'datetimepicker'
		]
	];
	/**
	 * @var array The input group button options. This value is ignored when type equals
	 * [[TYPE_INPUT]] or [[TYPE_INLINE]]
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $buttonOptions = [
		'class' => ['input-group-text'],
	];
	/**
	 * @var array default client options
	 */
	private $_defaultClientOptions = [
		'stepping' => 5,
	];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		/**
		 * register translations
		 */
		if (!isset(\Yii::$app->get('i18n')->translations['datetime*'])) {
			\Yii::$app->get('i18n')->translations['datetime*'] = [
				'class'	=> 'yii\i18n\PhpMessageSource',
				'basePath' => __DIR__ . '/messages',
			];
		}

		if (!isset($this->format)) {
			$this->format = Yii::$app->formatter->dateFormat;
		}
		if ($this->hasModel()) {
			try {
				$this->model->{$this->attribute} = Yii::$app->formatter->asDatetime(
					$this->model->{$this->attribute},
					$this->format
				);
			}
			catch (InvalidArgumentException $e) {
				$this->model->{$this->attribute} = null;
			}
			if (false === strtotime($this->model->{$this->attribute})) {
				$this->model->{$this->attribute} = null;
			}
		}
		else {
			try {
				$this->value = Yii::$app->formatter->asDatetime($this->value, $this->format);
			}
			catch (InvalidArgumentException $e) {
				$this->value = null;
			}
			if (false === strtotime($this->value)) {
				$this->value = null;
			}
		}

		$this->_defaultClientOptions['tooltips'] = [
			'today' => \Yii::t('datetime.app', 'Go to today'),
			'clear' => \Yii::t('datetime.app', 'Clear selection'),
			'close' => \Yii::t('datetime.app', 'Close the picker'),
			'selectMonth' => \Yii::t('datetime.app', 'Select Month'),
			'prevMonth' => \Yii::t('datetime.app', 'Previous Month'),
			'nextMonth' => \Yii::t('datetime.app', 'Next Month'),
			'selectYear' => \Yii::t('datetime.app', 'Select Year'),
			'prevYear' => \Yii::t('datetime.app', 'Previous Year'),
			'nextYear' => \Yii::t('datetime.app', 'Next Year'),
			'selectDecade' => \Yii::t('datetime.app', 'Select Decade'),
			'prevDecade' => \Yii::t('datetime.app', 'Previous Decade'),
			'nextDecade' => \Yii::t('datetime.app', 'Next Decade'),
			'prevCentury' => \Yii::t('datetime.app', 'Previous Century'),
			'nextCentury' => \Yii::t('datetime.app', 'Next Century'),
			'incrementHour' => \Yii::t('datetime.app', 'Increment Hour'),
			'pickHour' => \Yii::t('datetime.app', 'Pick Hour'),
			'decrementHour' => \Yii::t('datetime.app', 'Decrement Hour'),
			'incrementMinute' => \Yii::t('datetime.app', 'Increment Minute'),
			'pickMinute' => \Yii::t('datetime.app', 'Pick Minute'),
			'decrementMinute' => \Yii::t('datetime.app', 'Decrement Minute'),
			'incrementSecond' => \Yii::t('datetime.app', 'Increment Second'),
			'pickSecond' => \Yii::t('datetime.app', 'Pick Second'),
			'decrementSecond' => \Yii::t('datetime.app', 'Decrement Second'),
			'togglePeriod' => \Yii::t('datetime.app', 'Toggle Period'),
			'selectTime' => \Yii::t('datetime.app', 'Select Time'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$this->registerPlugin();
		return $this->renderInput();
	}

	/**
	 * Renders the source input for the DatePicker plugin.
	 *
	 * @return string
	 */
	protected function renderInput()
	{
		$options = $this->options;
		$id = ArrayHelper::getValue($options, 'id');

		$options['data']['target'] = '#' . $id;
		$_ignoreReadonly = ArrayHelper::getValue($this->clientOptions, 'ignoreReadonly', false);

		if($_ignoreReadonly) {
			$options['readonly'] = 'readonly';
		}

		$tag = ArrayHelper::remove($inputGroupAddonOptions, 'tag', 'div');

		$inputGroupAddonOptions = $this->inputGroupAddonOptions;
		$inputGroupAddonOptions['data']['target'] = '#' . $id;
		$buttonOptions = $this->buttonOptions;
		$buttonIcon = ArrayHelper::remove($buttonOptions, 'icon', self::CALENDAR_ICON);
		Html::addCssClass($options, 'form-control');

		if ($this->type === self::TYPE_INPUT) {
			$options['id'] = $id;
			$options['data']['toggle'] = 'datetimepicker';
		}

		if ($this->hasModel()) {
			$input = Html::activeTextInput($this->model, $this->attribute, $options);
		}
		else {
			$input = Html::textInput($this->name, $this->value, $options);
		}

		switch ($this->type) {
			case self::TYPE_INPUT:
				return $input;

			case self::TYPE_COMPONENT_PREPEND:
				Html::addCssClass($inputGroupAddonOptions, 'input-group-prepend');
				$addon = Html::tag($tag, Html::tag('div', $buttonIcon, $buttonOptions), $inputGroupAddonOptions);
				return Html::tag('div', $addon . $input, [
					'class' => 'input-group',
				]);

			case self::TYPE_COMPONENT_APPEND:
			default:
				Html::addCssClass($inputGroupAddonOptions, 'input-group-append');
				$addon = Html::tag($tag, Html::tag('div', $buttonIcon, $buttonOptions), $inputGroupAddonOptions);
				return Html::tag('div', $input . $addon, [
					'class' => 'input-group',
				]);

			case self::TYPE_INLINE:
				$options['id'] = $id;
				if ($this->hasModel()) {
					$input = Html::activeHiddenInput($this->model, $this->attribute, $options);
				} else {
					$input = Html::hiddenInput($this->name, $this->value, $options);
				}

				return $input;
		}
	}

   /**
	 * {@inheritdoc}
	 */
	protected function registerPlugin()
	{
		$id = $this->options['id'];
		$view = $this->getView();

		DateTimePickerAsset::register($view);

		$js = [
			"window.ODate = Date;window.Date = pDate;",
			"$('#$id').on('dp.show', function () { var dtp = $(this); window.setTimeout(function () { dtp.trigger('change.datetimepicker'); }, 200); });",
			"$('#$id').datetimepicker({$this->getClientOptions()});"
		];

		if (!empty($this->link)) {
			$js[] = <<<JS
				$('#{$this->link}').on('change.datetimepicker', function (e) {
					if (!e.date) {
						return;
					}
					$('#$id').$pluginName('{$this->linkType}Date', e.date);
				});
			JS;
		}

		$view->registerJs(implode("\n", $js));
		$this->registerClientEvents($id);
	}

	/**
	 * Get client options as json encoded string
	 *
	 * @return string
	 */
	protected function getClientOptions()
	{
		if (!empty($this->link)) {
			$this->clientOptions['useCurrent'] = false;
		}

		if ($this->type === static::TYPE_INLINE) {
			$this->clientOptions['inline'] = true;
		}

		$options = ArrayHelper::merge($this->_defaultClientOptions, $this->clientOptions);
		return Json::encode($options);
	}

	/**
	 * Registers JS event handlers that are listed in [[clientEvents]].
	 */
	protected function registerClientEvents($id)
	{
		if (!empty($this->clientEvents)) {
			$js = [];

			foreach ($this->clientEvents as $event => $handler) {
				$js[] = "jQuery('#$id').on('$event', $handler);";
			}

			$this->view->registerJs(implode("\n", $js));
		}
	}
}
