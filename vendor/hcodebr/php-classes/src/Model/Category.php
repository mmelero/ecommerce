<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Model\Product;


class Category extends Model {

    public static function listAll()
    {

        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories ORDER BY idcategory");

    }

    public function save()
    {

        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save(:idcategory,:descategory)", array(
            ":idcategory"=>$this->getidcategory(),
            ":descategory"=>utf8_decode($this->getdescategory())
        ));

        $this->setData($results[0]);

        Category::updateFile();

    }

    public function get($idcategory){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory",[
            ":idcategory"=>$idcategory
        ]);

        $this->setData($results[0]);
    }

    public function delete(){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory =:idcategory",[
            ":idcategory"=>$this->getidcategory()
        ]);

        Category::updateFile();
    }

    public static function updateFile(){

        $categories = Category::listAll();

        $html = [];

        foreach ($categories as $row){

            array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');

        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. "views" . DIRECTORY_SEPARATOR. "categories-menu.html",
                            implode('',$html));
    }

    public function getProducts($related = true){
        $sql = new Sql();

        //var_dump((int)$this->getidcategory());
        //exit();

        if ($related === true) {

           return $sql->select(
               "SELECT * FROM tb_products a 
                         WHERE EXISTS( SELECT 1 
                                        FROM tb_productscategories b
                                        WHERE b.idproduct = a.idproduct
                                        AND   b.idcategory = :idcategory);
                        ", [
                            ':idcategory'=>$this->getidcategory()
                        ]);

        } else {
            return $sql->select(
                "SELECT * FROM tb_products a 
                         WHERE NOT EXISTS( SELECT 1 
                                            FROM tb_productscategories b
                                            WHERE b.idproduct = a.idproduct
                                            AND   b.idcategory = :idcategory);
			", [
                ':idcategory'=>$this->getidcategory()
            ]);
        }
    }

    public function addProduct(Product $product){

        $sql = new Sql();

        $sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)",
        [
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getidproduct()
        ]);
    }


    public function removeProduct(Product $product)
    {

        $sql = new Sql();

        $sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct", [
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getidproduct()
        ]);

    }

    public function getProductsPage($page, $itemsPerPage = 2){

        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();


        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS *  
                                 FROM tb_products a 
                                 WHERE EXISTS( SELECT 1 
                                                 FROM tb_productscategories b
                                                 WHERE b.idproduct = a.idproduct
                                                 AND   b.idcategory = :idcategory)
                                LIMIT $start, $itemsPerPage;",[
                ':idcategory'=>$this->getidcategory()
                ]);

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data'=>Product::checkList($results),
            'total'=>(int)$resultsTotal[0]["nrtotal"],
            'pages'=>ceil($resultsTotal[0]["nrtotal"]/$itemsPerPage)
        ];
    }


}

?>