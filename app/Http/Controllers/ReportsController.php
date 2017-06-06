<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Product;

class ReportsController extends Controller
{//returns a report with current item and monetary totals

  public function invSaldos() {

      $action_code = 'reports_invSaldos';

      $message = usercan($action_code, Auth::user());

      if ($message) {
          return Redirect::back()->with('message', $message);
      }
          $products =  Product::with('costTotal')
                  ->with('qtyTotal')
                  ->with('productDescription')
                  ->orderBy('id','desc')
                  ->paginate(config('global.rows_page'));
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
