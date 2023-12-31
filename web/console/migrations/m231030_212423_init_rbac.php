<?php

use yii\db\Migration;
use console\models\AuthorDadosPessoaisRule;
use console\models\AlterarPasswordRule;
use console\models\PermissoesProprioClienteRule;
use console\models\DeleteRuleProprioCliente;
use console\models\UpdateRuleProprioCliente;
/**
 * Class m231030_212423_init_rbac
 */
class m231030_212423_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // The authManager can now be accessed via \Yii::$app->authManager.
        $auth = Yii::$app->authManager;

        $rule = new AuthorDadosPessoaisRule;
        $auth->add($rule);
        $rule = new AlterarPasswordRule;
        $auth->add($rule);
        $rule = new PermissoesProprioClienteRule;
        $auth->add($rule);
        $rule = new DeleteRuleProprioCliente;
        $auth->add($rule);
        $rule = new UpdateRuleProprioCliente;
        $auth->add($rule);

        // Criar os roles - $auth->createRole();
        $role_admin = $auth->createRole('Admin');
        $role_admin->description = 'Role de Administrador';
        $auth->add($role_admin);

        $role_funcionario = $auth->createRole("Funcionario");
        $role_funcionario->description = 'Role de Funcionario';
        $auth->add($role_funcionario);

        $role_cliente = $auth->createRole('Cliente');
        $role_cliente->description = 'Role de Cliente';
        $auth->add($role_cliente);

        //////////////////////// PERMISSÕES BACK-OFFICE
        //Criar permissões - $auth->createPermission();
        $permission_edit_roles = $auth->createPermission('editRoles');
        $permission_edit_roles->description = 'Permissão para editar os roles';
        $auth->add($permission_edit_roles);

        $permission_gerir_produtos = $auth->createPermission('gerirProdutos');
        $permission_gerir_produtos->description = 'Permissão para gerir produtos';
        $auth->add($permission_gerir_produtos);

        $permission_edit_empresa = $auth->createPermission('editEmpresa');
        $permission_edit_empresa->description = 'Permissão para editar os dados da empresa';
        $auth->add($permission_edit_empresa);

        $permission_backoffice = $auth->createPermission('permissionBackoffice');
        $permission_backoffice->description = 'Permissão para entrar no backoffice';
        $auth->add($permission_backoffice);

        $permissaoDadosPessoais = $auth->createPermission('updateDadosPessoais');
        $permissaoDadosPessoais->description = 'Atualizar dados pessoais';
        $permissaoDadosPessoais->ruleName = 'isAuthorDadosPessoais'; // Nome da regra
        $auth->add($permissaoDadosPessoais);

        $permissaoAlterarPassword = $auth->createPermission('updatePassword');
        $permissaoAlterarPassword->description = 'Alterar Password';
        $permissaoAlterarPassword->ruleName = 'isAuthorPassword'; // Nome da regra
        $auth->add($permissaoAlterarPassword);

        $permissaoEliminarAvaliacao = $auth->createPermission('deleteAvaliacao');
        $permissaoEliminarAvaliacao->description = 'Eliminar Avaliacao';
        $auth->add($permissaoEliminarAvaliacao);

        $permissaoEliminarFornecedor = $auth->createPermission('deleteFornecedor');
        $permissaoEliminarFornecedor->description = 'Eliminar Fornecedor';
        $auth->add($permissaoEliminarFornecedor);

        $permissaoEliminarFatura = $auth->createPermission('deleteFatura');
        $permissaoEliminarFatura->description = 'Eliminar Fatura';
        $auth->add($permissaoEliminarFatura);

        $permissaoEliminarArtigo = $auth->createPermission('deleteArtigo');
        $permissaoEliminarArtigo->description = 'Eliminar Artigo';
        $auth->add($permissaoEliminarArtigo);

        $permissaoEliminarIva = $auth->createPermission('deleteIva');
        $permissaoEliminarIva->description = 'Eliminar Iva';
        $auth->add($permissaoEliminarIva);

        $permissaoEliminarCategoria = $auth->createPermission('deleteCategoria');
        $permissaoEliminarCategoria->description = 'Eliminar Categorias';
        $auth->add($permissaoEliminarCategoria);

        $permissaoEditarAvaliacao = $auth->createPermission('updateAvaliacao');
        $permissaoEditarAvaliacao->description = 'Editar Avaliacao';
        $auth->add($permissaoEditarAvaliacao);


        //////////////////////// PERMISSÕES FRONT-OFFICE
        $permission_frontoffice = $auth->createPermission('permissionFrontoffice');
        $permission_frontoffice->description = 'Permissão para entrar no front office';
        $auth->add($permission_frontoffice);

        $permission_VerClientesFront = $auth->createPermission('verClientesFront');
        $permission_VerClientesFront->description = 'Permissão para ver os seus Favoritos/Carrinho/Faturas como cliente';
        $permission_VerClientesFront->ruleName = 'isClientPermission';
        $auth->add($permission_VerClientesFront);

        $permissaoUpdateProprioCliente = $auth->createPermission('updateProprioCliente');
        $permissaoUpdateProprioCliente->description = 'Update Proprio Cliente';
        $permissaoUpdateProprioCliente->ruleName = 'isUpdateProprioCliente'; // Nome da regra
        $auth->add($permissaoUpdateProprioCliente);

        $permissaoDeleteProprioCliente = $auth->createPermission('deleteProprioCliente');
        $permissaoDeleteProprioCliente->description = 'Delete Proprio Cliente';
        $permissaoDeleteProprioCliente->ruleName = 'isDeleteProprioCliente'; // Nome da regra
        $auth->add($permissaoDeleteProprioCliente);

        // dar heranças addChild
        $auth->addChild($role_funcionario, $permission_gerir_produtos);
        $auth->addChild($role_funcionario, $permission_backoffice);
        $auth->addChild($role_funcionario, $permissaoDadosPessoais);
        $auth->addChild($role_funcionario, $permissaoAlterarPassword);

        $auth->addChild($role_cliente, $permissaoDadosPessoais);
        $auth->addChild($role_cliente, $permissaoUpdateProprioCliente);
        $auth->addChild($role_cliente, $permissaoDeleteProprioCliente);
        $auth->addChild($role_cliente, $permissaoAlterarPassword);
        $auth->addChild($role_cliente, $permission_VerClientesFront);

        $auth->addChild($role_admin, $permission_edit_roles);
        $auth->addChild($role_admin, $permission_edit_empresa);
        $auth->addChild($role_admin, $permissaoEliminarAvaliacao);
        $auth->addChild($role_admin, $permissaoEliminarFatura);
        $auth->addChild($role_admin, $permissaoEliminarFornecedor);
        $auth->addChild($role_admin, $permissaoEliminarIva);
        $auth->addChild($role_admin, $permissaoEliminarCategoria);
        $auth->addChild($role_admin, $role_funcionario);

        // o primeiro utilizador vai ser ADMIN
        $auth->assign($role_admin, 1);

        //permissao frontoffice
        $auth->addChild($role_cliente, $permission_frontoffice);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231030_212423_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
