<?php

namespace hipanel\modules\finance\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class TariffRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $columns = Yii::$app->user->can('plan.force-read') ? [
            'checkbox',
            'tariff',
            'used',
            'type',
            'client_id',
        ] : [
            'tariff',
            'used',
            'client_id',
        ];
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => $columns,
            ],
        ]);
    }
}
