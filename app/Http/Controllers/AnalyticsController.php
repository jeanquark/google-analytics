<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use View;

class AnalyticsController extends Controller
{
    public function index() {

    	return View::make('analytics');

    }


    public function getData() {
    	
    	include(app_path() . '/Functions/googleAnalytics.php');

        return $results;
    }
}