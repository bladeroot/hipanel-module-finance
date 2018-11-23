<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\grid;

use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\Url;
use Yii;

class TariffGridView extends \hipanel\grid\BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'tariff' => [
                'class' => 'hipanel\grid\MainColumn',
                'filterAttribute' => 'tariff_like',
                'note' => Yii::$app->user->can('tariff.update') ? 'note' : null,
                'noteOptions' => [
                    'url' => Url::to('set-note'),
                ],
            ],
            'used' => [
                'filter' => false,
            ],
            'type' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel:finance:tariff:types',
                'gtype' => 'type,tariff',
                'value' => function ($model) {
                    return Yii::t('hipanel:finance:tariff:types', $model->type);
                },
            ],
        ]);
    }
}
