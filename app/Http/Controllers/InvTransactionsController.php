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
        $invTransactionHeaders = InvTransactionHeader::orderBy('document_date', 'desc')
                        ->join('storages', 'inv_transaction_headers.storage_id', '=', 'storages.id')
                ->join('transaction_types','inv_transaction_headers.transaction_type_id','=','transaction_types.id')
                        ->select('inv_transaction_headers.id',
                                'transaction_types.description AS transaction',
                                'inv_transaction_headers.document_number as number', 
                                'inv_transaction_headers.note as note', 
                                'inv_transaction_headers.document_date as date',
                                'storages.description as storage')->get();

        //conver results to array

        $trans_array = $this->getTransArray($invTransactionHeaders);
        return view('invTransactions.index', compact('trans_array'));
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
        $transaction_types = TransactionType::orderBy('description', 'asc')
                ->pluck('description', 'id');
        $storages = Storage::orderBy('description', 'asc')
                ->pluck('description', 'id');
        $fact_id = config('global.fact_id', 0);
        return view('invTransactions.create', compact('transaction_types', 'storages', 'fact_id'));
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
        }
        // //a return won't let the following code to continue
        //Receives and updates new purchase data
        if (empty($request->get('product_id'))) {
            return redirect()->route('invTransactions.create')->withInput()
                            ->with('message', 'No product was found');
        }
        //$this->validate($request, InvTransactionHeader::$rules);
        //$this->validate($request, InvTransactionDetail::$rules);
        $transDetails = $this->getTransDetails($request, 0);

        if (empty($transDetails)) {
            return redirect()
                            ->route('invTransactions.create')
                            ->withInput()->with('message', 'No product was found');
        }

        $transHeadersData = $this->getTransHeaderData($request);
        $createdTransHeader = InvTransactionHeader::create($transHeadersData);
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
            return redirect()->route('invTransactions.index')
                            ->with('message', 'Transaction Header not found');
        }

        $productstransaction = $this->getProductsTransaction($id);
        $transaction_types = TransactionType::orderBy('description', 'asc')
                ->pluck('description', 'id');
        $storages = Storage::orderBy('description', 'asc')
                ->pluck('description', 'id');

        $fact_id = config('global.fact_id', -1);
        return view('invTransactions.edit', compact(
                        'invTransactionHeader', 'productstransaction', 'transaction_types', 'storages', 'fact_id')
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

        $message = usercan('purchases_update', Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }
        //usercan return won't let the following code to continue
        //$purchaseValidation = Validator::make($incoming_purchase, Purchase::$rules);
        //$detailValidation = Validator::make($purchaseDetails, ProductPurchase::$rules);
        $transDetails = $this->getTransDetails($request, $id);
        if (count($transDetails) === 0) {
            return redirect()->back()->with('message', 'No products found');
        }

        $transHeadersData = $this->getTransHeaderData($request);
        $invHeader = InvTransactionHeader::find($id);
        $invHeader->update($transHeadersData);

        //delete all records not in the list of records sent
        InvTransactionDetail::where('inv_transaction_header_id', '=', $id)
                ->whereNotIn('id', $request->get('detail_id'))->delete();
        foreach ($transDetails as $transDetail) {
            if ($transDetail['id'] === "null") {
                $invHeader->invTransactionDetails()->create($transDetail);
            } else {
                $currDetail = InvTransactionDetail::find($transDetail['id']);
                $currDetail->update($transDetail);
            }
        }
        return redirect()->route('invTransactions.index');
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
            'storage_id' => $request->get('storage_id'),
            'document_date' => $request->get('document_date'),
            'document_number' => $request->get('document_number'),
            'note' => $request->get('note')
        );
        return $transHeadersData;
    }

    private function getTransDetails($request, $id) {
        //get the transaction type
        $transTypeId = $request->get('transaction_type_id');
        //check if the effect requires to calculate cost for the transaction 
        $transSign = TransactionType::find($transTypeId);
        $needsCost = $transSign->effect_inv === -1;
        //check if incoming transaction is a bill
        //need to convert to number since we receive characters at $request
        $isFact = config('global.fact_id', -1) === (int) $transTypeId;

        $transProducts = $request->get('product_id');
        $product_qty = $request->get('product_qty');
        $product_cost = $request->get('product_cost');
        $detail_id = $request->get('detail_id');
        $transDetails = Array();
        for ($nCount = 0; $nCount < count($transProducts); $nCount++) {
            //use getCost helper function to get cust for current doc
            $avgCost = getCost($transProducts[$nCount], $request->get('document_date'), $id);
            $cost = ($needsCost ? $avgCost * $product_qty[$nCount] : $product_cost[$nCount]);
            $price = ($isFact ? $product_cost[$nCount] : 0);

            $transDetails[] = array(
                'id' => $detail_id[$nCount], 'product_id' => $transProducts[$nCount],
                'product_qty' => $product_qty[$nCount], 'product_cost' => $cost,
                'product_price' => $price);
        }
        return $transDetails;
    }

    public function getProductsTransaction($transaction_id) {

        $invTransactionHeader = InvTransactionHeader::find($transaction_id);

        $product_cost = 0;
        //if a billing transaction place price in the expected product_cost field
        if (config('global.fact_id', -1) === $invTransactionHeader->transaction_type_id) {
            $product_cost = 'inv_transaction_details.product_price AS product_cost';
        } else {
            $product_cost = 'inv_transaction_details.product_cost';
        }

        $products_transaction = Product::select('inv_transaction_details.id', 'products.id as product_id', DB::raw($this->getDbRaw()), 'inv_transaction_details.product_qty', $product_cost)
                ->join('products_descriptors', 'products_descriptors.product_id', '=', 'products.id')
                ->join('descriptors', 'descriptors.id', '=', 'products_descriptors.descriptor_id')
                ->join('inv_transaction_details', 'products.id', '=', 'inv_transaction_details.product_id')
                ->where('inv_transaction_details.inv_transaction_header_id', '=', $transaction_id)
                ->groupBy('products.id')
                ->groupBy('inv_transaction_details.id')
                ->orderBy('inv_transaction_details.id', 'asc')
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
    
    private function getTransArray($transactions) {
        $nCount = 0;
        $transArray = array();
        foreach ($transactions as $transaction) {
            $transArray[] = [
                $transaction['id'],$transaction['transaction'],$transaction['number'],
                $transaction['date'], $transaction['note'], $transaction['storage'],
            ];
            $nCount += 1;
        }
        return $transArray;
    }

}
