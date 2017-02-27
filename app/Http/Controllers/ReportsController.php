<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
  public function toBuyRpt() {
      /*Estimates products to be bought based on number of items actually
       * in storage, the daily average, and the next planned purchase date
       */
      
       return view('reports.toBuyRpt');
  }
}
