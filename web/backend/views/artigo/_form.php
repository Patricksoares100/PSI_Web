<?php

use common\models\Categoria;
use common\models\Fornecedor;
use common\models\Iva;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Artigo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="artigo-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'stock_atual')->textInput() ?>

    <?php

    $categoriaDropdown = [];
    $fornecedorDropdown = [];
    $ivaDropdown = [];

    foreach ($ivas as $iva) {
        if($iva->em_vigor == 'Sim') {
            $ivaDropdown[$iva->id] = $iva->percentagem;
        }
    }
    foreach ($fornecedores as $fornecedor) {
        $fornecedorDropdown[$fornecedor->id] = $fornecedor->nome;
    }
    foreach ($categorias as $categoria) {
        $categoriaDropdown[$categoria->id] = $categoria->nome;
    }
    ?>

    <?= $form->field($model, 'iva_id')->dropDownList($ivaDropdown, ['prompt' => 'Escolha uma taxa de Iva']) ?>

    <?= $form->field($model, 'fornecedor_id')->dropDownList($fornecedorDropdown, ['prompt' => 'Escolha um Fornecedor']) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList($categoriaDropdown, ['prompt' => 'Escolha uma Categoria']) ?>

    <?= $form->field($model, 'perfil_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>