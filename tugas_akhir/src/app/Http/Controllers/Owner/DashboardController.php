<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk card metrik
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('down_payment_amount');
        $totalSuccessfulBookings = Booking::whereIn('status', ['confirmed', 'completed'])->count();
        $totalPendingRevenue = Booking::where('status', 'pending_confirmation')->sum('down_payment_amount');

        // Data untuk tabel booking terbaru yang menghasilkan pendapatan
        $latestTransactions = Booking::with('user', 'place')
            ->whereIn('status', ['confirmed', 'completed'])
            ->latest()
            ->take(10)
            ->get();

        // Data untuk Grafik Pendapatan Bulanan
        $databaseConnection = config('database.default');
        $driver = config("database.connections.{$databaseConnection}.driver");

        $monthExpression = '';
        if ($driver === 'mysql') {
            $monthExpression = 'MONTH(booking_date)';
        } elseif ($driver === 'sqlite') {
            $monthExpression = "CAST(strftime('%m', booking_date) AS INTEGER)";
        } else {
            $monthExpression = DB::raw("EXTRACT(MONTH FROM booking_date)");
        }
        
        $revenueData = Booking::select(
                DB::raw($monthExpression . ' as month'),
                DB::raw('SUM(down_payment_amount) as revenue')
            )
            ->whereYear('booking_date', Carbon::now()->year)
            ->whereIn('status', ['confirmed', 'completed'])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')->all();

        $revenueCounts = [];
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
            $revenueCounts[] = $revenueData[$i] ?? 0;
        }

        return view('dashboards.owners.dashboard', compact(
            'totalRevenue',
            'totalSuccessfulBookings',
            'totalPendingRevenue',
            'latestTransactions',
            'revenueCounts',
            'months'
        ));
    }
}
