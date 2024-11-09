<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Generic store function to be used in child controllers.
     *
     * @param  FormRequest  $request  The specific request class to validate data.
     * @param  string  $modelClass  The model class to instantiate and save.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_ft(FormRequest $request, string $modelClass)
    {
        $validatedData = $request->validated();
        $model = new $modelClass;
        $model->fill($validatedData);
        $model->save();

        return response()->json(['data' =>$model::all(), 'check'=>true], 201);
    }

    /**
     * Generic update function to be used in child controllers.
     *
     * @param  FormRequest  $request  The specific request class to validate data.
     * @param  string  $modelClass  The model class to update.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_ft(FormRequest $request, string $modelClass, $id)
    {
        $validatedData = $request->all();
        $model = $modelClass::findOrFail($id);
        $model->update($validatedData);
        return response()->json(['check'=>true,'data' => $model::all(), 'msg' => 'Updated successfully'], 200);
    }
}
