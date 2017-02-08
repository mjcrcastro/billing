<?php

use Illuminate\Support\Facades\DB;
use App\InvTransactionHeader;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function usercan($action_code, $user) {
    /*
     * Will check for all the actions assigned to the role the user
     * has been assigned to. In case there is at leas one 
     * permission that corresponds to the action_code, the user
     * has been granted permissions, otherwise the answer is false
     */
    return null; //temporary until I fix the roles and permissions
    $actions_allowed = $user->role->actions()->where('code', '=', $action_code)->get();

    return count($actions_allowed) ? 0 : 'Access denied to action : ' . actionDescription($action_code);
}

function actionDescription($action_code) {
    //returns description of $action_code
    $actions_collection = Action::where('code', '=', $action_code)->get();

    foreach ($actions_collection as $action_record) {
        return $action_record->description;
    }
}

function lastQuery() {
    //returns last executed query
    DB::enableQueryLog();
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    return $queries;
}

function getAllTables() {
    if (DB::connection()->getName() === 'mysql') {
        $allTables = DB::select('SHOW TABLES');
    } else {
        $allTables = DB::select("SELECT * FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'");
    }
}

function getCost($product_id, $upToDate) {
    /* Calculates cost for s given product
     * upto an specific date
     */

    $sumInv = InvTransactionHeader::select(
                            DB::raw('round(sum(product_cost*effect_inv)/sum(product_qty*effect_inv),2) AS cost')
                    )
                    ->join('inv_transaction_details', 'inv_transaction_details.inv_transaction_header_id', '=', 'inv_transaction_headers.id')
                    ->join('transaction_types', 'inv_transaction_headers.transaction_type_id', '=', 'transaction_types.id')
                    ->where('inv_transaction_details.product_id', '=', $product_id)
                    ->where('inv_transaction_headers.document_date', '<=', $upToDate)
                    ->groupBy('inv_transaction_details.product_id')->get();

    if (empty($sumInv)) {
        return 0;
    } else {
        return $sumInv[0]->cost;
    }
    return $sumInv;
}
