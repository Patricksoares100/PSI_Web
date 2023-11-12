<?php

namespace frontend\models;

use common\models\Carrinho;
use common\models\Perfil;
use Yii;
use yii\base\Model;
use common\models\User;

use DateTime;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username; // estes 3 são da tabela user
    public $email;
    public $password;

    public $nome; // aqui leva os da tabela perfil
    public $telefone;
    public $nif;
    public $morada;
    public $codigo_postal;
    public $localidade;
    public $carrinho_id; // ativar no fim


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['nome', 'trim'], // da tabela perfil
            ['nome', 'required'],

            ['telefone', 'trim'],
            ['telefone', 'required'],
            ['telefone', 'match', 'pattern' => '^\d{9}?$^', 'message' => 'Invalid Phone Number'],
            ['telefone', 'string', 'max' => 9, 'message' => 'Invalid Phone Number'],

            ['nif', 'trim'],
            ['nif', 'required'],
            ['nif', 'match', 'pattern' => '^\d{9}?$^', 'message' => 'Invalid NIF'],
            ['nif', 'string', 'max' => 9, 'message' => 'Invalid NIF'],

            ['morada', 'trim'],
            ['morada', 'required'],

            ['codigo_postal', 'trim'],
            ['codigo_postal', 'required'],
            ['codigo_postal', 'match', 'pattern' => '^\d{4}-\d{3}?$^', 'message' => 'Invalid Postal Code'],

            ['localidade', 'trim'],
            ['localidade', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $perfil = new Perfil(); //Instancia do perfil e fazer do carrinho
        $carrinho = new Carrinho();
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        $user->save() && $this->sendEmail($user);
        $carrinho->valor_total = 0;
        $carrinho->iva_total = 0;
        $carrinho->data = (new DateTime())->format('Y-m-d');
        $carrinho->save();

        $perfil->nome = $this->nome;
        $perfil->telefone = $this->telefone;
        $perfil->nif = $this->nif;
        $perfil->morada = $this->morada;
        $perfil->codigo_postal = $this->codigo_postal;
        $perfil->localidade = $this->localidade;
        $perfil->carrinho_id = $carrinho->id;///$this->carrinho_id;// tirar no fim
        $perfil->save();

        //Guardar os novos utilizadores com o role de cliente
        //Todos menos o primeiro, no rbac/migration esta definido que o 1º é admin
        $contadorUsers = User::find()->count();
        $auth = \Yii::$app->authManager;
        if ($contadorUsers != 1) {
            $authorRole = $auth->getRole('cliente');
            $auth->assign($authorRole, $user->getId());
        }


        return $user;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
