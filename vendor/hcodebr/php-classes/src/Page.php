<?php


    namespace Hcode;

    use Rain\Tpl;

    class Page
    {
        private $tpl;
        private $options = [];
        private $defualts = [
            "header"=>true,
            "footer"=>true,
            "data"=>[]
        ];

        public function __construct($opts = array())
        {
            $this->options = array_merge($this->defualts,$opts);
            // config
            $config = array(
                "base_url"      => null,
                "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]. "/views/",
                "cache_dir"     =>  $_SERVER["DOCUMENT_ROOT"]. "/views-cache/",
                "debug"         => false // set to false to improve the speed
            );

            Tpl::configure( $config );
            $this->tpl = new Tpl();

            $this->setData($this->options["data"]);

            $this->tpl->draw("header");


        }

        //Metodo para carregar dados das paginas
        private function setData($data = array()){
            foreach ($data as $key => $value){
                $this->tpl->assign($key, $value);
            }
        }

        //Conteudo da pagina "Login", Cadasreo, etc.
        public function setTpl($name, $data= array(), $returnHTML = false){
            $this->setData($data);
            return $this->tpl->draw($name, $returnHTML);
        }

        public function __destruct()
        {
            $this->tpl->draw("footer");
        }
    }
?>