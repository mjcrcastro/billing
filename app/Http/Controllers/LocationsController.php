<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers;
use App\Location;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Returns all storages to a view
        $action_code = 'locations_index';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        } 
        $filter = $request->get('filter');
        if ($filter) {
            //this query depends on the definition of 
            //function productDescriptors in the products model
            //productDescriptors returns all of this product descriptors
            $locations = Location::where('description', 'like', '%' . $filter . '%')
                    ->paginate(config('global.rows_page'));
        } else {
            $locations = Location::paginate(config('global.rows_page'));
        }
        
            return view('locations.index', compact('locations'))
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
        $action_code = 'locations_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }
            return view('locations.create');
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
        $action_code = 'locations_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
            $input = $request->all();
            
            $this->validate($request, Location::$rules);

                //if valid data, create a new shop
                $location = Location::create($input);
                //and return to the index
                return redirect()->route('locations.index')
                                ->with('message', 'Ubicacion ' . $location->description . ' creada');
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
        $action_code = 'locations_edit';
        $message = usercan($action_code, Auth::user());
        if ($message) { //I the user does not have permissions
            return redirect()->back()->with('message', $message);
        }
            //Actual code to execute
            $location = Location::find($id); //the the shop by the id

            if (is_null($location)) { //if no shop is found
                return redirect()->route('locations.index'); //go to previous page
            }
            //otherwise display the shop editor view
            return view('locations.edit', compact('location'));
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
        $action_code = 'location_update';
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
            'description' => 'required|unique:storages,description,'.$id.'id'
            ]);

                $location = Location::find($id);
                $location->update($input);
                return redirect()->route('locations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action_code = 'locations_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
            Location::find($id)->delete();
            return redirect()->route('locations.index');
    }
}
