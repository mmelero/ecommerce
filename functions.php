<?php
use \Hcode\Model\User;

    function formatPrice($vlprice){

        $vlprice = (float)$vlprice;

        return number_format($vlprice, 2, ",",".");
    }

    function checkLogin($inadmin = ture){

        return User::checkLogin($inadmin);

    }

    function getUserName(){

        $user =  User::getFromSession();
        return $user->getdesperson();

    }
?>