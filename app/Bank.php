<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public function deposit($m){
        if($this->first()){
            $i = $this->first();
            $i->money += $m;
            $i->save();
        } else {
            $i = new self();
            $i->money = $m;
            $i->save();
        }
    }

    public function refund(){
        if($i = $this->first()){
            $i->money = 0;
            $i->save();

            return true;
        } 
        
        return false;
    }

    public function purchase($ins, $price){
        $ins->money -= $price;
        $ins->save();
    }
}
