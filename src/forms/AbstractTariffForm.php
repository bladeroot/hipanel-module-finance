<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\forms;

use hipanel\modules\finance\logic\Calculator;
use hipanel\modules\finance\models\Tariff;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

abstract class AbstractTariffForm extends \yii\base\Model
{
    /**
     * @var int Tariff ID
     */
    public $id;

    /**
     * @var string Tariff name
     */
    public $name;

    /**
     * @var int Parent tariff ID
     */
    public $parent_id;

    /**
     * @var Tariff the selected parent tariff
     */
    public $parentTariff;

    /**
     * @var Tariff
     */
    protected $tariff;

    /**
     * @var \hipanel\modules\finance\models\Resource[]
     */
    protected $_resources;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->initTariff();
    }

    /**
     * Initializes tariff.
     * @void
     */
    protected function initTariff()
    {
        if ($this->ensureTariff()) {
            $this->ensureScenario();
        }
    }

    /**
     * Ensures that [[tariff]] is set.
     * Otherwise calls [[setDefaultTariff()]].
     * @return bool
     */
    protected function ensureTariff()
    {
        if ($this->getTariff() instanceof Tariff) {
            return true;
        }

        return $this->setDefaultTariff();
    }

    protected function ensureScenario()
    {
        foreach ($this->tariff->resources as $resource) {
            $resource->scenario = $this->scenario;
        }
    }

    /**
     * Sets default tariff.
     *
     * @return bool success
     */
    protected function setDefaultTariff()
    {
        if (!$this->setTariff($this->parentTariff)) {
            return false;
        }

        // Default tariff's id and name are useless on create
        $this->id = null;
        $this->name = null;

        return true;
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['name'], 'required', 'on' => ['create', 'update']],
            [['parent_id', 'id'], 'integer', 'on' => ['create', 'update']],
            'parent-id-required' => [['parent_id'], 'required', 'on' => ['create']],
            [['id'], 'required', 'on' => ['update']],
        ];
    }

    /** {@inheritdoc} */
    public function fields()
    {
        return ArrayHelper::merge(array_combine($this->attributes(), $this->attributes()), [
            'resources' => '_resources',
        ]);
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return [
            'id',
            'parent_id',
            'name',
        ];
    }

    /**
     * @return \hipanel\modules\finance\models\Resource[]
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * @param \hipanel\modules\finance\models\Resource[] $resources
     * @throws InvalidConfigException when not implemented
     */
    public function setResources($resources)
    {
        throw new InvalidConfigException('Method "setResources" must be implemented');
    }

    /**
     * @return array
     */
    public function getResourceTypes(): array
    {
        $res = $this->parentTariff->resources;

        return $res ? reset($res)->getTypes() : [];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => Yii::t('hipanel:finance:tariff', 'Parent tariff'),
            'name' => Yii::t('hipanel:finance:tariff', 'Name'),
            'label' => Yii::t('hipanel:finance:tariff', 'Label'),
            'note' => Yii::t('hipanel', 'Note'),
        ];
    }

    /**
     * @param array $data to be loaded
     * @param null $formName
     * @throws InvalidConfigException when not implemented
     * @return bool
     */
    public function load($data, $formName = null)
    {
        throw new InvalidConfigException('Method load must be implemented');
    }

    public function insert($runValidation = true)
    {
        throw new InvalidConfigException('Method insert must be implemented');
    }

    public function update($runValidation = true)
    {
        throw new InvalidConfigException('Method update must be implemented');
    }

    /**
     * @return Tariff
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * Sets [[tariff]].
     *
     * @param Tariff $tariff
     * @return bool
     */
    public function setTariff($tariff)
    {
        if ($tariff === null) {
            return false;
        }

        $this->tariff = $tariff;

        $this->id = $tariff->id;
        $this->name = $tariff->name;

        return true;
    }

    public function getPrimaryKey()
    {
        return ['id'];
    }

    /**
     * @var Calculator
     */
    protected $_calculator;

    /**
     * Creates [[TariffCalculator]] object for the [[tariff]].
     *
     * @return Calculator
     */
    protected function calculator()
    {
        if (!isset($this->_calculator)) {
            $this->_calculator = new Calculator([$this->tariff]);
        }

        return $this->_calculator;
    }

    /**
     * @return \hipanel\modules\finance\models\Value
     */
    public function calculation()
    {
        return $this->calculator()->getCalculation($this->tariff->id)->forCurrency($this->tariff->currency);
    }

    /**
     * @var Calculator
     */
    protected $_parentCalculator;

    /**
     * Creates [[TariffCalculator]] object for the [[parentTariff]].
     *
     * @return Calculator
     */
    protected function parentCalculator()
    {
        if (!isset($this->_parentCalculator)) {
            $this->_parentCalculator = new Calculator([$this->parentTariff]);
        }

        return $this->_parentCalculator;
    }

    /**
     * @return \hipanel\modules\finance\models\Value
     */
    public function parentCalculation()
    {
        return $this->parentCalculator()->getCalculation($this->parentTariff->id)->forCurrency($this->parentTariff->currency);
    }
}
