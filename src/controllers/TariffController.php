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

use hipanel\actions\ComboSearchAction;
use hipanel\actions\IndexAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\models\Ref;
use hipanel\modules\finance\logic\DomainTariffManager;
use hipanel\modules\finance\logic\TariffManagerFactory;
use hipanel\modules\finance\models\CertificateResource;
use Yii;

class TariffController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'data' => function () {
                    return [
                        'types' => Ref::getList('type,tariff', 'hipanel:finance:tariff:types'),
                    ];
                },
            ],
            'search' => [
                'class' => ComboSearchAction::class,
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel', 'Note updated'),
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:finance:tariff', 'Tariff deleted'),
            ],
        ];
    }

    public function actionCreateDomain($parent_id = null)
    {
        /** @var DomainTariffManager $manager */
        $manager = TariffManagerFactory::createByType('domain', $parent_id);
        $form = $manager->form;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $manager->insert();
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('domain/create', ['model' => $form]);
    }

    public function actionCreateSvds($parent_id = null)
    {
        /** @var DomainTariffManager $manager */
        $manager = TariffManagerFactory::createByType('svds', $parent_id);

        $form = $manager->form;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $manager->insert();
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('vds/create', ['model' => $form]);
    }

    public function actionCreateOvds($parent_id = null)
    {
        /** @var DomainTariffManager $manager */
        $manager = TariffManagerFactory::createByType('ovds', $parent_id);
        $form = $manager->form;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $manager->insert();
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('vds/create', ['model' => $form]);
    }

    public function actionCreateCertificate($parent_id = null)
    {
        /** @var CertificateResource $manager */
        $manager = TariffManagerFactory::createByType('certificate', $parent_id);
        $form = $manager->form;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $manager->insert();
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('certificate/create', ['model' => $form]);
    }

    public function actionCreateServer($parent_id = null)
    {
        /** @var CertificateResource $manager */
        $manager = TariffManagerFactory::createByType('server', $parent_id);
        $form = $manager->form;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $manager->insert();
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('server/create', ['model' => $form]);
    }

    public function actionUpdate($id)
    {
        $manager = TariffManagerFactory::createById($id, ['scenario' => 'update']);
        $form = $manager->form;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $manager->update();
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render($manager->getType() . '/update', ['model' => $form]);
    }

    public function actionCopy()
    {
        $id = Yii::$app->request->post('selection')[0];
        $manager = TariffManagerFactory::createById($id, ['scenario' => 'create']);
        $form = $manager->form;

        return $this->render($manager->getType() . '/copy', ['model' => $form]);
    }

    public function actionView($id)
    {
        $manager = TariffManagerFactory::createById($id);

        return $this->render('view', ['manager' => $manager]);
    }
}
