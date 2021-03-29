<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersApiController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $valid = $this->getAccess('user_access');
        if(!empty($valid)){
            return $valid;
        }
    
        return new UserResource(User::with(['roles'])->get());

    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(User $user)
    {
        // abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $valid = $this->getAccess('user_show');
        if(!empty($valid)){
            return $valid;
        }

        return new UserResource($user->load(['roles']));

    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);

    }

    public function destroy(User $user)
    {
        // abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $valid = $this->getAccess('user_delete');
        if(!empty($valid)){
            return $valid;
        }

        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function getAccess($option){
        if(Gate::denies($option)){
            return response()->json([ "message" => "Unauthorized" ], 403);
        }else{
            return null;
        }
    }
  
}
