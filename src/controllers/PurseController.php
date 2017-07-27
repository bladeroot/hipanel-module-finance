<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\modules\finance\models\Purse;
use hiqdev\hiart\ResponseErrorException;
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
            'update' => [
                'class' => SmartUpdateAction::class,
            ],
            'update-contact' => [
                'class' => SmartUpdateAction::class,
            ],
            'update-requisite' => [
                'class' => SmartUpdateAction::class,
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'invoice-archive' => [
                'class' => RedirectAction::class,
                'error' => Yii::t('hipanel', 'Under construction'),
            ],
            'generate-and-save-monthly-document' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:finance', 'Document updated'),
            ],
            'generate-and-save-document' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:finance', 'Document updated'),
            ],
        ];
    }

    public function actionGenerateMonthlyDocument($id, $type, $month = null)
    {
        return $this->generateDocument('generate-monthly-document', compact('id', 'type', 'month'));
    }

    public function actionGenerateDocument($id, $type)
    {
        return $this->generateDocument('generate-document', compact('id', 'type'));
    }

    public function generateDocument($action, $params)
    {
        try {
            $data = Purse::perform($action, $params);
        } catch (ResponseErrorException $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('hipanel:finance', 'Failed to generate document! Check requisites!'));

            return $this->redirect(['@client/view', 'id' => $params['id']]);
        }
        $this->asPdf();

        return $data;
    }

    protected function asPdf()
    {
        $response = Yii::$app->getResponse();
        $response->format = $response::FORMAT_RAW;
        $response->getHeaders()->add('content-type', 'application/pdf');
    }

    public function actionPreGenerateDocument($type)
    {
        $purse = new Purse(['scenario' => 'generate-and-save-monthly-document']);
        if ($purse->load(Yii::$app->request->post()) && $purse->validate()) {
            return $this->redirect([
                '@purse/generate-monthly-document',
                'id' => $purse->id,
                'type' => $type,
                'month' => $purse->month,
            ]);
        }
    }
}
