<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Http\Requests\RoleRequest;
use App\Traits\HasCrud;
use Inertia\Inertia;

class RolesController extends Controller
{
    use HasCrud;

    public function __construct()
    {
        $this->model=Roles::class;
        $this->view='Roles/Index';
        $this->data=['roles'=>$this->model::all()];
       
    }

    // This will use the UserRequest for validation
    public function update(RoleRequest $request, $id)
    {
       $data=$request->validated();
       $data['updated_at']=now();
        $this->model::find($id)->update($data);
        $data=$this->model::all();
        return response()->json(['check'=>true,'data'=>$data]);
    }
}
