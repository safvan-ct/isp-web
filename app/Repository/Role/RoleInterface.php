<?php
namespace App\Repository\Role;

use Spatie\Permission\Models\Role;

interface RoleInterface
{
    public function dataTable();

    public function updateOrCreate(array $data, ?Role $role = null);

    public function destroy(Role $role);

    public function getStaffRoles($onlyName = false);
}
