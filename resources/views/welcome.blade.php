@extends('shopify-app::layouts.default')

@section('content')

    <style>
        .input_wrapper{
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            grid-gap: 30px;
        }

        label{
            font-weight: bold;
            margin-left: 10px;
        }
        
        .input_wrapper input{
            padding: 5px 10px;
        }
        h2{
            text-align: center;
        }
    </style>
    <!-- You are: (shop domain name) -->
    <label>You are: {{ $shopDomain ?? Auth::user()->name }} [English To Bangla Translate]</label>
    
    <form method="POST" action="{{ route('translate') }}">
        @sessionToken
    {{-- Root Label --}}
    @foreach ($value_array as $key => $value)
        @if(gettype($value) =='array')
            <h1>{{ $key }}</h1>
            {{-- First Label --}}
            @foreach ($value as $key1 => $firstLavel)
                @if(gettype($firstLavel) =='array')  
                    <h2>{{ $key1 }}</h2>
                    {{-- Second Label --}}
                    @foreach ($firstLavel as $key2 => $secondLavel)
                        @if(gettype($secondLavel) =='array')  
                            <h3>#{{ $key2 }}</h3>
                            {{-- Third Label --}}
                            @foreach ($secondLavel as $key3 => $thirdLavel)
                                @if(gettype($thirdLavel) =='array')  
                                    <h3>#{{ $key3 }}</h3>
                                    {{-- Forth Label --}}
                                    @foreach ($thirdLavel as $key4 => $forthLavel) 
                                        @if(gettype($forthLavel) =='array')
                                            <h3>#{{ $key4 }}</h3>
                                            @foreach ($forthLavel as $key5 => $fifthLavel)
                                                <label>{{ $key5 }}</label>
                                                <div class="input_wrapper">
                                                    <input type="text" value="{{ $fifthLavel }}" readonly>
                                                    <input type="text" name="{{ $key }}[{{$key1}}][{{$key2}}][{{$key3}}][{{$key4}}][{{$key5}}]" value="{{ $value_array_bn[$key][$key1][$key2][$key3][$key4][$key5] }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <label>{{ $key4 }}</label>
                                            <div class="input_wrapper">
                                                <input type="text" value="{{ $forthLavel }}" readonly>
                                                <input type="text" name="{{ $key }}[{{$key1}}][{{$key2}}][{{$key3}}][{{$key4}}]" value="{{ $value_array_bn[$key][$key1][$key2][$key3][$key4] }}">
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <label>{{$key3}}</label>
                                    <div class="input_wrapper">
                                        <input type="text" value="{{$thirdLavel}}" readonly>
                                        <input type="text" name="{{ $key }}[{{$key1}}][{{$key2}}][{{$key3}}]" value="@if(is_null($value_array_bn[$key][$key1][$key2][$key3]) ||  $value_array_bn[$key][$key1][$key2][$key3] < 1) {{ $thirdLavel }} @else{{ $value_array_bn[$key][$key1][$key2][$key3] }}@endif">
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <label>{{$key2}}</label>
                            <div class="input_wrapper">
                                <input type="text" value="{{$secondLavel}}" readonly>
                                <input type="text" name="{{ $key }}[{{$key1}}][{{$key2}}]" value="@if(is_null($value_array_bn[$key][$key1][$key2]) ||  $value_array_bn[$key][$key1][$key2] < 1) {{ $secondLavel }} @else{{ $value_array_bn[$key][$key1][$key2] }}@endif">
                            </div>
                        @endif
                    @endforeach
                @else
                    <label>{{ $key1 }}</label>
                    <div class="input_wrapper">
                        <input type="text" value="{{$firstLavel}}" readonly>
                        <input class="form-control" type="text" name="{{ $key }}[{{$key1}}]" value="@if(is_null($value_array_bn[$key][$key1]) ||  $value_array_bn[$key][$key1] < 1) {{ $firstLavel }} @else{{ $value_array_bn[$key][$key1] }}@endif">  
                    </div>
                @endif
            @endforeach
        @else
            <label>{{ $key }}</label>
            <div class="input_wrapper">
                <input type="text" value="{{$value}}" readonly>
                <input type="text" name="{{ $key }}" value="@if(is_null($value_array_bn[$key]) ||  $value_array_bn[$key] < 1) {{ $value }} @else{{ $value_array_bn[$key] }}@endif">
            </div>
        @endif
    @endforeach

    <button type="submit">Submit</button>
    </form>
@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>
@endsection