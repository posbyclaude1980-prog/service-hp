<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class LoginController extends Controller
{
    private function debugLog(string $hypothesisId, string $location, string $message, array $data = []): void
    {
        // #region agent log
        file_put_contents(
            base_path('debug-df97ec.log'),
            json_encode([
                'sessionId' => 'df97ec',
                'runId' => 'pre-fix',
                'hypothesisId' => $hypothesisId,
                'location' => $location,
                'message' => $message,
                'data' => $data,
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES)."\n",
            FILE_APPEND
        );
        // #endregion
    }

    public function showLoginForm(): View
    {
        // #region agent log
        $this->debugLog('A', 'LoginController.php:showLoginForm', 'Login form requested', [
            'routeLoginExists' => Route::has('login'),
            'routeDashboardExists' => Route::has('dashboard'),
        ]);
        // #endregion

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // #region agent log
        $this->debugLog('B', 'LoginController.php:login', 'Login attempt started', [
            'emailProvided' => filled($credentials['email'] ?? null),
            'remember' => $remember,
        ]);
        // #endregion

        if (! Auth::attempt($credentials, $remember)) {
            // #region agent log
            $this->debugLog('C', 'LoginController.php:login', 'Auth attempt failed', [
                'validationErrors' => $request->validator?->errors()?->keys() ?? [],
            ]);
            // #endregion

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau kata sandi tidak valid.']);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user !== null && ! $user->is_active) {
            // #region agent log
            $this->debugLog('D', 'LoginController.php:login', 'Inactive user blocked', [
                'userId' => $user->id,
                'isActive' => $user->is_active,
            ]);
            // #endregion

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        // #region agent log
        $this->debugLog('E', 'LoginController.php:login', 'Login success redirecting', [
            'userId' => $user?->id,
            'intendedUrl' => session()->get('url.intended'),
        ]);
        // #endregion

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
