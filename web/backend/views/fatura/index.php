<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Faturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fatura-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fatura', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'data',
            'valor_fatura',
            'estado' => [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $buttonColorClass = ($model->estado == 'Emitida') ? 'btn btn-danger' : 'btn btn-success'; // ver se esta ativo ou não e dar uma cor

                    return Html::a(
                        $model->estado,
                        ['fatura/atualizarstatus', 'id' => $model->id],
                        ['class' => $buttonColorClass]
                    );
                },
            ],
            'perfil_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Fatura $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },


            ],
        ],
    ]); ?>


</div>