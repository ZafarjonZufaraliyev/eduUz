<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * QR skan: QR_IN_* = checkin, QR_OUT_* = checkout
     */
    public function scan(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $qr = $request->qr_code;

        if (str_starts_with($qr, 'QR_IN_')) {
            $user = User::where('qr_checkin', $qr)->with(['category', 'schoolClass'])->first();
            $type = 'checkin';
        } elseif (str_starts_with($qr, 'QR_OUT_')) {
            $user = User::where('qr_checkout', $qr)->with(['category', 'schoolClass'])->first();
            $type = 'checkout';
        } else {
            return response()->json(['message' => "QR kod noto'g'ri formatda"], 422);
        }

        if (! $user || ! $user->is_active) {
            return response()->json(['message' => 'Foydalanuvchi topilmadi'], 404);
        }

        $log = AttendanceLog::create([
            'user_id'    => $user->id,
            'type'       => $type,
            'scanned_at' => now(),
            'device_id'  => $request->device_id ?? 'web',
            'is_late'    => $this->checkLate($user, $type),
        ]);

        $log->load('user');

        return response()->json(['log' => $log, 'user' => $user]);
    }

    public function manualCheckin(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $log = AttendanceLog::create([
            'user_id'    => $request->user_id,
            'type'       => 'checkin',
            'scanned_at' => now(),
            'device_id'  => 'manual',
        ]);
        return response()->json($log->load('user'));
    }

    public function manualCheckout(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $log = AttendanceLog::create([
            'user_id'    => $request->user_id,
            'type'       => 'checkout',
            'scanned_at' => now(),
            'device_id'  => 'manual',
        ]);
        return response()->json($log->load('user'));
    }

    /**
     * Hozir binoda kim bor: oxirgi yozuvi checkin bo'lganlar
     */
    public function live()
    {
        $logs = AttendanceLog::with('user')
            ->whereDate('scanned_at', today())
            ->orderByDesc('scanned_at')
            ->get()
            ->groupBy('user_id')
            ->map(fn($g) => $g->first())
            ->filter(fn($l) => $l->type === 'checkin')
            ->values();

        return response()->json($logs);
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        $logs = AttendanceLog::with('user')
            ->whereDate('scanned_at', $date)
            ->orderByDesc('scanned_at')
            ->get();
        return response()->json($logs);
    }

    public function byUser(Request $request, int $userId)
    {
        $query = AttendanceLog::where('user_id', $userId)->orderByDesc('scanned_at');
        if ($request->date) {
            $query->whereDate('scanned_at', $request->date);
        }
        return response()->json($query->get());
    }

    public function summary(Request $request)
    {
        $from = $request->get('from', today()->subDays(7)->toDateString());
        $to   = $request->get('to', today()->toDateString());

        return response()->json([
            'checkins'    => AttendanceLog::where('type', 'checkin')->whereBetween('scanned_at', [$from, $to])->count(),
            'checkouts'   => AttendanceLog::where('type', 'checkout')->whereBetween('scanned_at', [$from, $to])->count(),
            'lates'       => AttendanceLog::where('is_late', true)->whereBetween('scanned_at', [$from, $to])->count(),
            'total_users' => User::where('role', 'member')->where('is_active', true)->count(),
        ]);
    }

    public function stats()
    {
        $today = today()->toDateString();
        $checkins  = AttendanceLog::where('type', 'checkin')->whereDate('scanned_at', $today)->count();
        $checkouts = AttendanceLog::where('type', 'checkout')->whereDate('scanned_at', $today)->count();
        $lates     = AttendanceLog::where('is_late', true)->whereDate('scanned_at', $today)->count();

        $insideLogs = AttendanceLog::whereDate('scanned_at', $today)
            ->orderByDesc('scanned_at')
            ->get()
            ->groupBy('user_id')
            ->filter(fn($g) => $g->first()->type === 'checkin')
            ->count();

        $weekly = [];
        $days = ['Du', 'Se', 'Ch', 'Pa', 'Ju', 'Sh', 'Ya'];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $weekly[] = [
                'day'      => $days[now()->subDays($i)->dayOfWeekIso - 1],
                'checkins' => AttendanceLog::where('type', 'checkin')->whereDate('scanned_at', $d)->count(),
                'checkouts' => AttendanceLog::where('type', 'checkout')->whereDate('scanned_at', $d)->count(),
            ];
        }

        return response()->json([
            'today_checkins'   => $checkins,
            'today_checkouts'  => $checkouts,
            'currently_inside' => $insideLogs,
            'late_today'       => $lates,
            'weekly'           => $weekly,
        ]);
    }

    private function checkLate(User $user, string $type): bool
    {
        if ($type !== 'checkin') return false;
        $startHour = 8;
        return now()->hour > $startHour || (now()->hour === $startHour && now()->minute > 15);
    }
}
