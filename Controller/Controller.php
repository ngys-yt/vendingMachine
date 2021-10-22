<?php

    require_once "../Model/Admin.php";
    require_once "../Model/Drinks.php";
    require_once "../Model/Money.php";

    class Controller{

        // 起動時に商品リストの初期化
        public function __construct(){
            $drinks = Drinks::getInstance();
            $drinks->initializeProducts();
            $this->menu();
        }

        // 最初にユーザーに選択肢を表示する
        public function menu(){
            echo "1.入金 2.商品リスト 3.返金 4.購入 5.管理者 6.終了\n";
            $menu = readline();
            switch($menu){
                case 1:
                    $this->pay();
                    break;

                case 2:
                    $this->display("user");
                    break;

                case 3:
                    $this->refund();
                    break;

                case 4:
                    $this->buy();
                    break;

                case 5:
                    $this->admin();
                    break;

                case 6:
                    exit();

                default:
                    echo "再度入力してください\n";
                    $this->menu();
            } 
        }

        
        // 1.(入金)が入力された場合の入金処理
        public function pay(){
            $money = Money::getInstance();

            echo "入金してください\n";
            // 入金額を入力
            // 指定以外の入力をはじく
            $cash = readline();

            if (!($cash == "10" || $cash =="50" || $cash =="100" || $cash =="500" || $cash =="1000")){
                echo "入金できません\n";
                //入金できなかった入力$cashを0に戻す
                $cash = 0;
                $this->menu();
            }

            // 入金された金額を残高に追加
            $money->keep($cash);

            echo "入金額".$money->getPayment()."円\n";
            $this->menu();
        }

        // 2.(商品リスト)が入力された場合のリスト出力処理(ユーザー、管理者)
        public function display($authority){
            $drinks = Drinks::getInstance();

            // リストを出力
            $drinks->getProductsList($authority);

            if($authority == "user"){
                $this->menu();

            }elseif($authority == "admin"){
                $this->adminMenu();
            }
        }


        // 3.(返金)が入力された場合の返金処理
        public function refund(){
            $money = Money::getInstance();

            // 残高を取得して表示する
            echo "返金額".$money->getPayment()."円\n";

            // 残高を0にするメソッド
            $money->refund();

            $this->menu();
        }

        // 4.(購入)が入力された場合の購入処理
        public function buy(){
            $drinks = Drinks::getInstance();
            $money = Money::getInstance();

            // 最低金額を取得
            $minProductsPrice = $drinks->minPrice();

            // 残高が最低金額以下の場合購入できない
            $payment = $money->getPayment();

            if($payment < $minProductsPrice){
                echo "お金が足りません\n";
                $this->menu();
            }

            echo "商品を選んでください\n";

            // 商品の入力、リストと照合
            $productsName = $this->nameCollation("user");

            // 入力された商品名の在庫数を取得
            $productsStock = $drinks->getProductsStock($productsName);

            // 入力された商品名の価格を取得
            $productsPrice = $drinks->getProductsPrice($productsName);

            // 在庫がない商品は購入できない
            if($productsStock == 0){
                echo "売り切れ商品です\n";
                $this->menu();

            // 残高が商品価格より少ない場合は購入できない
            }elseif($payment < $productsPrice){
                echo "お金が足りません\n";
                $this->menu();
            }

            // 購入後、在庫数を-1する
            echo $productsName."を購入しました\n";
            $drinks->buy($productsName);

            // 残高から商品価格を引く
            $money->setPayment($productsPrice);

            // 購入金額を売り上げに追加する
            $money->setProceeds($productsPrice);

            // 購入後の残高が最低金額より低い場合は自動返金する
            $payment = $money->getPayment();
            if($payment < $minProductsPrice){
                echo "返金額".$payment."円\n";
                $money->refund();
                $this->menu();
            }

            $this->menu();
        }

        // 5.(管理者)が入力された場合の処理
        public function admin(){
            $admin = Admin::getInstance();
            echo "パスワードを入力してください\n";
            $password = readline();
            // 入力されたパスワードの認証
            // 失敗した場合、再入力
            $result = $admin->authentication($password);

            switch($result){

                case "successful":
                    echo "successful\n";
                    $this->adminMenu();
                    break;

                case "failure":
                    echo "failure\n";
                    $this->admin();
                    break;
            }
        }

        // 入力された商品名の照合(ユーザー、管理者)
        // errorの場合、ユーザー権限によってメニューの表示を変える
        public function nameCollation($authority){
            $productsName = readline();
            $drinks = Drinks::getInstance();
            $productsName = $drinks->getProductsName($productsName);

            if($productsName == "error"){
                echo "入力された商品名は存在しません\n";

                switch($authority){

                        case "user":
                            $this->menu();
                            break;

                        case "admin":
                            $this->adminMenu();
                            break;
                }
            }

            return $productsName;
        }


        // 認証成功で管理者メニュー表示
        // 管理操作した後にメニュー表示に帰ってくるためにmenuメソッドを認証と分ける
        public function adminMenu(){
            echo "1.商品リスト 2.商品名変更 3.金額変更 4.在庫数変更 5.売り上げ確認 6.ログアウト\n";
            // メニュー選択
            $menu = readline();
            switch($menu){
                case 1:
                    $this->display("admin");
                    break;

                case 2:
                    $this->editName();
                    break;

                case 3:
                    $this->editPrice();
                    break;

                case 4:
                    $this->editStock();
                    break;

                case 5:
                    $this->proceeds();
                    break;

                case 6:
                    echo "ログアウトしました\n";
                    $this->menu();
                    break;

                default:
                    echo "再度入力してください\n";
                    $this->adminMenu();
                    break;
            }
        }
        // 2.(商品名変更)が入力された場合の処理
        public function editName(){
            $drinks = Drinks::getInstance();
            echo "名前を変更したい商品を入力してください\n";

            // 入力された商品名の照合
            $editName = $this->nameCollation("admin");
            
            echo "新しい名前を入力してください\n";
            $insertName = readline();

            // 入力された商品名に応じて新しい名前を代入する
            $newProductsName = $drinks->setProductsName($editName,$insertName);
            // 新しい名前が既にある場合はエラー
            if($newProductsName == "error"){
                echo "入力された商品名は既に存在しています\n";
                $this->adminMenu();
            }

            // 変更内容を表示
            echo $editName."を".$newProductsName."に変更しました\n";

            $this->adminMenu();
        }

        // 3.(金額変更)が入力された場合の処理
        public function editPrice(){
            $drinks = Drinks::getInstance();
            echo "金額を変更したい商品を入力してください\n";

            // 入力された商品名の照合
            $editPriceName = $this->nameCollation("admin");

            echo "新しい金額を入力してください\n";
            $insertPrice = (int) readline();

            // 新しい金額の条件分岐
            // 『１桁目が０じゃない、200以上、値が０』の場合はエラー
            if(substr($insertPrice ,-1) !== "0" || $insertPrice > 200 || $insertPrice == 0){
                echo "入力できません\n";
                $this->adminMenu();
            }

            // 入力された商品名に応じて新しい名前を代入する
            $newProductsPrice = $drinks->setProductsPrice($editPriceName,$insertPrice);

            // 変更内容を表示
            echo $editPriceName."の金額を".$newProductsPrice."円に変更しました\n";

            $this->adminMenu();
        }

        // 4.(在庫数変更)が入力された場合の処理
        public function editStock(){
            $drinks = Drinks::getInstance();
            echo "在庫数を変更したい商品を入力してください\n";

            // 入力された商品名の照合
            $editStockName = $this->nameCollation("admin");
            
            echo "新しい在庫数を入力してください\n";
            $insertStock = (int) readline();

            // 新しい在庫数の条件分岐
            // 『値が０、１０以上』の場合はエラー
            if($insertStock == 0 || $insertStock > 10){
                echo "入力できません\n";
                $this->adminMenu();
            }

            // 入力された商品名に応じて新しい在庫数を代入
            $newProductsStock = $drinks->setProductsStock($editStockName,$insertStock);

            // 変更内容の表示
            echo $editStockName."の在庫数を".$newProductsStock."本に変更しました\n";

            $this->adminMenu();
        }

        // 5.(売り上げ確認)が入力された場合の処理
        public function proceeds(){
            $money = Money::getInstance();
            // 売り上げを取得して表示
            echo "売り上げは".$money->getProceeds()."円です\n";
            $this->adminMenu();
        }
    }

    $controller = new Controller();

