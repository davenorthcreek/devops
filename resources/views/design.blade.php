@extends('adminlte::page')

@section('title', 'LiveData DevOps Dashboard Design')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard Design</h1>
@stop


@section('content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">

				<!-- Default box -->
				<div class="box">
					<div class="box-body">
                        <form action="{{url('/dashboard/edit')}}" method='POST'>
                            {{csrf_field()}}
                            @if($dashboard)
                                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Existing Tile</th>
                                            <th>Position</th>
                                            <th>Delete Tile</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dashboard->assignments as $assignment)
                                            <?php $assigned = $assignment->tile;
                                            ?>
                                            <tr>
                                                <td>
                                                    <select id="tile_{{$assignment->id}}" name="tile_{{$assignment->id}}">
                                                        @foreach($tiles as $tile)
                                                            <option VALUE={{$tile->id}}
                                                                @if($tile->id == $assigned->id)
                                                                    SELECTED
                                                                @endif
                                                                >
                                                                {{$tile->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <!--a href='{{url("/tile/".$assignment->tile->id)}}'>
                                                        {{$assignment->tile->name}}
                                                    </a-->
                                                </td>
                                                <td>
                                                    <input
                                                        type="text"
                                                        name="position_{{$assignment->id}}"
                                                        id="position_{{$assignment->id}}"
                                                        value="{{$assignment->position}}">
                                                </td>
                                                <td>
                                                    <button type="submit"
                                                        name="delete" value="{{$assignment->id}}"
                                                        class="delete bg-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <table>
                                <table id="new_datatable" class="table table-bordered" cellspacing="0" width="100%">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Add A Tile</th>
                                            <th>Position (Required)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <select id="new_tile" name="new_tile">
                                                @foreach($tiles as $tile)
                                                    <option VALUE={{$tile->id}}>
                                                        {{$tile->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="new_position" id="new_position">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Update Dashboard</button>
                        </form>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

			</div>
		</div>
	</div>
@endsection

@push('js')
    <script>
        $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
            if(e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endpush
