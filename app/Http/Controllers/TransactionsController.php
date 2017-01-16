<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $action_code = 'transactions_index';
        $message = usercan($action_code, Auth::user());
        if ($message) { return redirect()->back()->with('message', $message); }
      
        $filter = Input::get('filter');
        if ($filter) {
       
           $transactionHeaders = TransactionHeader::join('shops','purchases.shop_id','=','shops.id')
                   ->where('user','=',Auth::user()->username)
                   ->whereRAW("shops.description like '%".$filter."%'")
                   ->orderBy('purchase_date','desc')
                   ->paginate(Config::get('global/default.rows'));
            
            return view('purchases.index', compact('purchases'))
                            ->with('filter', $filter);
        } else {
            $transactionHeaders = TransactionHeader::orderBy('document_date','desc')
                    ->paginate(Config::get('global/default.rows'));
            return View::make('transactionHeaders.index', compact('transactionHeaders'))
                            ->with('filter', $filter);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $action_code = 'transaction_store';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return redirect()->back()->with('message', $message);
        }//a return won't let the following code to continue
        //Receives and updates new transaction data
        $transactionData = array(
            "transaction_type_id" => Input::get('transactionType_id'),
            "document_date" => Input::get('document_date'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        );
        $transactedProducts = Input::get('product_id');
        if (!$transactedProducts) {
            return Redirect::route('invTransactionHeaders.create')
                            ->withInput()
                            ->with('message', 'No product was found');
        }
        $this->validate($transactionData, TransactionHeader::$rules);
        
            $transactionHeader = TransactionHeader::create($transactionData);
            $transactedAmount = Input::get('amount');
            $transactedTotal = Input::get('total');
            
            for ($nCount = 0; $nCount < count($transactedProducts); $nCount++) {
                $transactedProducts[] = array('transaction_header_id' => $transactionHeader->id,
                    'product_id' => $transactedProducts[$nCount],
                    'amount' => $transactedAmount[$nCount],
                    'total' => $transactedTotal[$nCount],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                );
            }
            $this->validate($transactedProducts, TransactionDetails::$rules);
            TransactionDetails::insert($transactedProducts);
            return redirect()->route('transactionHeaders.index')
                            ->with('message', 'Purchase Created');
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
        //
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
    }
}
