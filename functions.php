<?php
    function formatPrice($vlprice){

        $vlprice = (float)$vlprice;

        return number_format($vlprice, 2, ",",".");
    }
?>