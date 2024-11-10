<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brands;
use App\Traits\HasCrud;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    use HasCrud;

    public function __construct()
    {
        $this->model=Brands::class;
        $this->view='Brands/Index';
        $this->data=['brands'=>$this->model::all()];
       
    }

    // This will use the UserRequest for validation
    public function update(BrandRequest $request, $id)
    {
       $data=$request->validated();
       $data['updated_at']=now();
        $this->model::find($id)->update($data);
        $data=$this->model::all();
        return response()->json(['check'=>true,'data'=>$data]);
    }
}
