<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Product;
use App\Storage;
use App\InvTransactionDetail;
use App\TransactionType;

class ReportsController extends Controller
{//returns a report with current item and monetary totals
    
  public function getSelectedBalanceReport()   {
      //presents a form for selecting a storage for a inventory balance report
      $storages = Storage::orderBy('description', 'asc')
                ->pluck('description', 'id');
      return view('reports.toSelectReport',compact('storages'));
  }
 
  public function selectedBalanceReport(Request $request){
      //prepares data for a selected storage inventory balance report
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
      
      $this_delivery_date = date_create($request->get('this_delivery_date'));
      $next_delivery_date = date_create($request->get('next_delivery_date'));
      $analysis_start_date = date_create($request->get('analysis_start_date'));
      $analysis_end_date = date_create($request->get('analysis_end_date'));
      $title = 'Analisis de consumo del '
              .$analysis_start_date->format('dMY')
              .' a '
              .$analysis_end_date->format('dMY')
              .' | Fecha de esta compra: '.$this_delivery_date->format('dMY')
              .' | Fecha de proxima compra: '.$next_delivery_date->format('dMY')
              .' | Dias hasta esta compra: '
              .$analysis_end_date->diff($this_delivery_date)->format('%a')
              .' | Dias en el ciclo de compra: '
              .$next_delivery_date->diff($this_delivery_date)->format('%a');
      
      $analysis_cut_date = $analysis_end_date->format('dMY');
      $purchase_date = $this_delivery_date->format('dMY');
      
      $products_to_buy = $this->getProductsToBuy($this_delivery_date,
              $next_delivery_date,
              $analysis_start_date,
              $analysis_end_date);
      
      return view('reports.toBuyRpt', compact('products_to_buy','title','analysis_cut_date','purchase_date'));
  }
  
  private function getProductsToBuy($this_delivery_date ,$next_delivery_date,
          $analysis_start_date, $analysis_end_date) {
      $days_in_analysis = $analysis_end_date->diff($analysis_start_date)->format('%a');
      $days_to_delivery = $analysis_end_date->diff($this_delivery_date)->format('%a');
      $days_to_provision = $next_delivery_date->diff($this_delivery_date)->format('%a');
      
      $productsToBuy = Product::join('inv_transaction_details',
                                'inv_transaction_details.product_id',
                                '=','products.id')
                        ->join('inv_transaction_headers', 
                                'inv_transaction_details.inv_transaction_header_id', 
                                '=', 'inv_transaction_headers.id')
                        ->join('transaction_types', 
                                'inv_transaction_headers.transaction_type_id', 
                                '=', 'transaction_types.id')
                        ->whereDate('inv_transaction_headers.document_date','<=',$analysis_end_date)
              ->whereDate('inv_transaction_headers.document_date','>=',$analysis_start_date)
                        ->groupBy('inv_transaction_details.product_id')
              ->selectRaw('products.id, sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0)) AS cosumption_to_date')
              ->selectRaw('sum(product_qty*transaction_types.effect_inv) AS existence_to_date')
              ->selectRaw($days_to_provision.'*sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'.($days_in_analysis).' AS ave_coms_cycle')
              ->selectRaw('sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'.($days_in_analysis).' AS daily_coms_ave')
              ->selectRaw('sum(product_qty*transaction_types.effect_inv) - sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'
                      .($days_in_analysis).'*'.$days_to_delivery.' AS proyected_existence')
              ->selectRaw($days_to_provision.'*sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'.($days_in_analysis).' - IF(sum(product_qty*transaction_types.effect_inv) - (sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'.($days_in_analysis).'*'.$days_to_delivery.') < 0,'.
                      ' 0, sum(product_qty*transaction_types.effect_inv) - (sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'
                      .($days_in_analysis).'*'.$days_to_delivery.')) AS proyected_purchase')
              ->havingRaw($days_to_provision.'*sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'.($days_in_analysis).' - IF(sum(product_qty*transaction_types.effect_inv) - (sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'.($days_in_analysis).'*'.$days_to_delivery.') < 0,'.
                      ' 0, sum(product_qty*transaction_types.effect_inv) - (sum(product_qty*if(transaction_types.effect_inv = -1, 1, 0))/'
                      .($days_in_analysis).'*'.$days_to_delivery.')) > 0')
              ->get()
              ->sortByDesc('proyected_purchase');
      
      
      return $productsToBuy;
  }
  
   public function tipTransForm() {
       //select transaction to report a consolidated movement
       $transaction_types = TransactionType::orderBy('description', 'asc')
                ->pluck('description', 'id');
       return view('reports.tipTransForm',compact('transaction_types'));
  }
  
  public function tipTransRpt(Request $request) {
      //This report presents 
      // a consolidated movement of all products reported in a transaction type
      //in a given date range
      $analysis_start_date = date_create($request->get('analysis_start_date'));
      $analysis_end_date = date_create($request->get('analysis_end_date'));
      
      $title = 'Analisis de movimientos del '.$analysis_start_date->format('dMY')
              .' a ' .$analysis_end_date->format('dMY');
      
      $transaction_type = TransactionType::find($request->get('transaction_type_id'));
      
      $products = Product::join('inv_transaction_details',
                                'inv_transaction_details.product_id','=','products.id')
                        ->join('inv_transaction_headers', 
                                'inv_transaction_details.inv_transaction_header_id', 
                                '=', 'inv_transaction_headers.id')
                        ->join('transaction_types', 
                                'inv_transaction_headers.transaction_type_id', 
                                '=', 'transaction_types.id')
              ->where('inv_transaction_headers.transaction_type_id','=',$transaction_type->id)
              ->whereDate('inv_transaction_headers.document_date','<=',$analysis_end_date)
              ->whereDate('inv_transaction_headers.document_date','>=',$analysis_start_date)
              ->groupBy('inv_transaction_details.product_id')
              ->selectRaw('products.id, abs(sum(product_qty*transaction_types.effect_inv)) AS mov_to_date')
              ->havingRaw('abs(sum(product_qty*transaction_types.effect_inv)) > 0')
              ->get()
              ->sortByDesc('mov_to_date');
      
       return view('reports.tipTransRpt',compact('products','transaction_type','title'));
  }
  
  public function dashboard() {
      return view('reports.dashboard',compact('inventory_charts'));
  }
  
}
