<?php

namespace backend\modules\api\controllers;

use common\models\Artigo;
use common\models\Favorito;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favorito';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            //'except' => ['index', 'view', 'create', 'remove'], //Excluir aos GETs
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function auth($username, $password)
    {   //ppt8 slide 11
        $user = \common\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication'); //403
    }

    public function actions()
    {
        $actions = parent::actions();

        // Se quisermos apagar açoes para o controlador nao as suportar
        // apagamos o index e o create para fazer uma implementaçao personalizada
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['delete']);

        return $actions;
    }

    public function actionIndex($id)
    {
        $response = [];
        $favoritos = Favorito::findAll(['perfil_id' => $id]);
        foreach ($favoritos as $favorito) {
            /*$artigo = Artigo::find()->where(['id' => $favorito->artigo_id])->one();*/
            $images = $favorito->artigo->imagens;

            // Adiciona os detalhes do artigo diretamente ao array de resposta
            $data = [
                'favorito' => [
                    'id' => $favorito->id,
                    'artigo' => [
                        'id' => $favorito->artigo->id,
                        'nome' => $favorito->artigo->nome,
                        'preco' => $favorito->artigo->preco,
                        'referencia' => $favorito->artigo->referencia,
                    ],
                ],
            ];
            foreach ($images as $image) {
                $image_binary = file_get_contents($image->image_path);

                $data['imagens'] [] = base64_encode($image_binary);
            }
            $response[] = $data;
        }
        return $response;
    }

    public function actionCreate()
    {
        try {
            $params = Yii::$app->getRequest()->getBodyParams();

            // Verifica se todos os parâmetros necessários foram enviados
            if (!isset($params['artigo_id'])) {
                throw new \Exception('Parâmetros inválidos');
            }

        } catch (\Exception $e) {
            return ["response" => $e->getMessage()];
        }

        // Verifica se o artigo já está nos favoritos do cliente
        $favorito = Favorito::findOne(['perfil_id' => $params['perfil_id'], 'artigo_id' => $params['artigo_id']]);
        if ($favorito) {
            throw new \Exception("Artigo já está nos favoritos");

        } else {

            // Cria uma nova instância de Favorito
            $favorito = new Favorito();
            $favorito->perfil_id = $params['perfil_id'];
            $favorito->artigo_id = $params['artigo_id'];
            $favorito->save();

            return ["response" => "Artigo adicionado aos favoritos"];
        }
    }

    public function actionRemove()
    {
        try {
            $params = Yii::$app->getRequest()->getBodyParams();
            // Verifica se todos os parâmetros necessários foram enviados
            if (!isset($params['artigo_id'])) {
                throw new \Exception('Parâmetros inválidos');
            }

        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }

        $params = Yii::$app->request->post();
        $favorito = Favorito::findOne(['perfil_id' => $params['perfil_id'], 'artigo_id' => $params['artigo_id']]);
        if ($favorito) {
            $favorito->delete();
            return ["response" => "Produto removido dos favoritos"];
        } else {
            return ["response" => "Produto não existe nos favoritos"];
        }
    }
}
