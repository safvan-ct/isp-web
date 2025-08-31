<?php
namespace App\Repository\Settings;

use App\Models\Settings;

interface SettingsInterface
{
    public function dataTable();

    public function updateOrCreate(array $data, ?Settings $settings = null);

    public function destroy(Settings $settings);
}
