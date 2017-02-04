<?php

namespace App\Http\Controllers;

use App\Helpers;
//models below
use App\Product;
use App\Descriptor;
use App\DescriptorType;
use App\ProductDescriptor;
use App\ProductType;
use App\InvTransactionHeader;
use App\InvTransactionDetail;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //Return all products
        $action_code = 'products_index';

        $message = usercan($action_code, auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }//a return won't let the following code to continue
        $filter = $request->get('filter');
        if ($filter) {
            //this query depends on the definition of 
            //function productDescriptors in the products model
            //productDescriptors returns all of this product descriptors
            $products = Product::whereHas('productDescriptors', function($q) {
                        $q->where('description', 'like', '' . '%' . Input::get('filter') . '' . '%');
                    })->paginate(config('global/default.rows'));
            return view('products.index', compact('products'))
                            ->with('filter', $filter);
        } else {
            $products = Product::paginate(config::get('global/default.rows'));

            return view('products.index', compact('products'))
                            ->with('filter', $filter);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //list new product form

        $action_code = 'products_create';

        $message = usercan($action_code, Auth::user());

        if ($message) {
            return redirect()->back()->with('message', $message);
        }//a return won't let the following code to continue
        //to be used to check if the descriptor is already registered
        $descriptors = Descriptor::orderBy('description')->get()->pluck('description', 'id');

        //to be used by a modal form on descriptor registration
        $descriptor_types = DescriptorType::orderBy('description')->get()->pluck('description', 'id');

        return view('products.create', compact('descriptors', 'descriptor_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $action_code = 'products_store';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
        //a return won't let the following code to continue

        $descriptors = $request->get('descriptor_id');

        $existingProduct = $this->productGet($descriptors);

        // check if the product has already been created
        if (empty($existingProduct)) {//check for duplicate products
            $product = new Product;
            $product->save();
            //modify the array so that it includes the product id
            foreach ($descriptors as &$row) {
                $data[] = array('product_id' => $product->id,
                    'descriptor_id' => $row,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                );
            }
            ProductDescriptor::insert($data);
            return redirect()->route('products.index');
        } else {
            return redirect()->back()->with('message', $existingProduct . ' already registered');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //Return a kardex report for the selected product
        $action_code = 'products_show';

        $message = usercan($action_code, auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }//a return won't let the following code to continue
        //calculate the total from the previous page
       
        $transDetails = InvTransactionHeader::select(
                DB::raw('sum(product_cost*effect_inv) AS efe_cost'),
                DB::raw('sum(product_qty*effect_inv) AS efe_qty'))
                ->join('inv_transaction_details', 'inv_transaction_details.inv_transaction_header_id', '=', 'inv_transaction_headers.id')
                ->join('transaction_types', 'inv_transaction_headers.transaction_type_id', '=', 'transaction_types.id')
                ->where('inv_transaction_details.product_id','=',$id)
                ->groupBy('inv_transaction_details.product_id');

        $beforeCost = $transDetails->first('efe_cost');
        $beforeQty = $transDetails->first('efe_qty');
        
        $product = Product::find($id);
        $transactions = InvTransactionHeader::select(
                'product_qty', 
                'product_cost', 
                'document_date', 
                'document_number', 
                'note',
                'short_description',
                DB::raw('product_cost*effect_inv AS efe_cost'),
                DB::raw('product_qty*effect_inv AS efe_qty')
                )
                ->join('inv_transaction_details', 'inv_transaction_details.inv_transaction_header_id', '=', 'inv_transaction_headers.id')
                ->join('transaction_types', 'inv_transaction_headers.transaction_type_id', '=', 'transaction_types.id')
                ->where('inv_transaction_details.product_id', '=', $id)
                ->orderBy('document_date', 'desc')
                ->paginate(Config::get('global/default.rows'));
        return view('products.kardex', compact('product', 'transactions','beforeCost','beforeQty'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        //edit product
        $action_code = 'products_edit';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
        //Actual code to execute
        $product = Product::find($id);
        $productTypes = ProductType::orderBy('description', 'asc')
                ->pluck('description', 'id');
        if (is_null($product)) {
            return redirect()->route('products.index', array('product_type_id' => Input::get('product_type_id'),
                        'filter' => $request->get('filter'))
            );
        }
        return view('products.edit', compact('product', 'productTypes'), array('product_type_id' => $request->get('product_type_id'),
            'filter' => $request->get('filter')));
        // End of actual code to execute
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $action_code = 'products_update';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }
        //Actual code to execute
        //Receives and updates new role  data
        $input = $request->all();

        $this->validate($request, Product::$rules);

        $product = Product::find($id);
        $product->update($input);
        return redirect()->route(
                        'products.index', array(
                    'product_type_id' => $request->get('product_type_id'),
                    'filter' => $request->get('filter'))
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $action_code = 'products_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }//a return won't let the following code to continue
        Product::find($id)->delete();
        return redirect()->route('products.index');
    }

    /**
     * returns an empty array if no product, having
     * the given group of descriptors exists
     * returns the identified product otherwise
     *
     * @param  $descriptors array
     * @return \Illuminate\Http\Response
     */
    private function productGet($descriptors) {

        sort($descriptors); //sort descriptor Id so they are in the 
        //same order as in the query

        $filter = $this->toGroupCount($descriptors);

        if (Config::get('database.default') === 'mysql') {
            $havingRaw = "GROUP_CONCAT(DISTINCT descriptor_id ORDER BY descriptor_id) ='" . $filter . "'";
        } else {
            $havingRaw = "array_to_string(array_agg(descriptor_id), ', ') ='" . $filter . "'";
        }
        $productArray = DB::table('products_descriptors')->select('product_id')
                ->havingRaw($havingRaw)
                ->groupBy('product_id')
                ->get();

        if (count($productArray) === 0) {
            return null;
        } else {
            $descriptorString = '';
            foreach (Product::find($productArray->first()->product_id)->productDescriptors as $productdescriptor) {
                $descriptorString = $descriptorString . ' ' . $productdescriptor->descriptor->description . ' ';
            }
            return $descriptorString;
        }
    }

    private function toGroupCount($data) {
        //concatenate data in the array to
        //prepare the filter for the query used by
        //productGet
        static $filter = '';
        for ($nCount = 0; $nCount < sizeof($data); $nCount++) {
            $filter = $filter . $data[$nCount] . ',';
        }
        //cut the trailing ','
        return substr($filter, 0, strlen($filter) - 1);
    }

}
