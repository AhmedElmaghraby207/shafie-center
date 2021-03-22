@extends('admin.layout.app')

@section('title') Edit Profile @endsection

@section('content_header')@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-colored-controls">Edit Profile</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collpase show">
                    <div class="card-body">
                        <form class="form form-horizontal" method="POST" action="{{ route('admin.update', $admin->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="name">Name</label>
                                            <div class="col-md-10">
                                                <input type="text" id="name" class="form-control border-primary"
                                                       placeholder="Name" name="name" value="{{$admin->name}}">
                                                @if ($errors->has('name'))
                                                    <div class="error" style="color: red">
                                                        <i class="fa fa-sm fa-times-circle"></i>
                                                        {{ $errors->first('name') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="email">Email</label>
                                            <div class="col-md-10">
                                                <input type="email" id="email" class="form-control border-primary"
                                                       placeholder="Email" name="email" value="{{$admin->email}}">
                                                @if ($errors->has('email'))
                                                    <div class="error" style="color: red">
                                                        <i class="fa fa-sm fa-times-circle"></i>
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="password">Password</label>
                                            <div class="col-md-10">
                                                <input type="password" id="password" class="form-control border-primary"
                                                       placeholder="Password"
                                                       name="password">
                                                @if ($errors->has('password'))
                                                    <div class="error" style="color: red">
                                                        <i class="fa fa-sm fa-times-circle"></i>
                                                        {{ $errors->first('password') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control"
                                                   for="password_confirmation">Confirm</label>
                                            <div class="col-md-10">
                                                <input type="password" id="password_confirmation"
                                                       class="form-control border-primary"
                                                       placeholder="Confirm Password"
                                                       name="password_confirmation">
                                                @if ($errors->has('password_confirmation'))
                                                    <div class="error" style="color: red">
                                                        <i class="fa fa-sm fa-times-circle"></i>
                                                        {{ $errors->first('password_confirmation') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center mb-2">
                                            <img id="image_preview" height="161"
                                                 src="{{$admin->image ? url($admin->image) : url('/app-assets/images/portrait/medium/avatar-m-4.png')}}"
                                                 class="rounded-circle" alt="Card image">
                                        </div>
                                        <fieldset class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="image"
                                                       id="image_to_preview">
                                                <label class="custom-file-label" for="image">Choose image</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <a href="{{url('/dashboard')}}" class="btn btn-warning mr-1">
                                    <i class="ft-x"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="la la-check-square-o"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
