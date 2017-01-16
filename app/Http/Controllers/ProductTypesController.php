<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\ProductType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class ProductTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Returns all shops to a view
        $action_code = 'productTypes_index';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        } else {
            $productTypes = ProductType::paginate(7);
            return view('producttypes.index', compact('productTypes'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         //Display form for creation of product types
        $action_code = 'productTypes_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        } else {
            return view('productTypes.create');
        }
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
        $action_code = 'productTypes_store';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
            $input = $request->all();
            $this->validate($request, ProductType::$rules);

                //if valid data, create a new shop
                $productType = ProductType::create($input);
                //and return to the index
                return redirect()->route('productTypes.index')
                                ->with('message', 'Descriptor Type ' . $productType->description . ' created');
                
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
        //Redirect to product types editor
        $action_code = 'productTypes_edit';
        $message = usercan($action_code, Auth::user());
        if ($message) { //I the user does not have permissions
            return redirect()->back()->with('message', $message);
        } 
            //Actual code to execute
            $productType = ProductType::find($id); //the the shop by the id

            if (is_null($productType)) { //if no shop is found
                return redirect()->route('productTypes.index'); //go to previous page
            }
            //otherwise display the product type editor view
            return view('productTypes.edit', compact('productType'));
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
        $action_code = 'productTypes_update';
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
            'description' => 'required|unique:product_types,description,null,{{$id}}'
            ]);

                $productType = ProductType::find($id);
                $productType->update($input);
                return redirect()->route('productTypes.index');
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
        $action_code = 'productTypes_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
            ProductType::find($id)->delete();
            return redirect()->route('productTypes.index');
    }
}
