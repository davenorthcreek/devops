<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['dashboard'] = Auth::user()->dashboard;
        $data['tiles'] = \App\Models\AvailableTile::all();
        return view('design')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $design_updated = false;
        $user = Auth::user();
        $dashboard = $user->dashboard;
        if (!$dashboard) {
            $dashboard = new \App\Models\Dashboard();
            $dashboard->user_id = $user->id;
            $dashboard->save();
        }
        if ($request->delete) {
            $assignment = \App\Models\Assignment::find($request->delete);
            $assignment->delete();
        }
        foreach($dashboard->assignments as $assignment) {
            $id = $assignment->id;
            $updated = false;
            $submitted = $request->get("tile_".$id);
            if ($submitted != $assignment->tile_id) {
                $assignment->tile_id = $submitted;
                $updated = true;
            }
            $submitted_pos = $request->get("position_".$id);
            if ($submitted_pos != $assignment->position) {
                $assignment->position = $submitted_pos;
                $updated = true;
            }
            $design_updated = $updated;
            if ($updated) {
                $assignment->save();
            }
        }
        if ($request->new_position) {
            $design_updated = true;
            Log::debug($request->new_tile.' '.$request->new_position);
            $new_assignment = new \App\Models\Assignment();
            $new_assignment->position = $request->new_position;
            $tile = \App\Models\AvailableTile::find($request->new_tile);
            $new_assignment->tile_id = $tile->id;
            Log::debug($new_assignment);
            $dashboard->assignments()->save($new_assignment);
        }
        $this->generate_new_blade();
        return redirect('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function generate_new_blade() {
        $user = Auth::user();
        if ($user->dashboard) {
            $folder_path = base_path().'/resources/views/users/'.$user->id;
            Log::debug("folder to be created ".$folder_path);
            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true);
                Log::debug("Folder created");
            }
            $path = $folder_path.'/dashboard-blade-view.blade.php';
            $string = "<x-dashboard>".PHP_EOL;
            $user->dashboard->refresh();
            foreach ($user->dashboard->assignments as $tile) {
                Log::debug($tile->tile->name.':'.$tile->position);
                $string.= "    <livewire:".$tile->tile->name.' position="'.$tile->position.'" />'.PHP_EOL;
            }
            $string.= "</x-dashboard>".PHP_EOL;
            file_put_contents($path, $string);
            Log::debug("wrote new blade template");
        }
    }

    /**
    * Create correctly writable folder.
    * Check if folder exist and writable.
    * If not exist try to create it one writable.
    *
    * @return bool
    *     true folder has been created or exist and writable.
    *     False folder not exist and cannot be created.
    */
    private function createWritableFolder($folder, $mode)
    {
        if($folder != '.' && $folder != '/' ) {
            $this->createWritableFolder(dirname($folder), $mode);
        }
        if (file_exists($folder)) {
            return is_writable($folder);
        }

        return is_writable($folder) && mkdir($folder, $mode, true);
    }

}
