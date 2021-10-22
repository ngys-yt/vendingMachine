<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head')
<body>
    <div class="flex-center position-ref full-height flex-column"> {{--flex-column⇨縦並び--}}
        <div class="content">
            <ul style="display: flex;list-style:none;">
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
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="content">
                <form action="{{ route('editMerc') }}" method="post">
                    @csrf
                    <div class="content d-flex mt-5" style="align-items:center;">
                            <input type="text" class="form-control mr-2" name="name" value="{{ old('name') }}" placeholder="名前 {{ $editmerc->name }} ">
                        @error('name')
                            
                                <p size="2" color="red">{{ $message }}</p>
                            
                        @enderror
                            <input type="text" class="form-control mr-2" name="price" value="{{ old('price') }}" placeholder="値段 {{ $editmerc->price }}円">
                        @error('name')
                            <p class="mr-2">
                                <font size="2" color="red">{{ $message }}</font>
                            </p>
                        @enderror
                            <input type="text" class="form-control mr-5" name="stock" value="{{ old('stock') }}" placeholder="在庫 {{ $editmerc->stock }}本">
                        @error('name')
                            <p class="mr-2">
                                <font size="2" color="red">{{ $message }}</font>
                            </p>
                        @enderror
                        <input type="submit" value="商品変更" class="form-control mr-2">
                    </div>
                </form>

            <form class="mt-5" action="{{ route('logout') }}">
                <button type="submit" class="form-control col-4 mx-auto mt-10">ログアウト</button>
            </form>
        </div>
    </div>
    @php
        if(session()->has('test'))
        echo session('test')[0];
    @endphp
</body>
</html>

