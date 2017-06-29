<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Product;
use App\Storage;
use App\InvTransactionDetail;

class ReportsController extends Controller
{//returns a report with current item and monetary totals
    
  public function getSelected()   {
      $storages = Storage::orderBy('description', 'asc')
                ->pluck('description', 'id');
      return view('reports.toSelectReport',compact('storages'));
  }
 
  public function selectedReport(Request $request){
      $storageId = $request->get('storage_id');
      
      $storage = Storage::find($storageId);
      
      $products = Product::join('inv_transaction_details',
                                'inv_transaction_details.product_id',
                                '=','products.id')
                        ->join('inv_transaction_headers', 
                                'inv_transaction_details.inv_transaction_header_id', 
                                '=', 'inv_transaction_headers.id')
                        ->join('transaction_types', 
                                'inv_transaction_headers.transaction_type_id', 
                                '=', 'transaction_types.id')
                        ->where('inv_transaction_headers.storage_id','=',$storageId)
                        ->groupBy('inv_transaction_details.product_id')
              ->selectRaw('products.*, sum(product_qty*transaction_types.effect_inv) AS Qty')
              ->selectRaw('sum(product_cost*transaction_types.effect_inv) AS Cost')
              ->havingRaw('round(sum(product_qty*transaction_types.effect_inv),2) <> 0')
              ->with('productDescription')
              ->get();
      return view('reports.saldos_bodega_no_paginate', compact('products','storage','full_report'));
  }
  
  public function invSaldos() {

      $action_code = 'reports_invSaldos';

      $message = usercan($action_code, Auth::user());

      if ($message) {return Redirect::back()->with('message', $message);}
          
      $products =  Product::join('inv_transaction_details',
                                'inv_transaction_details.product_id',
                                '=','products.id')
                        ->join('inv_transaction_headers', 
                                'inv_transaction_details.inv_transaction_header_id', 
                                '=', 'inv_transaction_headers.id')
                        ->join('transaction_types', 
                                'inv_transaction_headers.transaction_type_id', 
                                '=', 'transaction_types.id')
                        ->groupBy('inv_transaction_details.product_id')
              ->selectRaw('products.*, sum(product_qty*transaction_types.effect_inv) AS Qty')
              ->selectRaw('sum(product_cost*transaction_types.effect_inv) AS Cost')
              ->havingRaw('round(sum(product_qty*transaction_types.effect_inv),2) <> 0')
              ->with('productDescription')
              ->get()->sortByDesc('Qty');
      
          return view('reports.saldos', compact('products'));

  }
  
  public function toBuyForm() {
       return view('reports.toBuyForm');
  }
  public function toBuyRpt(Request $request) {
      /*Estimates products to be bought based on number of items actually
       * in storage, the daily average, and the next planned purchase date
       * this report estimates the minimum number of items needed to be bougth
       * in this purchase so when the next purchase is due there will be 
       * a minimum number of products at the store
       */
      $this_purchase_date = $request->get('this_purchase_date');
      $next_purchase_date = $request->get('next_purchase_date');
      $products_to_buy = $this->getProductsToBuy($this_purchase_date ,$next_purchase_date);
      
      return view('reports.toBuyRpt', compact('products_to_buy'));
  }
  
  private function getProductsToBuy($this_purchase_date ,$next_purchase_date) {
      $productsToBuy = 0;
      return $productsToBuy;
  }
  
}
