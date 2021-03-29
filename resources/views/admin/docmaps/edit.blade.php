@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.docmaps.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.docmaps.update", [$service->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="" for="name">{{ trans('cruds.docmaps.fields.name') }}</label>
                <input class="form-control {{ $errors->has('service') ? 'is-invalid' : '' }}" readonly type="text" name="name" id="name" value="{{ old('name', $service->name) }}">
                <input class="form-control {{ $errors->has('service') ? 'is-invalid' : '' }}" type="hidden" name="service" id="service" value="{{ old('service', $service->id) }}">
                @if($errors->has('service'))
                    <span class="text-danger">{{ $errors->first('service') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.docmaps.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="documents">{{ trans('cruds.docmaps.fields.document') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('document') ? 'is-invalid' : '' }}" name="documents[]" id="documents" multiple required>
                    @foreach($documents as $service_id => $document)
                        <option value="{{ $service_id }}" {{ (in_array($service_id, old('documents', [])) || $service->documents->contains($service_id)) ? 'selected' : '' }}>{{ $document }}</option>
                    @endforeach
                </select>
                @if($errors->has('document'))
                    <span class="text-danger">{{ $errors->first('document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.docmaps.fields.document_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

</script>

@endsection