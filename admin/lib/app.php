<?php
class Common
{
    public static function getRandomKey($length = 8)
    {
        $char = "ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvxyz0123456789";
        $str = '';
        for($i=0; $i<$length; $i++) {
            $str .= $char[rand(0, strlen($char)-1)];
        }
        return $str;
    }
}

class User
{
    protected $_password = '123456';

    protected $_password_salt;

    public function __construct()
    {
        $this->setPasswordSalt(Common::getRandomKey(22));
    }

    public function login($password)
    {
        $password_hash = crypt($this->_password, '$2a$07$'.$this->getPasswordSalt().'$');
        $input_hash = crypt($password, '$2a$07$'.$this->getPasswordSalt().'$');

        if ($password_hash == $input_hash) {
            $token_login = base64_encode(Common::getRandomKey(64));
            $this->setLoginToken($token_login);
            echo $token_login;
        } else {
            echo 'Login false!';
        }
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->_password_salt;
    }

    /**
     * @param string $password_salt
     */
    public function setPasswordSalt($password_salt)
    {
        $this->_password_salt = $password_salt;
    }

    /**
     * @return mixed
     */
    public static function getLoginToken()
    {
        return isset($_SESSION['token_login'])?$_SESSION['token_login']:'';
    }

    /**
     * @param mixed $login_token
     */
    public function setLoginToken($login_token)
    {
        $_SESSION['token_login'] = $login_token;
    }


}
echo (new User)->login('123456').'<br/>';
echo User::getLoginToken();