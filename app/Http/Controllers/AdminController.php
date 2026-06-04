<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pack;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Platform Net Earnings = sum of all platform fees collected from completed transactions
        $platformEarnings = Transaction::where('status', 'completed')->sum('platform_fee');
        $totalPaidOut = Withdrawal::where('status', 'completed')->sum('amount');
        $totalPendingWithdrawals = Withdrawal::where('status', 'pending')->sum('amount');

        // Monthly stats (Current month starting from 1st day)
        $monthStart = now()->startOfMonth();
        $monthlyEarnings = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $monthStart)
            ->sum('platform_fee');
        $monthlyWithdrawals = Withdrawal::where('status', 'completed')
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        // Chart Data (Last 30 Days)
        $chartLabels = [];
        $chartRevenue = [];
        $chartWithdrawals = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d/m');

            // Platform fees earned on this specific day
            $dayFee = Transaction::where('status', 'completed')
                ->whereDate('created_at', $date->toDateString())
                ->sum('platform_fee');
            $chartRevenue[] = (float) $dayFee;

            // Withdrawals completed on this specific day
            $dayPayout = Withdrawal::where('status', 'completed')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount');
            $chartWithdrawals[] = (float) $dayPayout;
        }

        $stats = [
            'total_users' => User::count(),
            'total_creators' => User::where('role', 'creator')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_packs' => Pack::count(),
            'total_purchases' => Purchase::where('status', 'confirmed')->count(),
            'total_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'platform_fees' => $platformEarnings,
            'total_paid_out' => $totalPaidOut,
            'total_pending_withdrawals' => $totalPendingWithdrawals,
            'monthly_earnings' => $monthlyEarnings,
            'monthly_withdrawals' => $monthlyWithdrawals,
            'chart_labels' => json_encode($chartLabels),
            'chart_revenue' => json_encode($chartRevenue),
            'chart_withdrawals' => json_encode($chartWithdrawals),
        ];

        $recentTransactions = Transaction::with('user')->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(10)->get();

        return view('admin.index', compact('stats', 'recentTransactions', 'recentUsers'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Status do usuário atualizado.');
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    public function categories()
    {
        $categories = Category::withCount('packs')->orderBy('sort_order')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:10',
        ]);

        $maxOrder = Category::max('sort_order') ?? 0;
        $validated['sort_order'] = $maxOrder + 1;

        Category::create($validated);

        return back()->with('success', 'Categoria criada com sucesso!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Categoria excluída com sucesso!');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function earnings()
    {
        // Calculate platform net earnings
        $platformEarnings = Transaction::where('status', 'completed')->sum('platform_fee');

        // Calculate actual withdrawals processed/debited from platform fee balance
        $withdrawals = Withdrawal::where('user_id', auth()->id())->latest()->get();
        $totalWithdrawn = Withdrawal::where('user_id', auth()->id())->where('status', 'completed')->sum('amount');
        $availableBalance = $platformEarnings - $totalWithdrawn;

        return view('admin.earnings', compact('platformEarnings', 'totalWithdrawn', 'availableBalance', 'withdrawals'));
    }
}
