<?php

namespace App\Services;

use App\Models\User;

class AIContextService
{
    public function build(User $user): array
    {
        $user->load([
            'farms.crops.activities'
        ]);

        return [
            'name' => $user->name,
            'farms' => $user->farms
        ];
    }
}