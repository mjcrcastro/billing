<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\InvTransactionHeader;
use App\InvTransactionDetail;
use App\TransactionType;
use App\Storage;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class InvTransactionsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $action_code = 'invTransactions_index';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }

        $filter = $request->get('filter');
        if ($filter) {

            $invTransactionHeaders = InvTransactionHeader::join('shops', 'purchases.shop_id', '=', 'shops.id')
                    ->where('user', '=', Auth::user()->username)
                    ->whereRAW("shops.description like '%" . $filter . "%'")
                    ->orderBy('purchase_date', 'desc')
                    ->paginate(Config::get('global/default.rows'));

            return view('invTransactions.index', compact('purchases'))
                            ->with('filter', $filter);
        } else {
            $invTransactionHeaders = InvTransactionHeader::orderBy('document_date', 'desc')
                    ->paginate(Config::get('global/default.rows'));
            return view('invTransactions.index', compact('invTransactionHeaders'))
                            ->with('filter', $filter);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //Display form for creation of roles
        $action_code = 'invTransactions_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }//a return won't let the following code to continue
        $transactionTypes = TransactionType::orderBy('description', 'asc')
                ->pluck('short_description', 'id');
        $storages = Storage::orderBy('description', 'asc')
                ->pluck('description', 'id');
        return view('invTransactions.create', compact('transactionTypes', 'storages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $action_code = 'invTransactions_store';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }//a return won't let the following code to continue
        //Receives and updates new purchase data

        if (empty($request->get('product_id'))) {
            return redirect()->route('invTransactions.create')
                            ->withInput()
                            ->with('message', 'No product was found');
        }

        //$this->validate($request, InvTransactionHeader::$rules);
        //$this->validate($request, InvTransactionDetail::$rules);

        $transHeadersData = $this->getTransHeaderData($request);

        $createdTransHeader = InvTransactionHeader::create($transHeadersData);

        $transDetails = $this->getTransDetails($request);

        foreach ($transDetails as $transDetail) {
            $createdTransHeader->invTransactionDetails()->create($transDetail);
        }

        return redirect()->route('invTransactions.index')
                        ->with('message', 'Transaction created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //Redirect to Company editor
        $action_code = 'invTransactions_edit';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }
        // //a return won't let the following code to continue

        $invTransactionHeader = InvTransactionHeader::find($id);
        if (is_null($invTransactionHeader)) {
            return redirect()->route('invTransactions.index');
        }
        
        $productstransaction = $this->getProductsTransaction($id);
        
        $transaction_types = TransactionType::orderBy('description', 'asc')
                ->pluck('short_description', 'id');
        $storages = Storage::orderBy('description', 'asc')
                ->pluck('description', 'id');
        
        return view('invTransactions.edit', compact(
                'invTransactionHeader', 
                'productstransaction',
                'transaction_types',
                'storages'
                )
                );
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        $action_code = 'inv_transactions_destroy';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }
        //a return won't let the following code to continue
        InvTransactionHeader::find($id)->delete();
        return redirect()->route('invTransactions.index');
    }

    private function getTransHeaderData($request) {
        $transHeadersData = array(
            'transaction_type_id' => $request->get('transaction_type_id'),
            "storage_id" => $request->get('storage_id'),
            "document_date" => $request->get('document_date'),
            'document_number' => $request->get('document_number'),
            'note' => $request->get('note', 'none')
        );
        return $transHeadersData;
    }

    private function getTransDetails($request) {
        $transProducts = $request->get('product_id');
        $product_qty = $request->get('product_qty');
        $product_cost = $request->get('product_cost');
        for ($nCount = 0; $nCount < count($transProducts); $nCount++) {
            $transDetails[] = array(
                'product_id' => $transProducts[$nCount],
                'product_qty' => $product_qty[$nCount],
                'product_cost' => $product_cost[$nCount]
            );
        }
        return $transDetails;
    }
    
    public function getProductsTransaction($transaction_id) { 
        $products_transaction = Product::select('inv_transaction_details.id', 'products.id as product_id', 
                DB::raw($this->getDbRaw()), 'inv_transaction_details.product_qty', 'inv_transaction_details.product_cost')
                ->join('products_descriptors', 'products_descriptors.product_id', '=', 'products.id')
                ->join('descriptors', 'descriptors.id', '=', 'products_descriptors.descriptor_id')
                ->join('inv_transaction_details', 'products.id', '=', 'inv_transaction_details.product_id')
                ->where('inv_transaction_details.inv_transaction_header_id', '=', $transaction_id)
                ->groupBy('products.id')
                ->groupBy('inv_transaction_details.id')
                ->orderBy('inv_transaction_details.id')
                ->get();
        return $products_transaction;
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
}
