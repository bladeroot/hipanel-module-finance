<?php

namespace hipanel\modules\finance\grid;

use hipanel\grid\MainColumn;
use hipanel\helpers\Url;
use Yii;

class PlanGridView extends \hipanel\grid\BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'class' => MainColumn::class,
                'note' => 'note',
                'noteOptions' => [
                    'url' => Url::to(['/finance/plan/set-note']),
                ],
            ],
        ]);
    }
}