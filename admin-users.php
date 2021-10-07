<?php

    use \Hcode\PageAdmin;
    use \Hcode\Model\User;

    $app->get('/admin/users', function(){

        User::verifyLogin();

        $users = User::listAll();

        $page = new PageAdmin();

        $page->setTpl("users", array("users"=>$users));
    });


    $app->get('/admin/users/:iduser/delete', function ($iduser)
    {
        User::verifyLogin();

        $user = new User();

        $user->get((int)$iduser);

        $user->delete($user);

        header("Location: /admin/users");
        exit();
    });


    $app->get('/admin/users/create', function(){

        User::verifyLogin();

        $page = new PageAdmin();

        $page->setTpl("users-create",[
            'error'=>User::getError(),
            'errorRegister'=>User::getErrorRegister(),
            'registerValues'=>(isset($_SESSION['registerValues'])) ? $_SESSION['registerValues'] :
                ['desperson'=>'', 'deslogin'=>'', 'nrphone'=>'', 'desemail'=>'','despassword'=>'']

        ] );

    });

    $app->post('/admin/users/create', function ()
    {
        $_SESSION['registerValues'] = $_POST;

        if(!isset($_POST['desperson']) || $_POST['desperson'] == ''){
            User::setErrorRegister("Preencha  o seu nome.");
            header("Location: /admin/users/create");
            exit();
        }
        if(!isset($_POST['deslogin']) || $_POST['deslogin'] == ''){
            User::setErrorRegister("Preencha  o seu login.");
            header("Location: /admin/users/create");
            exit();
        }
        if(!isset($_POST['nrphone']) || $_POST['nrphone'] == ''){
            User::setErrorRegister("Preencha  o numero do telefone.");
            header("Location: /admin/users/create");
            exit();
        }
        if(!isset($_POST['desemail']) || $_POST['desemail'] == ''){
            User::setErrorRegister("Preencha  o seu Email.");
            header("Location: /admin/users/create");
            exit();
        }
        if(!isset($_POST['despassword']) || $_POST['despassword'] == ''){
            User::setErrorRegister("Preencha  o seu password.");
            header("Location: /admin/users/create");
            exit();
        }

        User::verifyLogin();

        $user = new User();

        $user->setData([
            'inadmin'=>0,
            'desperson'=>$_POST['desperson'],
            'deslogin'=>$_POST['deslogin'],
            'nrphone'=>(int)$_POST['nrphone'],
            'desemail'=>$_POST['desemail'],
            'despassword'=>$_POST['despassword']


        ]);


        $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

        $user->setData($_POST);

        $user->save();

        header("Location: /admin/users");
        exit();
    });

    $app->get("/admin/users/:iduser", function($iduser) {

        User::verifyLogin();

        $user = new User();

        $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

        $user->get((int)$iduser);

        $page = new PageAdmin();

        $page->setTpl("users-update", array(
            "user"=>$user->getValues()
        ));

    });


    $app->post('/admin/users/:iduser', function ($iduser)
    {
        User::verifyLogin();

        $user = new User();

        $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

        $user->get((int)$iduser);

        $user->setData($_POST);

        $user->update();

        header("Location: /admin/users");
        exit();
    });


?>