<?php
namespace App\Repository\User;

use App\Models\User;

interface UserInterface
{
    public function dataTable(array $roles, array $with = []);

    public function updateOrCreate(array $data, ?User $user = null);

    public function toggleActive(User $user);

    public function destroy(User $user);
}
