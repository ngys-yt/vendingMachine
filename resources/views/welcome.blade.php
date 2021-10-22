<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head')
    <body>
        <div class="flex-center position-ref full-height flex-column">
            <div class="content">
                @if(session('empty'))
                <h1 class="my-3">お金が足りません</h1>
                @endif

                @if(session('bool'))
                <h1 class="my-3">返金完了</h1>
                @endif

                @if(session('name'))
                <h1 class="my-3">{{ session()->get('name') }}を購入しました</h1>
                @endif
                <ul style="display:flex; list-style:none; padding:0"> 
                {{-- ul⇨箇条書き  display:flex⇨子要素を横並びにする --}}
                    <li class="mr-3">
                        <p>名前</p>
                        <p>値段</p>
                        <p>在庫</p>
                    </li>
                @foreach($merchandise as $key => $value)
                    <li class="mr-2">
                        <p>{{ $value->name }}</p>
                        <p>{{ $value->price }}</p>
                        <p>{{ $value->stock }}</p>
                        @auth
                        <form action="{{ route('editView') }}" method="post">
                        @csrf
                            <input type="hidden" name="id" value="{{ $value->id }}">
                            <input type="submit" class="form-control" value="修正">
                        </form>
                        <form action="{{ route('destroy') }}" method="post" class="mt-2 mb-5">
                        @csrf
                            <input type="hidden" name="id" value="{{ $value->id }}">
                            <input type="submit" class="form-control" value="削除">
                        </form>
                        @else
                        <form action="{{ route('purchase') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $value->id }}">
                            <input type="submit" class="form-control" value="購入">
                        </form>
                        @endauth
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="content">
                    @auth
                    <form class="form-inline mx-auto row" action="{{ route('add_merchandise') }}" method="post">
                    @csrf
                            <input class="form-control w-20 mr-2" type="text" name="name" placeholder="商品の名前" value="{{ old('name') }}">
                            @error('name')
                                <p class="mr-2">
                                    <font size="2" color="red">{{ $message }}</font>
                                </p>
                            @enderror
                            <input class="form-control w-25 mr-2" type="text" name="price" placeholder="商品の値段" value="{{ old('price') }}">
                            @error('price')
                                <p class="mr-2" >
                                    <font size="2" color="red">{{ $message }}</font>
                                </p>
                            @enderror
                            <input class="form-control w-25 mr-5" type="text" name="stock" placeholder="商品の在庫" value="{{ old('stock') }}">
                            @error('stock')
                                <p class="mr-2">
                                    <font size="2" color="red">{{ $message }}</font>
                                </p>
                            @enderror
                            <input type="submit" value="商品追加" class="form-control w-15">
                    </form>
                    @endauth
                    @auth
                    <form class="mt-5 w-25 mx-auto" action="{{ route('logout') }}">
                        <input type="submit" class="form-control mx-auto" value="ログアウト"/>
                    </form>
                    @else
                    @if($money)
                    <h3 id="money">{{ number_format($money) }}円が入ってます！</h3>
                    @endif
                    <form action="{{ route('deposit') }}" method="post">
                    @csrf
                        <input type="text" class="form-control w-50 mx-auto mt-5" name="money" placeholder="金額を入力">
                        @error('money')
                        <p style="color:red">{{ $message }}</p>
                        @enderror
                        <input type="submit" value="入金" class="form-control w-25 mx-auto mt-2">
                    </form>
                    <form action="{{ route('refund') }}" method="post">
                    @csrf
                        <input type="submit" value="返金" class="form-control w-25 mx-auto mt-2">
                    </form>
                    <h3 class="my-3"><a href="/login">ログイン</a></h3>
                    <h3 class="my-3"><a href="/register">登録</a></h3>
                    @endauth
            </div>
        </div>
        @php
            if(session()->has('test'))
                echo session('test')[0];
        @endphp
    </body>
</html>
