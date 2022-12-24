<?php

namespace App\Http\Controllers\Api;
use Carbon\Carbon;

use App\Models\Trader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\TraderResource;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class TraderController extends Controller
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;

    public function __construct ()
    {
        $authorizationHeader = \request()->header('Authorization');
        if(isset($authorizationHeader)) {
            $this->middleware('auth:sanctum');
        };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $traders = Trader::orderBy('f_name', 'ASC')->get();
        return response()->json([
            "data" => TraderResource::collection($traders)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTraderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->getTokenId('user')) {
            $request->validate([
                'phone'       => 'unique:traders,phone|regex:/^(01)[0-9]{9}$/',
                'code'        => 'unique:traders,code',
                'phone1'      => 'nullable|regex:/^(01)[0-9]{9}$/',
                'phone2'      => 'nullable|regex:/^(01)[0-9]{9}$/',
                'national_id' => 'unique:traders,national_id',
            ], [
                'phone.required'       => 'الهاتف مسجل من قبل',
                'phone.unique'       => 'الهاتف مسجل من قبل',
                'code.unique'        => 'الكود مسجل من قبل',
                'phone.regex'        => 'صيغة الهاتف غير صحيحة',
                'national_id.unique' => 'الرقم القومي مسجل من قبل',
                'phone1.regex'       => 'صيغة الهاتف غير صحيحة',
                'phone2.regex'       => 'صيغة الهاتف غير صحيحة',
            ]);
            $emailExist = Trader::where(['email'=>$request->email])->first();
            if (!empty($emailExist->email)) {
                return response()->json([
                'email'     => 'البريد الالكتروني مسجل من قبل',
                ]);
            }
            $trader = new Trader();
            $trader->fill($request->input());
            if ($request->has('img')) {
                $img = $request->file('img');
                $trader->img = $this->setImage($img, 'traders', 450, 450);
            }
            $trader->code = randomCode();

            if ($trader->save()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم تسجيل تاجرا جديدا",
                    "data" => new TraderResource($trader),
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل تسجيل التاجر",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "ليست لديك صلاحية الاضافة",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function show(Trader $trader)
    {
        $trader = Trader::where(['id'=>$trader->id])->with(['units' ,'items'])->first();
        return response()->json([
            "data"=> new TraderResource($trader),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function trader()
    {
        $user = $this->getTokenId('trader');
        $trader = Trader::where(['id'=>$this->getTokenId('trader')])->with(['items', 'orderDetails'])->get();
        if ($user) {
            return response()->json([
                'data' => TraderResource::collection($trader),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trader $trader)
    {
        $age = $trader->age;
        if ($this->getTokenId('user') || $this->getTokenId('trader')) {
            $trader->fill($request->input());
            if ($request->hasFile('img')) {
                $image_path = "assets/images/uploads/traders/".$trader->img;  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
                $file            = $request->file('img');
                $ext             = $file->getClientOriginalExtension();
                $filename        = time().'.'.$ext;
                $file->move('assets/images/uploads/traders/', $filename);
                $trader->img = $filename;
            }
            // $age = Carbon::createFromFormat('m/d/Y', $request->age)->format('Y-m-d');
            // $age = Carbon::parse($request->age)->format('Y-m-d');
            // $trader->age = Carbon::parse($age)->age;
            if ($request->age != null) {
                $trader->age = $request->age;
            } else {
                $trader->age = $age;
            }
            if ($trader->update()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم تعديل التاجر",
                    "data" => new TraderResource($trader),
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل تعديل التاجر",
                ], 422);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trader $trader)
    {
        if ($trader->units->count() == 0) {
            $trader->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف التاجر",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "التاجر لديه وحدات ولا يمكن حذفه",
            ], 422);
        }
    }
}
