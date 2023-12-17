<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class TelegramUserController extends Controller
{
    /**
     * Handle an incoming login request.
     *
     */
    public function store(Request $request): JsonResponse
    {

        try {
            // Check Telegram authorization and prepare user data
            $user = $this->checkTelegramAuthorization($request->all());
            $user['telegram_id'] = $user['id'];
            unset($user['id']);

            // Check if the user already exists in the database
            $existingUser = (new User)->where('telegram_id', $user['telegram_id'])->first();

            if (!$existingUser) {
                // User doesn't exist, create a new user
                $createdUser = (new User)->create($user);
                event(new Registered($createdUser));
                Auth::login($createdUser);
            } else {
                // User already exists, log them in
                Auth::login($existingUser);
            }

            return response()->json([
                'status' => 'success',
                'statusCode' => 200,
                'url' => route('dashboard'),
                'message' => 'Successfully logged in as ' . $user['username']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 'failed',
                'statusCode' => 400,
                'url' => route('/'),
                'message' => $e
            ]);
        }

    }

    /**
     * Comparing the received hash parameter with the hexadecimal representation of
     * the HMAC-SHA-256 signature of the data-check-string with the SHA256 hash of
     * the bots token used as a secret key.
     *
     * https://core.telegram.org/widgets/login#widget-configuration
     *
     * @throws Exception
     */
    private function checkTelegramAuthorization($auth_data)
    {
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        ksort($auth_data);
        $data_check_string = urldecode(http_build_query($auth_data, "", "\n"));
        $secret_key = hash('sha256', env('BOT_TOKEN', ''), true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            throw new Exception('Data is NOT from Telegram');
        }
        if ((time() - $auth_data['auth_date']) > 86400) {
            throw new Exception('Data is outdated');
        }
        return $auth_data;
    }

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Redirect to the landing page
        return redirect(RouteServiceProvider::LANDING);
    }

}
