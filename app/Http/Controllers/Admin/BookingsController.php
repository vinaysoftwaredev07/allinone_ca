<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transactions;
use App\Services;
use App\Bookings;
use App\Documents;
use App\Role;
use Gate;
use Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class BookingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('booking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = Bookings::all();

        return view('admin.booking.index', compact('bookings'));
    }

    public function create()
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        return view('admin.booking.create', compact('roles'));
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

        return redirect()->route('admin.booking.index');

    }

    public function edit(Services $service)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Documents::all()->pluck('name', 'id');

        return view('admin.booking.edit', compact('service', 'documents'));
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

        return redirect()->route('admin.booking.index');

    }

    public function show(Services $service)
    {
        abort_if(Gate::denies('booking_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.booking.show', compact('service'));
    }

    public function destroy(Services $service)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service->delete();

        return back()->with('Success', "Service Sucessfully deleted");

    }

    public function massDestroy(MassDestroyServiceRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
