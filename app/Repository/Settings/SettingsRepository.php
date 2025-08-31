<?php
namespace App\Repository\Settings;

use App\Models\Settings;

class SettingsRepository implements SettingsInterface
{
    public function dataTable()
    {
        return Settings::select('id', 'key', 'value');
    }

    public function updateOrCreate(array $data, ?Settings $settings = null): Settings
    {
        return Settings::updateOrCreate(['id' => $settings?->id], ['key' => strtolower($data['key']), 'value' => $data['value']]);
    }

    public function destroy(Settings $settings): void
    {
        $settings->delete();
    }
}
