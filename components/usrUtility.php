<?php
class usrUtility extends CApplicationComponent
{

    private $pass_min = 8;
    private $pass_max = 15;
    private $user_min = 3;
    private $user_max = 12;
    private $user_case = FALSE;
    private $salt = '$2a$07$usesomesillystringforsalt$';

    const PASS_MIN = 8;
    const PASS_MAX = 15;
    const USER_MIN = 10;
    const USER_MAX = 15;
    const USER_CASE = false;

    //800
    //24859800

    private $users = array();

    public function __construct(){
        $this->pass_min = self::PASS_MIN;
        $this->pass_max = self::PASS_MAX;
        $this->user_min = self::USER_MIN;
        $this->user_max = self::USER_MAX;
        $this->user_case = self::USER_CASE;
    }

    public function generate( $capacity = 1 )
    {


        try {
            for ($i = 1; $i <= $capacity; $i++) {
                $username = $this->genUsername($this->user_min, $this->user_max, $this->user_case);


                // Make sure username is unique
                if (array_key_exists($username, $this->users) == false) {
                    $password = $this->genPassword($this->pass_min, $this->pass_max);


                    $encrypted = $this->cryptPassword($password);


                    $this->users[$username] = array
                    (
                        'user_num' => $i,
                        'username' => $username,
                        'password' => $password,
                        'encrypted' => $encrypted,
                    );
                }
            }
            return $this->users;
        }catch (CException $e){
            return false;
        }
    }

    public function getQuery( $tablename, $columns)
    {
        $values = '';

        foreach ( $this->users as $u )
        {
            $values .= "(null,'{$u['username']}','{$u['encrypted']}'),";
        }

        return 'INSERT INTO '.$tablename.' ('.$columns.') VALUES'.rtrim($values, ",");

    }

    public function checkPassword ( $password )
    {
        // Query users password in DB. It will be stored encrypted, so use it here.
        $password_queried_from_db = '$2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi'; // pass = rasmuslerdorf

        if (CRYPT_BLOWFISH == 1)
        {
            if (crypt($password, $this->salt) == $password_queried_from_db)
            {
                return true;
            }
        }
        return false;

    }

    private function cryptPassword( $password )
    {
        return crypt($password, $this->salt);
    }


    public function genUsername( $min, $max, $case_sensitive = false )
    {
        // Set length
        $length = rand($min, $max);


        // Set allowed chars (And whether they should use case)
        if ( $case_sensitive )
        {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        else
        {
            $chars = "abcdefghijklmnopqrstuvwxyz";
        }

        // Get string length
        $chars_length = strlen($chars)-1;


        // Create username char for char
        $username = "";

        for ( $i = 0; $i < $length; $i++ )
        {
            $username .= $chars[mt_rand(0, $chars_length)];
        }

        return $username;

    }

    public function genPassword( $min, $max)
    {
        // Set length
        $length = rand($min, $max);

        // Set charachters to use
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$chars = '123456789@#$%&';
        $chars = '123456789';

        // Calculate string length
        $lower_length = strlen($lower)-1;
        $upper_length = strlen($upper)-1;
        $chars_length = strlen($chars)-1;

        // Generate password char for char
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++)
        {
            if ($alt == 0)
            {
                $password .= $lower[mt_rand(0, $lower_length)]; $alt = 1;
            }
            if ($alt == 1)
            {
                $password .= $upper[mt_rand(0, $upper_length)]; $alt = 2;
            }
            else
            {
                $password .= $chars[mt_rand(0, $chars_length)]; $alt = 0;
            }
        }

        return $password;
    }

    private function isNum( $num )
    {
        if ( is_int( (String) $num ) && ctype_digit((int) $num) && $num > 0 )
        {
            return true;
        }
        return false;
    }
}