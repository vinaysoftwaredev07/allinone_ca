@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.docmaps.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.docmaps.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.docmaps.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.docmaps.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="icon">{{ trans('cruds.docmaps.fields.service') }}</label>
                <div class="form-inline">
                    <select class="form-control" name="service" id="service">
                        <option value="">Select</option>
                        @foreach($services as $key => $service)
                            <option value="{{ $service->id }}" {{ (old('service') == $service->id) ? 'Selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($errors->has('icon'))
                    <span class="text-danger">{{ $errors->first('service') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.docmaps.fields.service_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="" for="icon">{{ trans('cruds.docmaps.fields.sample') }}</label>
                <div class="form-inline">
                    <input class="form-control {{ $errors->has('icon') ? 'is-invalid' : '' }}" type="file" name="icon" id="icon" value="{{ old('icon') }}" onchange="loadFile(event)" />
                    <img src="{{ old('icon') ?? '' }}" id="output" width="50px" height="50px" />
                </div>
                @if($errors->has('icon'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.docmaps.fields.sample_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.docmaps.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description') }}" required>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.docmaps.fields.description_helper') }}</span>
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