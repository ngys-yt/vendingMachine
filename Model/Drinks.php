<?php
    class Drinks{
        //インスタンスを保持する
        private static $singleton;
        //外部からインタンスを生成させない
        private function __construct(){}
        //外部からインスタンスを取得
        public static function getInstance(){
            if(is_null(self::$singleton)){
                self::$singleton = new Drinks();
            }
            return self::$singleton;
        }

        // 商品リスト
        private $products=[
            "key1" => ["name","price","stock"],
            "key2" => ["name","price","stock"],
            "key3" => ["name","price","stock"]
        ];

        // 商品リストの初期化
        public function initializeProducts(){
            $this->products["key1"][0] = "水";
            $this->products["key2"][0] = "お茶";
            $this->products["key3"][0] = "コーラ";
            $this->products["key1"][1] = 100;
            $this->products["key2"][1] = 120;
            $this->products["key3"][1] = 160;
            $this->products["key1"][2] = 5;
            $this->products["key2"][2] = 3;
            $this->products["key3"][2] = 2;
        }

        // 商品の最低金額を取得
        public function minPrice(){
            $minProductsPrice = min($this->products["key1"][1],$this->products["key2"][1],$this->products["key3"][1]);
            return $minProductsPrice;
        }

        // 商品リストを取得
        // ユーザーと管理者で表示を変える
        public function getProductsList($authority){

            switch($authority){
                case "user":
                    foreach($this->products as $product)
                        if($product[2] == 0){
                            echo $product[0]," ","売り切れ\n";
                        }else{
                            echo $product[0]," ",$product[1],"円"."\n";
                        }
                    break;
            
                case "admin":
                    foreach($this->products as $product)
                        echo $product[0]," ",$product[1],"円"," ",$product[2]."本\n";
                    break;
            }
        }

        // controllerで入力された文字と商品名を照合して入力された商品名を返す
        // 管理者も使用するメソッド
        public function getProductsName($productsName){

            switch($productsName){
                case $this->products["key1"][0]:
                    return $this->products["key1"][0];
                    break;

                case $this->products["key2"][0]:
                    return $this->products["key2"][0];
                    break;

                case $this->products["key3"][0]:
                    return $this->products["key3"][0];
                    break;

                default:
                    return "error";
                    break;
            }
        }

        // controllerで入力された文字と商品名を照合して入力された商品名の在庫数を返す
        public function getProductsStock($productsName){

            switch($productsName){
                case $this->products["key1"][0]:
                    return $this->products["key1"][2];
                    break;

                case $this->products["key2"][0]:
                    return $this->products["key2"][2];
                    break;

                case $this->products["key3"][0]:
                    return $this->products["key3"][2];
                    break;
            }
        }

        // controllerで入力された文字と商品名を照合して入力された商品名の価格を返す
        public function getProductsPrice($productsName){

            switch($productsName){
                case $this->products["key1"][0]:
                    return $this->products["key1"][1];
                    break;

                case $this->products["key2"][0]:
                    return $this->products["key2"][1];
                    break;

                case $this->products["key3"][0]:
                    return $this->products["key3"][1];
                    break;
            }
        }

        // 購入後、在庫数を-1する
        public function buy($productsName){

            switch($productsName){
                case $this->products["key1"][0]:
                    $this->products["key1"][2] = $this->products["key1"][2]-1;
                    break;

                case $this->products["key2"][0]:
                    $this->products["key2"][2] = $this->products["key2"][2]-1;
                    break;

                case $this->products["key3"][0]:
                    $this->products["key3"][2] = $this->products["key3"][2]-1;
                    break;
            }
        }


        // 管理者が入力した名前に商品名を変更する
        // 入力された商品名が既に存在している場合はエラーを返す
        public function setProductsName($editName ,$insertName){

            if($insertName == $this->products["key1"][0] 
                || $insertName == $this->products["key2"][0] 
                || $insertName == $this->products["key3"][0]){

                return "error";
            }
            
            switch($editName){
                case $this->products["key1"][0]:
                    return $this->products["key1"][0] = $insertName;
                    break;
                
                case $this->products["key2"][0]:
                    return $this->products["key2"][0] = $insertName;
                    break;

                case $this->products["key3"][0]:
                    return $this->products["key3"][0] = $insertName;
                    break;
            }
        }

        // 管理者が入力した金額に変更する
        public function setProductsPrice($editPriceName ,$insertPrice){

            switch($editPriceName){
                case $this->products["key1"][0]:
                    return $this->products["key1"][1] = $insertPrice;
                    break;
                
                case $this->products["key2"][0]:
                    return $this->products["key2"][1] = $insertPrice;
                    break;

                case $this->products["key3"][0]:
                    return $this->products["key3"][1] = $insertPrice;
                    break;
            }
        }

        // 管理者が入力した在庫数に変更する
        public function setProductsStock($editStockName,$insertStock){

            switch($editStockName){
                case $this->products["key1"][0]:
                    return $this->products["key1"][2] = $insertStock;
                    break;
                
                case $this->products["key2"][0]:
                    return $this->products["key2"][2] = $insertStock;
                    break;

                case $this->products["key3"][0]:
                    return $this->products["key3"][2] = $insertStock;
                    break;
            }
        }
    }