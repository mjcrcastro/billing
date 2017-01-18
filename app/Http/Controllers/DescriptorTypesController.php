<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\DescriptorType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class DescriptorTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //Returns all shops to a view
        $action_code = 'descriptorTypes_index';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        } else {
            $descriptorTypes = DescriptorType::paginate(7);
            return view('descriptorTypes.index', compact('descriptorTypes'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //Display form for creation of shops
        $action_code = 'descriptorTypes_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        } else {
            return view('descriptorTypes.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        //name of the action code, a corresponding entry in actions table
        $action_code = 'descriptorsTypes_store';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        } else {
            $input = $request->all();
            
            $this->validate($request, DescriptorType::$rules);

                //if valid data, create a new shop
                $descriptorType = DescriptorType::create($input);
                //and return to the index
                return redirect()->route('descriptorTypes.index')
                                ->with('message', 'Descriptor Type ' . $descriptorType->description . ' created');
                
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    //I do not actually use this function since is is a simple object
    public function show($id) {
        $action_code = 'descriptorsTypes_show';
        $message = Helper::usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        } else {
            //
            return Redirect::to('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //Redirect to Shops editor
        $action_code = 'descriptorTypes_edit';
        $message = usercan($action_code, Auth::user());
        if ($message) { //I the user does not have permissions
            return redirect()->back()->with('message', $message);
        } else { //is the user has permissions
            //Actual code to execute
            $descriptorType = DescriptorType::find($id); //the the shop by the id

            if (is_null($descriptorType)) { //if no shop is found
                return redirect()->route('descriptorTypes.index'); //go to previous page
            }
            //otherwise display the shop editor view
            return view('descriptorTypes.edit', compact('descriptorType'));
            // End of actual code to execute
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {

        $action_code = 'descriptorTypes_update';
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
            'description' => 'required|unique:descriptor_types,description,null,{{$id}}'
            ]);

                $descriptorType = DescriptorType::find($id);
                $descriptorType->update($input);
                return redirect()->route('descriptorTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
        $action_code = 'descriptorTypes_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        } else {
            DescriptorType::find($id)->delete();
            return redirect()->route('descriptorTypes.index');
        }
    }
}
