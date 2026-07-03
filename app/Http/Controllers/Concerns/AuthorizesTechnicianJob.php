<?php

namespace App\Http\Controllers\Concerns;

use App\Models\ServiceOrder;
use App\Models\User;

trait AuthorizesTechnicianJob
{
    protected function authorizeTechnicianJob(ServiceOrder $order, User $user, bool $requireAssignment = true): void
    {
        if (in_array($user->role, ['owner', 'admin'], true)) {
            return;
        }

        if ($user->role !== 'teknisi') {
            abort(403, 'Anda tidak memiliki akses ke pekerjaan ini.');
        }

        if (! $requireAssignment) {
            return;
        }

        $technicianId = $user->technicianProfile?->id;

        if ($technicianId === null || (int) $order->technician_id !== (int) $technicianId) {
            abort(403, 'Pekerjaan ini tidak ditugaskan kepada Anda.');
        }
    }

    protected function technicianIdForUser(User $user): ?int
    {
        if ($user->role !== 'teknisi') {
            return null;
        }

        return $user->technicianProfile?->id;
    }
}
