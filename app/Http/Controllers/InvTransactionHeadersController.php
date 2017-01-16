<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\InvTransactionHeader;
use App\TransactionType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Config;

class InvTransactionHeadersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $action_code = 'inv_transactionHeaders_index';
        $message = usercan($action_code, Auth::user());
        if ($message) { return redirect()->back()->with('message', $message); }
      
        $filter = Input::get('filter');
        if ($filter) {
       
           $invTransactionHeaders = InvTransactionHeader::join('shops','purchases.shop_id','=','shops.id')
                   ->where('user','=',Auth::user()->username)
                   ->whereRAW("shops.description like '%".$filter."%'")
                   ->orderBy('purchase_date','desc')
                   ->paginate(Config::get('global/default.rows'));
            
            return View::make('purchases.index', compact('purchases'))
                            ->with('filter', $filter);
        } else {
            $invTransactionHeaders = InvTransactionHeader::orderBy('document_date','desc')
                    ->paginate(Config::get('global/default.rows'));
            return view('invTransactionHeaders.index', compact('invTransactionHeaders'))
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
        //Display form for creation of roles
        $action_code = 'invTransactionHeaders_create';
        $message = usercan($action_code, Auth::user());
        if ($message) {
            return Redirect::back()->with('message', $message);
        }//a return won't let the following code to continue
        $transactionTypes = TransactionType::orderBy('description', 'asc')->pluck('short_description', 'id');
        return view('invTransactionHeaders.create', compact('transactionTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
