<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingUpdateRequest;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {}

    public function index(): View
    {
        $settings = $this->settingService->getAll();

        return view('settings.index', compact('settings'));
    }

    public function update(SettingUpdateRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('logo');

        foreach ($data as $key => $value) {
            $this->settingService->set($key, $value);
        }

        if ($request->hasFile('logo')) {
            $this->settingService->uploadLogo($request->file('logo'));
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function uploadLogo(SettingUpdateRequest $request): RedirectResponse
    {
        if (! $request->hasFile('logo')) {
            return back()->withErrors(['logo' => 'File logo wajib diunggah.']);
        }

        $this->settingService->uploadLogo($request->file('logo'));

        return redirect()
            ->route('settings.index')
            ->with('success', 'Logo berhasil diunggah.');
    }
}
