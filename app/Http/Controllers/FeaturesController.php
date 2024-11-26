<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeatureRequest;
use App\Models\Features;
use App\Traits\HasCrud;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeaturesController extends Controller
{
    use HasCrud;

    public function __construct()
    {
        $this->model=Features::class;
        $this->view='Features/Index';
        $this->data=['features'=>$this->model::all()];

    }

    public function store (FeatureRequest $request){
        $data=$request->all();
        $data['slug']=Str::slug($request->title);
        $data['created_at']=now();
        $this->model::create($data);
        $data=$this->model::all();
        return response()->json(['check'=>true,'data'=>$data]);
    }
    // This will use the UserRequest for validation
    public function update(FeatureRequest $request, $id)
    {
       $data=$request->validated();
       $data['updated_at']=now();
        if($request->has('title')){
            $data['slug']=Str::slug($request->title);
        }
        $this->model::find($id)->update($data);
        $data=$this->model::all();
        return response()->json(['check'=>true,'data'=>$data]);
    }
}
