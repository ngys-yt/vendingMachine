<?php

namespace App\Http\Controllers;

use App\Http\Requests\VMRequest;
use Facades\App\Depot;
use Facades\App\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VendingMachineController extends Controller
{
    public function index(VMRequest $request){
        $b = Bank::first();

        return view('welcome')->with([
            'money' => $b ? $b->money : null,
            'merchandise' => Depot::get(),
        ]);
    }

    public function insert(Request $request){
        $request->validate([
            'money' => 'required',
        ],[
            'money.required' => 'お金を入力してください'
        ]);
        // ??????
        Bank::deposit($request->only('money')['money']);

        return redirect()->route('index')->with('test', [1,2,3,4]);
    }

    public function refund(Request $request){
        $bool = Bank::refund();

        return redirect()->route('index')->with('bool', $bool);
    }

    // 商品追加
    public function addMerchandise(Request $request){
        // バリデーション
        $request->validate([
            'name' => ['required','regex:/^[ぁ-んァ-ヶー一-龠]+$/'],
            // '入力必須','かな・カナ・漢字のみ'
            'price' => 'required | integer | between:100,200',
            // '入力必須','整数のみ','100~200の間'
            'stock' => 'required | integer | between:1,10'
            // '入力必須','整数のみ','1~10の間'
            ],[
            'required' => ':attributeを入力してください',
            'regex' => '入力できない文字です',
            'integer' => '数値を入力してください',
            'price.between' => '100~200の数値を入力してください',
            'stock.between' => '1~10の数値を入力してください'
            ],[
            'name' => '名前',
            'price' => '値段',
            'stock' => '在庫'
        ]);
        // データベースに追加するだけ
        Depot::addMerchandise(
            $request->get('name'),
            $request->get('price'),
            $request->get('stock')
        );

        return redirect()->route('index');
    }
    // 商品削除
    public function destroy(Request $request){
        $id = $request->get('id');
        // Depotテーブルの中から、requestで受け取ったIDを削除
        Depot::where('id', $id)->delete();
        return redirect()->route('index');
    }

    // 商品編集
    public function editMerc(Request $request){
        $request->validate([
            'name' => ['required','regex:/^[ぁ-んァ-ヶー一-龠]+$/'],
            // '入力必須','かな・カナ・漢字のみ'
            'price' => 'required | integer | between:100,200',
            // '入力必須','整数のみ','100~200の間'
            'stock' => 'required | integer | between:1,10'
            // '入力必須','整数のみ','1~10の間'
            ],[
            'required' => ':attributeを入力してください',
            'regex' => '入力できない文字です',
            'integer' => '数値を入力してください',
            'price.between' => '100~200の数値を入力してください',
            'stock.between' => '1~10の数値を入力してください'
            ],[
            'name' => '名前',
            'price' => '値段',
            'stock' => '在庫'
        ]);
        dd($request->get('name'));
        Depot::editMerchandise(
            $request->get('id'),
            $request->get('name'),
            $request->get('price'),
            $request->get('stock')
        );

        return redirect()->route('editView');
    }

    // 商品内容修正画面表示
    public function editView(Request $request){
        $id = $request->get('id');
        // Depotテーブルの中から、requestで受け取ったIDの１行を取得
        $merc = Depot::where('id', $id)->first();
        return view('editView')->with([
            'merchandise' => Depot::get(),
            'editmerc' => $merc
        ]);
    }

    // 商品購入
    public function purchase(Request $request){
        $id = $request->get('id');
        $bank = Bank::first();
        $merc = Depot::where('id', $id)->first();

        if(empty($bank->money) || $bank->money < $merc->price){
            return redirect()->route('index')->with('money', true);
        }elseif($merc->stock < 1){
            return redirect()->route('index')->with('stock', true);
        }

        Bank::purchase($bank, $merc->price);
        Depot::purchase($merc);

        return redirect()->route('index')->with('name', $merc->name);
    }


    public function test(Request $request){
        $start = $request->get('start');
        $end = $request->get('end');

        return Depot::where('created_at', '>', $start)->where('created_at', '<=', $end)->get();
    }

    public function getFatRatio(Request $request){

    }
}
