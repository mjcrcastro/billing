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

            $descriptors = Descriptor::select('descriptors.id as descriptor_id', 
                    'descriptors.description as label', 
                    'descriptor_types.description as category', 
                    'descriptors.descriptor_type_id')
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

            $dbRaw = $this->getDbRaw();

            $products = $this->getProducts($filter, $dbRaw);

            $response['draw'] = Input::get('draw');

            $response['recordsTotal'] = Product::all()->count();

            $response['recordsFiltered'] = $products->get()->count();

            $response['data'] = $products
                    ->skip(Input::get('start'))
                    ->take(Input::get('length'))
                    ->get();

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

    private function getDbRaw() {
        if (Config::get('database.default') === 'mysql') {

            $dbRaw = "GROUP_CONCAT(DISTINCT descriptors.description "
                    . "ORDER BY descriptors.descriptor_type_id SEPARATOR ' ') "
                    . "as product_description";
        } else {

            $dbRaw = "string_agg(descriptors.description, ' ' "
                    . "ORDER BY descriptors.\"descriptor_type_id\") "
                    . "as product_description";
        }

        return $dbRaw;
    }

    private function getProducts($filter, $dbRaw) {
        if ($filter) {

            $products = Product::select('products.id as product_id', DB::raw($dbRaw))
                    ->join('products_descriptors', 'products_descriptors.product_id', '=', 'products.id')
                    ->join('descriptors', 'descriptors.id', '=', 'products_descriptors.descriptor_id')
                    ->groupBy('products.id')
                    ->havingRaw($this->getHavingRaw(trim($filter)));
        } else {
            $products = Product::select('products.id as product_id', DB::raw($dbRaw))
                    ->join('products_descriptors', 'products_descriptors.product_id', '=', 'products.id')
                    ->join('descriptors', 'descriptors.id', '=', 'products_descriptors.descriptor_id')
                    ->groupBy('products.id');
        }

        return $products;
    }
    

}
