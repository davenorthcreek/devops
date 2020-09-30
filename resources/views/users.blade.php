@extends('adminlte::page')

@section('content')

                <div class="body">
                    <h3>List of Users (Admin User: {{Auth::user()->name}})</h3>
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Last Visit</th>
                                <th>Delete User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $listed_user)
                                <tr>
                                    <td><a href={{url("user/".$listed_user->id."/edit") }}>{{$listed_user->id}}</a></td>
                                    <td><a href={{url("user/".$listed_user->id."/edit") }}>{{$listed_user->name}}</a></td>
                                    <td>{{$listed_user->email}}</td>
                                    <td>@if($listed_user->last_seen)
                                            {{$listed_user->last_seen}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$listed_user->is_admin)
                                            <a href='{{ url('delete_user/'.$listed_user->id) }}'>
                                                <button type="submit" class="btn btn-danger">Delete User</button>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <a href="{{url("user/create")}}">
                        <button type="submit" class="btn btn-primary">Add a New User</button>
                    </a>
                </div>
@endsection

@section('localScripts')
<script>

$(document).ready(function() {
    $('#datatable').DataTable( {
        "scrollX": true
    } );
} );
</script>
@endsection
