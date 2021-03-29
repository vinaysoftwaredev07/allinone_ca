<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Documents;
use App\Services;
use App\Role;
use Gate;
use Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;

class DocumentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Documents::all();

        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        abort_if(Gate::denies('document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Services::all();

        return view('admin.documents.create', compact('services'));
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

        return redirect()->route('admin.documents.index');

    }

    public function edit(Documents $document)
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.documents.edit', compact('service'));
    }

    public function update(UpdateDocumentRequest $request, Documents $document)
    {
        $document->name = $request->name;
        $document->description = $request->description;
        if($request->file('sample')){
            $filename = 'ca/documents/samples/'. str_replace(' ', '_', $request->name) . '/'.$request->file('sample')->getClientOriginalName() . time();
            $storage = Config::get('settings.storage');
            Storage::disk($storage)->put($filename, file_get_contents($request->file('sample')));
            $document->icon = ($storage == 's3') ? Storage::disk($storage)->url($filename) : url('/') . Storage::disk($storage)->url($filename);
        }
        $document->save();

        return redirect()->route('admin.documents.index');

    }

    public function show(Documents $document)
    {
        abort_if(Gate::denies('document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.documents.show', compact('document'));
    }

    public function destroy(Documents $document)
    {
        abort_if(Gate::denies('document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->delete();

        return back()->with('Success', "Service Sucessfully deleted");

    }

    public function massDestroy(MassDestroyServiceRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
