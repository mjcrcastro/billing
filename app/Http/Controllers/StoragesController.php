<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class StoragesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Returns all storages to a view
        $action_code = 'storages_index';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        } 
        $filter = $request->get('filter');
        if ($filter) {
            //this query depends on the definition of 
            //function productDescriptors in the products model
            //productDescriptors returns all of this product descriptors
            $storages = Storage::where('description', 'like', '%' . $filter . '%')
                    ->paginate(config('global.rows_page'));
        } else {
            $storages = Storage::paginate(config('global.rows_page'));
        }
        
            return view('storages.index', compact('storages'))
                     ->with('filter', $filter);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Display form for creation of shops
        $action_code = 'storages_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }
            return view('storages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //name of the action code, a corresponding entry in actions table
        $action_code = 'storages_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
            $input = $request->all();
            
            $this->validate($request, Storage::$rules);

                //if valid data, create a new shop
                $storage = Storage::create($input);
                //and return to the index
                return redirect()->route('storages.index')
                                ->with('message', 'Storage ' . $storage->description . ' created');
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
        //Redirect to storages editor
        $action_code = 'storages_edit';
        $message = usercan($action_code, Auth::user());
        if ($message) { //I the user does not have permissions
            return redirect()->back()->with('message', $message);
        }
            //Actual code to execute
            $storage = Storage::find($id); //the the shop by the id

            if (is_null($storage)) { //if no shop is found
                return redirect()->route('storages.index'); //go to previous page
            }
            //otherwise display the shop editor view
            return view('storages.edit', compact('storage'));
            // End of actual code to execute
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $action_code = 'storage_update';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        } 
            //Actual code to execute
            //Receives and updates new shop data
            $input = $request->all();
            //make sure the description is unique but 
            //exclude the $id for the current shop
            $this->validate($request, [
            'description' => 'required|unique:storages,description,null,{{$id}}'
            ]);

                $storage = Storage::find($id);
                $storage->update($input);
                return redirect()->route('storages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action_code = 'storages_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
            Storage::find($id)->delete();
            return redirect()->route('storages.index');
    }
}
