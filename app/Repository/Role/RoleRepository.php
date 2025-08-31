<?php
namespace App\Repository\Role;

use Spatie\Permission\Models\Role;

class RoleRepository implements RoleInterface
{
    public function dataTable()
    {
        return Role::with('permissions')->select('id', 'name')
            ->when(! auth()->user()->hasRole('Developer'), fn($q) => $q->whereNotIn('id', [1, 2, 4]));
    }

    public function updateOrCreate(array $data, ?Role $role = null): Role
    {
        $query = Role::updateOrCreate(['id' => $role?->id], ['name' => $data['name']]);
        $query->syncPermissions($data['permissions']);
        return $query;
    }

    public function destroy(Role $role): void
    {
        $role->delete();
    }

    public function getStaffRoles($onlyName = false)
    {
        $query = Role::whereNotIn('name', ['Owner', 'Developer', 'Customer']);
        return $onlyName ? $query->pluck('name')->toArray() : $query->get();
    }
}
