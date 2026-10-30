<?php
// app/Policies/LoansPolicy.php

namespace App\Policies;

use App\Models\Loans;
use App\Models\User;

class LoansPolicy
{
    /**
     * Menentukan apakah user bisa melihat daftar (tabel) pinjaman.
     */
    public function viewAny(User $user): bool
    {
        return true; // Admin & Siswa boleh masuk ke halaman tabel
    }

    /**
     * Menentukan apakah user bisa melihat detail satu pinjaman.
     */
    public function view(User $user, Loans $loan): bool
    {
        // Admin bisa lihat semua, Siswa hanya bisa lihat miliknya sendiri
        return $user->role === 'admin' || $user->id === $loan->member?->user_id;
    }

    /**
     * Menentukan apakah user bisa membuat pinjaman baru.
     */
    public function create(User $user): bool
    {
        return true; // Admin & Siswa boleh meminjam
    }

    /**
     * Menentukan apakah user bisa mengedit pinjaman.
     */
    public function update(User $user, Loans $loan): bool
    {
        // Sesuai permintaanmu: Hanya Admin yang boleh mengedit/konfirmasi
        return $user->role === 'admin';
    }

    /**
     * Menentukan apakah user bisa menghapus pinjaman.
     */
    public function delete(User $user, Loans $loan): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Menentukan apakah user bisa menghapus massal (Bulk Delete).
     */
    public function deleteAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Menentukan apakah user bisa restore.
     */
    public function restore(User $user, Loans $loan): bool
    {
        return $user->role === 'admin';
    }

    public function restoreAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Menentukan apakah user bisa force delete.
     */
    public function forceDelete(User $user, Loans $loan): bool
    {
        return $user->role === 'admin';
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->role === 'admin';
    }
}