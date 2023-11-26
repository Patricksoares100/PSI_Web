<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "artigos".
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property string $referencia
 * @property float $preco
 * @property int $stock_atual
 * @property int $iva_id
 * @property int $fornecedor_id
 * @property int $categoria_id
 * @property int $perfil_id
 *
 * @property Avaliacaos[] $avaliacaos
 * @property Categorias $categoria
 * @property Fornecedores $fornecedor
 * @property Ivas $iva
 * @property LinhasCarrinho[] $linhasCarrinhos
 * @property LinhasFaturas[] $linhasFaturas
 * @property Perfis $perfil
 */
class Artigo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'artigos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'descricao', 'referencia', 'preco', 'stock_atual', 'iva_id', 'fornecedor_id', 'categoria_id', 'perfil_id'], 'required'],
            [['nome', 'descricao', 'referencia', 'preco', 'stock_atual'], 'trim'],
            ['nome', 'unique', 'targetClass' => '\common\models\Artigo', 'message' => 'Este nome já está a ser usado!'],
            ['referencia', 'unique', 'targetClass' => '\common\models\Artigo', 'message' => 'Esta referência já está a ser usada!'],
            [['preco'], 'number', 'message' => 'Preço não pode conter caracteres'],
            [['iva_id', 'fornecedor_id', 'categoria_id', 'perfil_id'], 'integer'],
            [['stock_atual'], 'integer','message' => 'Stock tem de ser um número inteiro'],
            [['nome', 'descricao', 'referencia'], 'string', 'max' => 255],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedor::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iva::class, 'targetAttribute' => ['iva_id' => 'id']],
            [['perfil_id'], 'exist', 'skipOnError' => true, 'targetClass' => Perfil::class, 'targetAttribute' => ['perfil_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'referencia' => 'Referência',
            'preco' => 'Preço',
            'stock_atual' => 'Quantidade em Stock',
            'iva_id' => 'Percentagem Iva',
            'fornecedor_id' => 'Fornecedor',
            'categoria_id' => 'Categoria',
            'perfil_id' => 'Funcionário',
            'perfil.nome' => 'Nome do Funcionário'
        ];
    }

    /**
     * Gets query for [[Avaliacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacaos()
    {
        return $this->hasMany(Avaliacaos::class, ['artigo_id' => 'id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Fornecedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFornecedor()
    {
        return $this->hasOne(Fornecedor::class, ['id' => 'fornecedor_id']);
    }

    /**
     * Gets query for [[Iva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIva()
    {
        return $this->hasOne(Iva::class, ['id' => 'iva_id']);
    }


    /**
     * Gets query for [[LinhasCarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasCarrinhos()
    {
        return $this->hasMany(LinhasCarrinho::class, ['artigo_id' => 'id']);
    }

    /**
     * Gets query for [[LinhasFaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasFaturas()
    {
        return $this->hasMany(LinhasFaturas::class, ['artigo_id' => 'id']);
    }

    /**
     * Gets query for [[Perfil]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerfil()
    {
        return $this->hasOne(Perfil::class, ['id' => 'perfil_id']);
    }

    public static function getNumeroArtigos(){

        return static::find()->count();
    }
}
