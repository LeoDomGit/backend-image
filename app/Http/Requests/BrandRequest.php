<?php

namespace App\Http\Requests;

use App\Models\Brands;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $id = $this->route('brand');
        if ($this->isMethod('POST')) {
            return [
               'name'=>'required|unique:brands,name',
            ];
        } else if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'name'=>'required|unique:brands,name',
             ];
        }else if ($this->isMethod('delete')) {
            $feature = Brands::find($id);
            if (!$feature) {
                throw new HttpResponseException(response()->json([
                    'check' => false,
                    'msg'   => 'Role id  not found',
                ], 200)); 
            }
        }
        return [];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'check' => false,
            'msg'  => $validator->errors()->first(),
            'errors'=>$validator->errors(),
            'data'=>Brands::all()
        ], 200));
    }
}
