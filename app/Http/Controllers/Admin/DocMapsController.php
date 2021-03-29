<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services;
use App\Documents;
use App\DocMaps;
use App\Role;
use Gate;
use Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocMapsRequest;

class DocMapsController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('docmaps_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Services::all();

        return view('admin.docmaps.index', compact('services'));
    }

    public function create()
    {
        abort_if(Gate::denies('docmaps_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Services::all();

        return view('admin.docmaps.create', compact('services'));
    }

    public function store(StoreDocumentRequest $request)
    {
        $document = new Documents();
        $document->name = $request->name;
        $document->description = $request->description;
        if($request->file('sample')){
            $filename = 'ca/documents/samples/'. str_replace(' ', '_', $request->name) . '/'.$request->file('sample')->getClientOriginalName() . time();
            $storage = Config::get('settings.storage');
            Storage::disk($storage)->put($filename, file_get_contents($request->file('sample')));
            $document->icon = ($storage == 's3') ? Storage::disk($storage)->url($filename) : url('/') . Storage::disk($storage)->url($filename);
        }
        $document->save();

        return redirect()->route('admin.docmaps.index');

    }

    public function edit(Request $request, Services $service)
    {
        abort_if(Gate::denies('docmaps_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $segmentArr = $request->segments(); 
        $id = $segmentArr[2];
        $service = Services::findorfail($id);

        $documents = Documents::all()->pluck('name', 'id');
        
        $service->load('documents');
        // echo "<pre>"; print_r($service); exit();
        
        return view('admin.docmaps.edit', compact('documents', 'service'));
    }

    public function update(UpdateDocMapsRequest $request, Services $service)
    {
        abort_if(Gate::denies('docmaps_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $segmentArr = $request->segments(); 
        $id = $segmentArr[2];
        $service = Services::findorfail($id);
        // $docmap = $service::where('service_id', $id)->get();
        // echo "<pre>"; print_r($docmap->toArray()); exit;
        // echo "<pre>"; print_r($request->all()); exit;
        // echo "<pre>"; print_r($service->documents); exit;
        // $service->update($request->all());
        $service->documents()->attach($request->input('documents', []));

        return redirect()->route('admin.docmaps.index');

    }

    public function show(Services $service)
    {
        abort_if(Gate::denies('docmaps_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.docmaps.show', compact('service'));
    }

    public function destroy(Services $service)
    {
        abort_if(Gate::denies('docmaps_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service->delete();

        return back()->with('Success', "Service Sucessfully deleted");

    }

    public function massDestroy(MassDestroyServiceRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
