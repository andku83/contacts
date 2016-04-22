<?php

namespace app\controllers;

use app\models\Phone;
use Yii;
use app\models\Contact;
use app\models\ContactSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends Controller
{
    const MAX_PHONES = 10;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contact model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contact();
        $phones = $this->initValues($model);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save() && Model::loadMultiple($phones, $post)) {
            $this->processValues($phones, $model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'phones' => $phones,
            ]);
        }
    }

    /**
     * Updates an existing Contact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $phones = $this->initValues($model);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save() && Model::loadMultiple($phones, $post)) {
            $this->processValues($phones, $model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'phones' => $phones,
            ]);
        }
    }

    /**
     * Deletes an existing Contact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param Contact $model
     * @return Phone[]
     */
    private function initValues(Contact $model)
    {
        /** @var Phone[] $values */
        $phones = $model->getPhones()/*->indexBy('id')*/->all();
        $i = 0;
        foreach ($phones as $phone) {
            $i++;
        }
        for (;$i<self::MAX_PHONES;$i++){
            $phones[] = new Phone();
        }
        foreach ($phones as $phone) {
            $phone->setScenario(Phone::SCENARIO_TABULAR);
        }
        return $phones;
    }

    /**
     * @param Phone[] $phones
     * @param Contact $model
     */
    private function processValues($phones, Contact $model)
    {
        foreach ($phones as $phone) {
            $phone->contact_id = $model->id;
            if ($phone->validate()) {
                if (!empty($phone->phone)) {
                    $phone->save(false);
                } else {
                    $phone->delete();
                }
            }
        }
    }
}
