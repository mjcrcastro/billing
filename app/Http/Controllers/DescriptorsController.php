<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Descriptor;
use App\DescriptorType;

class DescriptorsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        //Return all descriptors

        $action_code = 'descriptors_index';

        $message = usercan($action_code, auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }// no need for else, since the redirect will take me out

        $descriptor_type_id = $request->get('descriptor_type_id');

        $filter = $request->get('filter');

        $descriptors_label = $this->getDescriptorsLabel($descriptor_type_id, $filter);

        $descriptors = $descriptors_label['descriptors'];

        $label = $descriptors_label['label'];

        return view('descriptors.index', compact('descriptors'))
                        ->with('descriptor_type_id', $descriptor_type_id)
                        ->with('filter', $filter)
                        ->with('label', $label);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        //Display form for creation descriptors

        $action_code = 'descriptors_create';

        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
        $descriptor_type_id = $request->get('descriptor_type_id');
        $descriptorTypes = DescriptorType::orderBy('description', 'asc')
                ->pluck('description', 'id');

        $label = '';

        if ($descriptor_type_id) {
            //say that the descriptor is of a specific descriptor type
            $label = ' for ' . DescriptorType::find($descriptor_type_id)
                    ->description;
        }

        return view('descriptors.create')
                        ->with('descriptor_type_id', $descriptor_type_id)
                        ->with('descriptorTypes', $descriptorTypes)
                        ->with('label', $label);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $action_code = 'descriptors_store';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
        //Save new user data
        $input = request()->all();

        $this->validate($request, [
            'description' => 'required|unique:descriptors,description,null,{{$id}}',
            'descriptor_type_id' => 'required'
        ]);

        $descriptor = Descriptor::create($input);

        $descriptor_type_id = $descriptor->descriptor_type_id;

        if (request()->wantsJson()) {
            return response()->json($descriptor);
        }

        return redirect()->route('descriptors.index', array(
                    'descriptor_type_id' => $descriptor_type_id,
                    'filter' => $request->get('filter')
                        )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id) {
        //Redirect to Company editor
        $message = usercan('descriptors_edit', Auth::user());
        if ($message) {return redirect()->back()->with('message', $message);}
        //Actual code to execute
        $descriptor = Descriptor::find($id);
        $descriptor_types = DescriptorType::orderBy('description', 'asc')
                ->pluck('description', 'id');

        if (is_null($descriptor)) {
            return redirect()->route('descriptors.index', 
                    array(
                        'descriptor_type_id' => $request->get('descriptor_type_id'),
                        'filter' => $request->get('filter')
                    )
            );
        }
        return view('descriptors.edit', 
                compact('descriptor', 'descriptor_types'), 
                array(
                    'descriptor_type_id' => $request->get('descriptor_type_id'),
                    'filter' => $request->get('filter')
                        )
        );
        // End of actual code to execute
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {

        $action_code = 'descriptors_update';
        $message = usercan($action_code, Auth::user());
        if ($message) {return redirect()->back()->with('message', $message);}
        //Actual code to execute
        //Receives and updates new role  data
        $input = $request->all();

        $this->validate($request, [
            'description' => 'required|unique:descriptors,description,null,{{$id}}',
            'descriptor_type_id' => 'required'
        ]);

        $descriptor = Descriptor::find($id);
        $descriptor->update($input);
        return redirect()->route(
                'descriptors.index', 
                array(
                    'descriptor_type_id' => $request->get('descriptor_type_id'),
                    'filter' => $request->get('filter')
                )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
        //
        $action_code = 'descriptors_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {return redirect()->back()->with('message', $message);}
        
        $descriptor = Descriptor::find($id);
        
        $descriptor_type_id = $request->get('descriptor_type_id');
        
        $descriptor->delete();

        return redirect()->route(
                'descriptors.index', 
                array(
                    'descriptor_type_id' => $descriptor_type_id));
    }

    /*
     * Returns a json string with all desciptors from filter
     */

    public function jdescriptors() {

        $action_code = 'descriptors_index';

        $message = Helper::usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }

        if (Request::ajax()) {

            $filter = Input::get('term');
            //Will use the show function to return a json for ajax
            $descriptors = Descriptor::orderBy('description', 'asc')
                    ->where('description', 'like', '%' . strtolower($filter) . '%')
                    ->get();
            return Response::json($descriptors);
        } else {
            return Response::make("Page not found", 404);
        }
    }

    private function getDescriptorsLabel($descriptor_type_id, $filter) {
        //returns descriptors array and label for use in index
        $label = '';
        if ($filter and $descriptor_type_id) {
            $descriptors = Descriptor::orderBy('description', 'asc')
                    ->where('description', 'like', '%' . $filter . '%')
                    ->where('descriptor_type_id', '=', $descriptor_type_id);
            $label = ' for ' . DescriptorType::find($descriptor_type_id)
                    ->description;
        } elseif ($descriptor_type_id) {
            $descriptors = Descriptor::orderBy('description', 'asc')
                    ->where('descriptor_type_id', '=', $descriptor_type_id);
            $label = ' for ' . DescriptorType::find($descriptor_type_id)
                    ->description;
        } elseif ($filter) {
            $descriptors = Descriptor::orderBy('description', 'asc')
                    ->where('description', 'like', '%' . $filter . '%');
        } else {
            $descriptors = Descriptor::orderBy('description', 'asc');
        }
        return array('descriptors' => $descriptors->paginate(7), 'label' => $label);
    }

}
