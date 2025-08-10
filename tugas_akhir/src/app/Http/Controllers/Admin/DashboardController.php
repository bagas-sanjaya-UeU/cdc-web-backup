<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        $viewData = [];

        // Menggunakan switch case untuk struktur yang lebih jelas
        switch ($role) {
            case 'admin':
                $viewData = $this->getAdminData();
                break;
            case 'owner':
                $viewData = $this->getOwnerData();
                break;
            case 'customer':
                $viewData = $this->getCustomerData();
                break;
        }
        
        return view('dashboards.dashboard', $viewData);
    }

    private function getAdminData()
    {
        // Data untuk card metrik
        $pendingBookings = Booking::where('status', 'pending_confirmation')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $totalUsers = User::count();
        $totalMenus = MenuItem::count();

        // Data untuk tabel booking terbaru
        $latestBookings = Booking::with('user', 'place')->latest()->take(5)->get();

        // ===================================================================
        // PERBAIKAN: Langsung menggunakan sintaks MONTH() untuk MySQL
        // ===================================================================
        $monthExpression = 'MONTH(booking_date)';
        
        $bookingsData = Booking::select(
                DB::raw($monthExpression . ' as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('booking_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->all();

        $bookingCounts = [];
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
            $bookingCounts[] = $bookingsData[$i] ?? 0;
        }

        return [
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            'totalUsers' => $totalUsers,
            'totalMenus' => $totalMenus,
            'latestBookings' => $latestBookings,
            'bookingCounts' => $bookingCounts,
            'months' => $months,
        ];
    }

    private function getOwnerData()
    {
        // Data untuk card metrik
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_amount');
        $totalBookings = Booking::whereIn('status', ['confirmed', 'completed'])->count();
        $averageBookingValue = ($totalBookings > 0) ? $totalRevenue / $totalBookings : 0;

        // Data untuk tabel booking terbaru yang menghasilkan pendapatan
        $latestBookings = Booking::with('user', 'place')->whereIn('status', ['confirmed', 'completed'])->latest()->take(5)->get();
        
        return [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'averageBookingValue' => $averageBookingValue,
            'latestBookings' => $latestBookings,
        ];
    }

    private function getCustomerData()
    {
        $userId = auth()->id();
        
        // Data untuk card metrik
        $activeBookings = Booking::where('user_id', $userId)->whereIn('status', ['pending_confirmation', 'confirmed'])->count();
        $completedBookings = Booking::where('user_id', $userId)->where('status', 'completed')->count();
        $totalBookings = $activeBookings + $completedBookings;

        // Data untuk tabel booking terbaru
        $latestBookings = Booking::where('user_id', $userId)->with('place')->latest()->take(5)->get();

        return [
            'activeBookings' => $activeBookings,
            'completedBookings' => $completedBookings,
            'totalBookings' => $totalBookings,
            'latestBookings' => $latestBookings,
        ];
    }
}
