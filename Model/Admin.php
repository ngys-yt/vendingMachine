<?php
    class Admin{
        //singleton
        //インスタンスを保持する
        private static $singleton;
        //外部からインタンスを生成させない
        private function __construct(){}
        //外部からインスタンスを取得
        public static function getInstance(){
            if(is_null(self::$singleton)){
                self::$singleton = new Admin();
            }
            return self::$singleton;
        }

        // 入力されたパスワードを照合する
        public function authentication($password){
            if($password == "12345"){
                return "successful";
            }else{
                return "failure";
            }
        }
        
        
    }