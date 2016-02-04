<?php

/*
 * Finance Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\modules\finance\models\Purse;
use Yii;

class PurseController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'invoice-archive' => [
                'class' => RedirectAction::class,
                'error' => Yii::t('app', 'Under construction'),
            ],
            'update-monthly-invoice' => [
                'class'   => SmartPerformAction::class,
                'success' => Yii::t('app', 'Invoice updated'),
            ],
        ];
    }

    public function actionGenerateInvoice($id, $month = null)
    {
        $content_type = 'application/pdf';
        $data = Purse::perform('GenerateMonthlyInvoice', compact('id', 'month'));
        $response = Yii::$app->getResponse();
        $response->format = $response::FORMAT_RAW;
        $response->getHeaders()->add('content-type', $content_type);

        return $data;
    }
}
