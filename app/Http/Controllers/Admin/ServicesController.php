<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Services;
use App\Documents;
use App\Role;
use Gate;
use Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;


class ServicesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Services::all();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        abort_if(Gate::denies('service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        return view('admin.services.create', compact('roles'));
    }

    public function store(StoreServiceRequest $request)
    {
        $service = new Services();
        $service->name = $request->name;
        $service->description = $request->description;
        $filename = 'ca/services/'. str_replace(' ', '_', $request->name) . '/'. time() . $request->file('icon')->getClientOriginalName();
        $storage = Config::get('settings.storage');
        Storage::disk($storage)->put($filename, file_get_contents($request->file('icon')));
        $service->icon = Storage::disk($storage)->url($filename);
        $service->save();

        $service->documents()->sync($request->input('documents', []));

        return redirect()->route('admin.services.index');

    }

    public function edit(Services $service)
    {
        abort_if(Gate::denies('service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Documents::all()->pluck('name', 'id');

        return view('admin.services.edit', compact('service', 'documents'));
    }

    public function update(UpdateServiceRequest $request, Services $service)
    {
        $service->name = $request->name;
        $service->description = $request->description;
        if(!empty($request->file('icon'))){
            $filename = 'ca/services/'. str_replace(' ', '_', $request->name) . '/'. time() . $request->file('icon')->getClientOriginalName() ;
            $storage = Config::get('settings.storage');
            Storage::disk($storage)->put($filename, file_get_contents($request->file('icon')));
            $service->icon = ($storage == 's3') ? Storage::disk($storage)->url($filename) : Storage::disk($storage)->url($filename);
        }
        $service->save();

        $service->documents()->sync($request->input('documents', []));

        return redirect()->route('admin.services.index');

    }

    public function show(Services $service)
    {
        abort_if(Gate::denies('service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.services.show', compact('service'));
    }

    public function destroy(Services $service)
    {
        abort_if(Gate::denies('service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service->delete();

        return back()->with('Success', "Service Sucessfully deleted");

    }

    public function massDestroy(MassDestroyServiceRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
