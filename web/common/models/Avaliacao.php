<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "avaliacaos".
 *
 * @property int $id
 * @property string $comentario
 * @property string|null $classificacao
 * @property int $artigo_id
 * @property int $perfil_id
 *
 * @property Artigo $artigo
 * @property Perfi $perfil
 */
class Avaliacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avaliacaos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comentario', 'artigo_id', 'perfil_id'], 'required'],
            [['classificacao'], 'string'],
            [['artigo_id', 'perfil_id'], 'integer'],
            [['comentario'], 'string', 'max' => 255],
            [['artigo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artigo::class, 'targetAttribute' => ['artigo_id' => 'id']],
            [['perfil_id'], 'exist', 'skipOnError' => true, 'targetClass' => Perfi::class, 'targetAttribute' => ['perfil_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comentario' => 'Comentario',
            'classificacao' => 'Classificacao',
            'artigo_id' => 'Artigo ID',
            'perfil_id' => 'Perfil ID',
        ];
    }

    /**
     * Gets query for [[Artigo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArtigo()
    {
        return $this->hasOne(Artigo::class, ['id' => 'artigo_id']);
    }

    /**
     * Gets query for [[Perfil]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerfil()
    {
        return $this->hasOne(Perfi::class, ['id' => 'perfil_id']);
    }
}
