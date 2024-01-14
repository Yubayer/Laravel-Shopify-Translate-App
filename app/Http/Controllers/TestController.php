<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Shopify\ShopifyClient;

class TestController extends Controller
{

    public function getThemeId($shop) {
        $response = $shop->api()->rest('GET', '/admin/api/2024-01/themes.json');
        $response_array = $response['body'];;
        $theme_id = $response_array['themes'][0]['id'];
        return $theme_id;
    }

    public function createLocalesFile($shop, $theme_id, $assetUrl) {
        $response = $shop->api()->rest('GET', $assetUrl, ['asset[key]' => 'locales/en.default.json']);

        $value_json = $response['body']['asset']['value'];
        $value_ary = json_decode($value_json);


        $shopifyApiKey = 'd5d087abf9de4f51d8b530f76c1a4e29';
        $shopifyPassword = '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';
        $apiUrl = 'https://ostad-app-dev.myshopify.com/admin/api/2024-01/themes';

        $assetData = [
            'asset' => [
                'key' => 'locales/bn.json',
                'value' => json_encode($value_ary),
            ],
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode("$shopifyApiKey:$shopifyPassword"),
        ])
            ->put("$apiUrl/$theme_id/assets.json", $assetData);

        // Handle the response
        // if ($response->successful()) {
        //     $responseData = $response->json();
        //     // Process the response data
        //     dd($responseData);
        // } else {
        //     $errorResponse = $response->json();
        //     // Handle the error response
        //     dd($errorResponse);
        // }
    }

    
    public function index(Request $request)
    {
        $shop = $request->user();
        $theme_id = $this->getThemeId($shop);
        $assetUrl = '/admin/api/2024-01/themes/'.$theme_id.'/assets.json';
        
        $this->createLocalesFile($shop, $theme_id, $assetUrl);

        $response = $shop->api()->rest('GET', $assetUrl, ['asset[key]' => 'locales/en.default.json']);
        $responseBn = $shop->api()->rest('GET', $assetUrl, ['asset[key]' => 'locales/bn.json']);

        $value_jaon = $response['body']['asset'];
        $value_array = json_decode($value_jaon->value, true);

        $value_json_bn = $responseBn['body']['asset'];
        $value_array_bn = json_decode($value_json_bn->value, true);

        // dd($value_array_bn['general']);

        return view('welcome', compact(['value_array', 'value_array_bn']));
    }

    public function translate(Request $request) {

        $shop = $request->user();

        $updatedDataX = $request->except(['token','host','shop']);

        foreach ($updatedDataX as $key => $value) {
            if(gettype($value) == 'array') {
                foreach ($value as $key2 => $value2) {
                   if(gettype($value2) == 'array') {
                        foreach ($value2 as $key3 => $value3) {
                            if(gettype($value3) == 'array') {
                                foreach ($value3 as $key4 => $value4) {
                                    if(gettype($value4) == 'array') {
                                        foreach ($value4 as $key5 => $value5) {
                                            if(gettype($value5) == 'array') {
                                                //
                                            } else {
                                                if(is_null($value5)) {
                                                    $updatedDataX[$key][$key2][$key3][$key4][$key5] = '';
                                                }
                                            }
                                        }
                                    } else {
                                        if(is_null($value4)) {
                                            $updatedDataX[$key][$key2][$key3][$key4] = '';
                                        }
                                    }
                                }
                            } else {
                                if(is_null($value3)) {
                                    $updatedDataX[$key][$key2][$key3] = '';
                                }
                            }
                        }
                    } else {
                        if(is_null($value2)) {
                            $updatedDataX[$key][$key2] = '';
                        }
                    }
                }
            } else {
                if(is_null($value)) {
                    $updatedDataX[$key] = '';
                }
            }
        }


        $updatedData = json_encode($updatedDataX);

        $shopifyApiKey = 'd5d087abf9de4f51d8b530f76c1a4e29';
        $shopifyPassword = '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';
        $themeId = '133703336105';
        $apiUrl = 'https://ostad-app-dev.myshopify.com/admin/api/2024-01/themes';

        $assetData = [
            'asset' => [
                'key' => 'locales/bn.json',
                'value' => $updatedData,
            ],
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode("$shopifyApiKey:$shopifyPassword"),
        ])
            ->put("$apiUrl/$themeId/assets.json", $assetData);

        // Handle the response
        if ($response->successful()) {
            $responseData = $response->json();
            // Process the response data
            dd($responseData);
        } else {
            $errorResponse = $response->json();
            // Handle the error response
            dd($errorResponse);
        }

        dd($responseData);
        return redirect()->route('home');
    }
}
