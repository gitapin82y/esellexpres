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
    public function index()
{
    $totalIncome = 0;
    $totalProductSales = 0;
    $totalProduct = 0;
    $incomingOrders = 0;
    $orderProcess = 0;
    $orderCompleted = 0;

    // Ambil tahun dan bulan saat ini
    $currentYear = Carbon::now()->year;
    $currentMonth = Carbon::now()->month;
    
$totalProductSalesPerMonth = [
    'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
    'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
    'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
];

    if (Auth::user()->role == 1) {
          $totalIncome = Transaction::where('status', '!=', 'Waiting process')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('profit');

        // Transaksi selesai tahun ini dan bulan ini
        $transactions = Transaction::with('details')
            ->where('status', 'The customer has received the order')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();
            
                    $transactionYear = Transaction::with('details')
            ->where('status', 'The customer has received the order')
            ->whereYear('created_at', $currentYear)
            ->get();

        // Hitung total penjualan produk berdasarkan bulan

      
            
            foreach ($transactionYear as $transaction) {
  $month = Carbon::parse($transaction->created_at)->format('M');
    $totalQuantity = $transaction->details->sum('quantity');
    $totalProductSalesPerMonth[$month] += $totalQuantity;
            }

        // Total penjualan produk bulan ini
        $totalProductSales = $transactions->sum(function ($transaction) {
            return $transaction->details->sum('quantity');
        });

        // Jumlah produk (tidak berdasarkan tahun/bulan)
        $totalProduct = Product::count();

        // Pesanan berdasarkan status bulan ini
        $incomingOrders = Transaction::where('status', 'Process')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $orderProcess = Transaction::whereNotIn('status', ['Waiting process', 'Process', 'The customer has received the order'])
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $orderCompleted = Transaction::where('status', 'The customer has received the order')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();
    } else {
        $store = Store::where('user_id', Auth::id())->first();

        $totalIncome = Transaction::where('store_id', $store->id)
            ->where('is_confirmed', 'Y')
            ->where('status', 'The customer has received the order')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('profit');
            

        $transactions = Transaction::with('details')
            ->where('store_id', $store->id)
            ->where('status', 'The customer has received the order')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();
            
                    $transactionYear = Transaction::with('details')
            ->where('store_id', $store->id)
            ->where('status', 'The customer has received the order')
            ->whereYear('created_at', $currentYear)
            ->get();

      
        foreach ($transactionYear as $transaction) {
            $totalQuantity = $transaction->details->sum('quantity');
            $month = Carbon::parse($transaction->created_at)->format('M');
            if (!isset($totalProductSalesPerMonth[$month])) {
                $totalProductSalesPerMonth[$month] = 0;
            }
            $totalProductSalesPerMonth[$month] += $totalQuantity;
        }
        

        $totalProductSales = $transactions->sum(function ($transaction) {
            return $transaction->details->sum('quantity');
        });

        $totalProduct = ProductStore::where('store_id', $store->id)->count();

        $incomingOrders = Transaction::where('status', 'Waiting process')
            ->where('store_id', $store->id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $orderProcess = Transaction::whereNotIn('status', ['Waiting process', 'The customer has received the order'])
            ->where('store_id', $store->id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $orderCompleted = Transaction::where('status', 'The customer has received the order')
            ->where('store_id', $store->id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();
    }

    return view('pages.dashboard', compact(
        'totalIncome',
        'totalProductSales',
        'totalProduct',
        'incomingOrders',
        'orderProcess',
        'orderCompleted',
        'totalProductSalesPerMonth'
    ));
}

}