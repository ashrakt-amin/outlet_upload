<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelImage;
use Illuminate\Http\Request;

class LevelImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $modelName, $relationColumnName, $relationColumnId)
    {
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $image) {
                    $name            = $image->getClientOriginalName();
                    $ext             = $image->getClientOriginalExtension();
                    $filename        = rand(10, 100000).time().'.'.$ext;
                    $image->move('assets/images/uploads/items/', $filename);

                    // $relationColumnName = $relationColumn.'_id';

                    $relationColumnNameImage = new $modelName();
                    $relationColumnNameImage->$relationColumnName = $relationColumnId;
                    $relationColumnNameImage->img                = $filename;
                    $relationColumnNameImage->save();
                // $levelImage = new LevelImage();
                // $levelImage->item_id = $item;
                // $levelImage->img     = $this->aspectForResize($image, $item, 600, 450, 'items');
                // $levelImage->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LevelImage  $levelImage
     * @return \Illuminate\Http\Response
     */
    public function show(LevelImage $levelImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LevelImage  $levelImage
     * @return \Illuminate\Http\Response
     */
    public function edit(LevelImage $levelImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LevelImage  $levelImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LevelImage $levelImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LevelImage  $levelImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(LevelImage $levelImage)
    {
        //
    }
}
