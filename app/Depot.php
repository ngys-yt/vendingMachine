<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    public function addMerchandise($name, $price, $stock){
        $depot = new self();
        $depot->name = $name;
        $depot->price = $price;
        $depot->stock = $stock;
        $depot->save();
    }

    public function editMerchandise($id,$name,$price,$stock){
        $merc = $this::where('id', $id)->first();
        dd($id);
        $merc->name = $name;
        $merc->price = $price;
        $merc->stock = $stock;
        $merc->save();
    }

    public function purchase($ins){
        $ins->stock -= 1;
        $ins->save();
    }
}
