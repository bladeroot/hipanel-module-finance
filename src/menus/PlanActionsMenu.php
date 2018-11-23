<?php

namespace hipanel\modules\finance\menus;

use Yii;

class PlanActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@plan/view', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => Yii::$app->user->can('plan.read'),
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@plan/update', 'id' => $this->model->id],
                'visible' => true,
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => Yii::$app->user->can('plan.update'),
            ],
        ];
    }
}
