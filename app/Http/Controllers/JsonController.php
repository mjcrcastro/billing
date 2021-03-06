<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\Descriptor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Config;
use App\Product;
use App\Storage;
use App\InvTransactionDetail;

class JsonController extends Controller {
    /*
     * Returns a json string with all desciptors from filter
     */

    public function descriptors(Request $request) {

        $action_code = 'descriptors_index';

        $message = usercan($action_code, Auth::user());

        if ($message) {
            return Redirect::back()->with('message', $message);
        }

        if ($request->ajax()) {//only return data to ajax calls
            $filter = $request->get('term');

            $descriptors = Descriptor::select('descriptors.id as descriptor_id', 'descriptors.description as label', 'descriptor_types.description as category', 'descriptors.descriptor_type_id')
                            ->join('descriptor_types', 'descriptors.descriptor_type_id', '=', 'descriptor_types.id')
                            ->whereRaw("LOWER(descriptors.description) like '%" .
                                    strtolower($filter) . "%'")
                            ->orderBy('descriptor_types.id', 'asc')
                            ->orderBy('descriptors.description', 'asc')->get();

            return response()->json($descriptors);
        } else {
            return response()->make("Unable to comply request", 404);
        }
    }

    public function products(Request $request) {

        $action_code = 'products_list_json';

        $message = usercan($action_code, Auth::user());

        if ($message) {
            return redirect()->back()->with('message', $message);
        }

        if ($request->ajax()) {//return json data only to ajax queries
            $filter = Input::get('search.value');

            $products = $this->getProducts($filter);
            
            $response['draw'] = Input::get('draw');

            $response['recordsTotal'] = Product::all()->count();

            $response['recordsFiltered'] = count($products);

            $response['data'] = array_slice($products, Input::get('start'), Input::get('length'));

            return response()->json($response);
        }
    }

    /*
     * Receives a seach string and converts every word into individual
     * words that are used to prepare a havingRaw clause for a search of product
     * in function $this->products
     */

    private function getHavingRaw($search_string) {

        $searchArray = explode(" ", $search_string);

        static $having = '';
        //creates a having query for each incoming word
        for ($nCount = 0; $nCount < sizeof($searchArray); $nCount++) {
            if (Config::get('database.default') === 'mysql') {
                $having .= " AND GROUP_CONCAT(descriptors.description) " .
                        "like '%" . strtolower(ltrim(rtrim($searchArray[$nCount]))) . "%'";
            } else {
                $having .= " AND string_agg(LOWER(descriptors.description), ' ' ORDER BY descriptors.\"descriptorType_id\") " .
                        "like  '%" . strtolower(ltrim(rtrim($searchArray[$nCount]))) . "%'";
            }
        }

        return substr($having, 5, strlen($having) - 5);
    }

    private function getProducts($filter) {
        
        $products_array = array();
        if ($filter) {

            $products = Product::select('products.id')
                    ->join('products_descriptors', 'products_descriptors.product_id', '=', 'products.id')
                    ->join('descriptors', 'descriptors.id', '=', 'products_descriptors.descriptor_id')
                    ->groupBy('products.id')
                    ->havingRaw($this->getHavingRaw(trim($filter)))
                    ->distinct()
                    ->get();
        } else {
            $products = Product::get();
        }
        $storages = Storage::get();
        foreach ($products as $product) {
               $notes = " [";
                foreach ($storages as $storage) {
                    $notes = $notes.$storage->description."(".$this->getQtyStorage($product->id, $storage->id).")";
                }
                $notes = $notes."]";
                $products_array[] = [
                'product_id'=>$product->id,
                'product_description'=>$product->productDescription()->first()->description,
                'notes'=>$notes,
                'qty'=>number_format($product->total_qty, 2, '.', ',')
            ];
         } 
        
        return $products_array;
    }
    
    private function getQtyStorage($product_id, $storage_id) {
        $product_balance = InvTransactionDetail::selectRaw('sum(product_qty*transaction_types.effect_inv) AS totalQty')
                        ->join('inv_transaction_headers', 
                                'inv_transaction_details.inv_transaction_header_id', 
                                '=', 'inv_transaction_headers.id')
                        ->join('transaction_types', 
                                'inv_transaction_headers.transaction_type_id', 
                                '=', 'transaction_types.id')
                        ->where('storage_id',"=",$storage_id)
                        ->where('product_id',"=",$product_id)
                        ->groupBy('inv_transaction_details.product_id');
        if (!empty($product_balance->first()->totalQty)) {
            return $product_balance->first()->totalQty;
        }else{
            return 0;
        }
    }

}
