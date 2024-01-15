<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){

        $totalIncome=0;
        $totalProductSales=0;
        $totalProduct = 0;
        $incomingOrders =0;
        $orderProcess = 0;
        $orderCompleted = 0;

        if(Auth::user()->role == 1){
            $totalIncome = Auth::user()->balance;

            $currentYear = Carbon::now()->year;
            $transactionsYears = Transaction::with('details')
                ->where('status', 'The customer has received the order')
                ->whereYear('created_at', $currentYear)
                ->get();

            $totalProductSalesPerMonth = [];
            
            foreach ($transactionsYears as $transaction) {
                 $totalQuantity = $transaction->details->sum('quantity');

                $month = Carbon::parse($transaction->created_at)->format('M');
                if (!isset($totalProductSalesPerMonth[$month])) {
                    $totalProductSalesPerMonth[$month] = 0;
                }
    
                $totalProductSalesPerMonth[$month] += $totalQuantity;
            }


            $transactions = Transaction::with('details')
            ->where('status', 'The customer has received the order')
            ->get();

            $totalQuantity = 0;
            
            foreach ($transactions as $transaction) {
                 $totalQuantity += $transaction->details->sum('quantity');
            }

            $totalProductSales = $totalQuantity;
            $totalProduct = Product::count();
            $incomingOrders = Transaction::where('status','Process')->count();
            $orderProcess = Transaction::whereNotIn('status', ['Waiting process','Process','The customer has received the order'])->count();
            $orderCompleted = Transaction::where('status','The customer has received the order')->count();

        }else{
            $store = Store::where('user_id', Auth::id())->first();
    
            $totalIncome = Transaction::where('store_id', $store->id)->where('is_confirmed', 'Y')
            ->where('status', 'The customer has received the order')->sum('profit');

            $currentYear = Carbon::now()->year;
            $transactionsYears = Transaction::with('details')
                ->where('store_id',$store->id)
                ->where('status', 'The customer has received the order')
                ->whereYear('created_at', $currentYear)
                ->get();

            $totalProductSalesPerMonth = [];
            
            foreach ($transactionsYears as $transaction) {
                 $totalQuantity = $transaction->details->sum('quantity');

                $month = Carbon::parse($transaction->created_at)->format('M');
                if (!isset($totalProductSalesPerMonth[$month])) {
                    $totalProductSalesPerMonth[$month] = 0;
                }
    
                $totalProductSalesPerMonth[$month] += $totalQuantity;
            }


            $transactions = Transaction::with('details')
            ->where('store_id',$store->id)
            ->where('status', 'The customer has received the order')
            ->get();

            $totalQuantity = 0;
            
            foreach ($transactions as $transaction) {
                 $totalQuantity += $transaction->details->sum('quantity');
            }

            $totalProductSales = $totalQuantity;
            $totalProduct = ProductStore::where('store_id',$store->id)->count();
            $incomingOrders = Transaction::where('status','Waiting process')->where('store_id',$store->id)->count();
            $orderProcess = Transaction::whereNotIn('status', ['Waiting process','The customer has received the order'])
            ->where('store_id',$store->id)
            ->count();
            $orderCompleted = Transaction::where('status','The customer has received the order')->where('store_id',$store->id)->count();

        }





        return view('pages.dashboard',compact('totalIncome','totalProductSales','totalProduct','incomingOrders','orderProcess','orderCompleted','totalProductSalesPerMonth'));
    }
}
