<?php

namespace App\Http\Controllers\Api;
use App\Models\Item;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ItemResource;
use Illuminate\Validation\Rules\Unique;
use Laravel\Sanctum\PersonalAccessToken;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::with(['carts'])->get();
        $code = Auth::guard()->user()->code;
        if (!$code) {
            return response()->json([
                'user' => Auth::guard()->user(),
                'data' => ClientResource::collection($clients),
            ], 200);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = Client::create($request->all());
        if ($client) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل عميلا جديدا",
                "data" => new ClientResource($client)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل عميلا جديدا ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $user = auth()->guard()->user();
        if ($user) {
            $client = Client::where(['id'=>$client->id])->with(['carts'])->get();
            return response()->json([
                'data' => ClientResource::collection($client),
            ], 200);
        } else {
            return response()->json([
                'data' => "سجل الدخول اولا",
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function client()
    {
        if (request()->bearerToken() != null) {
            [$id, $user_token] = explode('|', request()->bearerToken(), 2);
            $token_data = DB::table('personal_access_tokens')->where(['token' => hash('sha256', $user_token), 'name'=>'client' ])->first();
            $guard = $token_data->name;
            $user = Client::where(['id'=>$token_data->tokenable_id])->with(['wishlists'])->first();
            if ($user) {
                return response()->json([
                    'data' => new ClientResource($user),
                ], 200);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function clientGuest()
    {
        if (auth()->guard()->check()) {
            $user = Client::where(['id'=>Auth::guard()->id()])->with(['wishlists'])->first();
            $wishlists = $user->wishlists;
            foreach ($wishlists as $item) {
                $wishlistItems[] = Item::where('id', '!=', $item->id)->get();
                $items = array_unique($wishlistItems);
            }
            // if ($user) {
            //     return response()->json([
            //         'data' => new ClientResource($user),
            //     ], 200);
            // }
        } else {
            $items = Item::all();
        }
        return response()->json([
            'client' => new ClientResource($user),
            'data'   => ($items),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if ($client->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل البيانات",
                "data" => $client
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل الشركة المصنعة ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if ($client->orders->count() == 0 ) {
            if ($client->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف العميل",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف العميل",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف عميل لديه فواتير شراء",
            ], 422);
        }
    }
}
