<?php
require_once 'core/init.php';
require_once '../data/IncludesNeeded.php';


$user = new User();
if($user->isLoggedIn())
{
    Redirect::to('../views/home.php');
}
else
{
    $validationError = "";
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'username' => array('required' => true),
                'password' => array('required' => true)
            ));

            $user = new User();

            if ($validation->passed()) {
                //log user in
                $user = new User();
                $remember = (Input::get('remember') == 'on') ? true : false;
                $pass = (Input::get('password'));
                $login = $user->login(Input::get('username'), $pass, $remember);

                if ($login) {
                    if ($user->user_active($user->data()->id)) {
                        if($user->hasPermission('admin'))
                        {
                            Redirect::to('../views/home.php');
                        }
                        else
                        {
                            Redirect::to('../views/home.php');
                        }
                    } else {
                        $validationError = mailBox();

                        $user->logout();
                    }

                } else {


                    $validationError = 'Sorry, Log in failed.';

                }

            } else {
                $bulkErrors = " ";
                foreach ($validation->errors() as $errors) {
                    // echo $errors,'<br>';
                    $space = '<br>';
                    $bulkErrors .= $errors . $space;
                }
                $validationError = $bulkErrors;


            }
            //}
        }
    }
}
//require_once"html/loginView.php";
?>


