@extends('adminlte::page')

@section('content')

    <div style="display: table">
        <h1 style="display: table-cell; vertical-align: bottom; height: 200px; text-align: center">{{$message}}</h1>
    </div>
                <div class="body">
                    @php
                        Log::debug($errors);
                    @endphp
                <div class="tab-pane" id="settings">
                  <form class="form-horizontal" action="{{url('/user_update/'.$user->id)}}" method='POST'>
                      {{csrf_field()}}

                    <div class="form-group">
                      <label for="name" class="col-sm-2 control-label">Name</label>

                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" autocomplete="off" value="{{$user->name}}">
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                      <label for="email" class="col-sm-2 control-label">Email</label>

                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" autocomplete="off" value="{{$user->email}}" required>
                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                      <label for="old_password" class="col-sm-2 control-label">Old Password</label>

                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="old_password" name="old_password" autocomplete="off" placeholder="********">
                        @if ($errors->has('old_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('old_password') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                      <label for="password" class="col-sm-2 control-label">New Password</label>

                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                      <label for="password-confirm" class="col-sm-2 control-label">Confirm Password</label>

                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="password-confirm" name="password_confirmation" autocomplete="new-password" placeholder="Repeat New Password">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    @if(!$user->is_admin && Auth::user()->is_admin)
                        <div class="form-group{{ $errors->has('is_admin') ? ' has-error' : '' }}">
                          <label for="is_admin" class="col-sm-2 control-label">Make Admin</label>

                          <div class="col-sm-10">
                            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
                            @if ($errors->has('is_admin'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('is_admin') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                    @endif
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">

                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger">Submit</button>
                      </div>
                    </div>
                  </form>
                </div>
            </div>
@endsection
