<?php

use Illuminate\Support\Facades\DB;
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
