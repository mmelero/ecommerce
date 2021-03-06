<?php

    use \Hcode\PageAdmin;
    use \Hcode\Model\User;
    use \Hcode\Model\Product;

    $app->get("/admin/products", function (){

        User::verifyLogin();

        $proudcts = Product::listAll();

        $page = new PageAdmin();

        $page->setTpl("products",[
            "products"=>$proudcts
        ]);
    });

    $app->get("/admin/products/create", function (){

        User::verifyLogin();

        $page = new PageAdmin();

        $page->setTpl("products-create");
    });

    $app->post("/admin/products/create", function (){

        User::verifyLogin();

        $product = new Product();

        $product->setData($_POST);

        $product->save();

        header("Location: /admin/products");
        exit();

      });

    $app->get("/admin/products/:idproduct", function ($idproduct){

        User::verifyLogin();

        $product = new Product();

        $product->get((int)$idproduct);

        $page = new PageAdmin();

        $page->setTpl("products-update",
        ['product'=>$product->getValues()]);

        //$product->save();

        //header("Location: /admin/products");
        //exit();

    });


    $app->post("/admin/products/:idproduct", function ($idproduct){

        User::verifyLogin();

        $product = new Product();

        $product->get((int)$idproduct);

        $product->setData($_POST);

        $product->save();

        //if($_FILES["file"]["name"] !== "") $product->setPhoto($_FILES["file"]);
        $product->setPhoto($_FILES["file"]);

        header("Location: /admin/products");
        exit();

    });

    $app->get("/admin/products/:idproduct/delete", function ($idproduct){

        User::verifyLogin();

        $product = new Product();

        $product->get((int)$idproduct);

        $product->delete();

        header("Location: /admin/products");
        exit();

    });
?>