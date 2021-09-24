
<?php
    class Money{
        // singleton
        // インスタンスを保持する
        private static $singleton;
        // 外部からインタンスを生成させない
        private function __construct(){}
        // 外部からインスタンスを取得
        public static function getInstance(){
            if(is_null(self::$singleton)){
                self::$singleton = new Money();
            }
            return self::$singleton;
        }

        // 入金された金額(残高)を保存するメンバ変数
        private $payment = 0;

        // 売り上げ金額を保存するメンバ変数
        private $proceeds = 0;

        // 残高を取得する
        public function getPayment(){
            return $this->payment;
        }

        // 売り上げ金額を取得
        public function getProceeds(){
            return $this->proceeds;
        }

        // 購入金額を売り上げに追加
        public function setProceeds($productPrice){
            $this->proceeds = $this->proceeds + $productPrice;
        }
        
        // 商品の値段を引数で受け取り残高から引く
        public function setPayment($productPrice){
            $this->payment = $this->payment - $productPrice;
        }
        
        // 既存の$paymentにcontrollerから受け取った$cash(入金額)を追加する
        public function keep($cash){
            $this->payment = $this->payment + $cash;
        }

        // 残高を0円にする
        public function refund(){
            $this->payment = 0;
        }
    }