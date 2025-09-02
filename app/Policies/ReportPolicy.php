<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Tentukan apakah user bisa melihat sebuah laporan.
     */
    public function view(User $user, Report $report): bool
    {
        // 1. Jika user adalah RW, dia bisa melihat laporan jika pembuat laporan
        //    adalah RT yang merupakan bawahannya (parent_id nya adalah ID user RW ini).
        if ($user->hasRole('RW')) {
            // report->user adalah user RT yang membuat laporan
            // report->user->parent_id adalah ID dari RW-nya
            return $report->user->parent_id === $user->id;
        }

        // 2. Jika user adalah KELURAHAN, dia bisa melihat laporan jika pembuat laporan
        //    adalah RT yang berada di bawah RW, dimana RW tersebut adalah bawahan Kelurahan ini.
        if ($user->hasRole('KELURAHAN')) {
            // report->user adalah user RT
            $rwUser = $report->user->parent; // Mengambil user RW dari RT
            if ($rwUser) {
                // Cek apakah parent_id dari RW adalah ID user Kelurahan ini
                return $rwUser->parent_id === $user->id;
            }
            return false;
        }

        // 3. RT tidak boleh melihat laporan (hanya input).
        //    Namun, jika Anda ingin RT bisa melihat laporannya sendiri, tambahkan:
        // if ($user->hasRole('RT')) {
        //     return $user->id === $report->user_id;
        // }

        // Untuk role lain (SUPERADMIN/KECAMATAN), akses sudah diberikan oleh Gate::before.
        // Jika tidak ada kondisi di atas terpenuhi, tolak akses.
        return false;
    }

    /**
     * Tentukan apakah user bisa membuat laporan.
     */
    public function create(User $user): bool
    {
        // Hanya RT yang bisa membuat laporan
        return $user->hasRole('RT');
    }

    // ... method lainnya (update, delete, etc.) bisa Anda tambahkan di sini
    // dengan logika yang serupa.
}