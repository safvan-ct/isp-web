<?php
namespace App\Repository\User;

use App\Models\User;

class UserRepository implements UserInterface
{
    public function dataTable(array $roles, array $with = [])
    {
        return User::select('id', 'first_name', 'last_name', 'role', 'email', 'phone', 'is_active')
            ->when($with, fn($q) => $q->with($with))
            ->whereIn('role', $roles);
    }

    public function updateOrCreate(array $data, ?User $user = null): User
    {
        // Set password only for new users or when the role is other than user
        if (! $user || ($data['role'] != 'Customer' && ! empty($data['password']))) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $query = User::updateOrCreate(['id' => $user?->id], $data);

        if (! empty($data['role']) || $data['role'] != 'Customer') {
            $query->syncRoles([$data['role']]);
        }

        return $query;
    }

    public function toggleActive(User $user): void
    {
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function destroy(User $user): void
    {
        $user->delete();
    }
}
