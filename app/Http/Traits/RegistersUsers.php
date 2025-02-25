<?php

namespace App\Http\Traits;

use App\Models\PengaturanWebsite;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $pengaturanWebsite = PengaturanWebsite::first();
        $allowRegisteredRole = collect([
            'POLISI' => $pengaturanWebsite->izinkan_polisi_daftar ? 'POLISI' : null,
            'WARGA' => $pengaturanWebsite->izinkan_warga_daftar ? 'Warga / Masyarakat' : null,
        ])->filter()->toArray();

        return view('auth.register', ['allowRegisteredRole' => $allowRegisteredRole]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // event(new Registered($user = $this->create($request->all())));
        $user = $this->create($request->all());

        $pengaturanWebsite = PengaturanWebsite::first();
        $allowRegisteredRole = collect([
            'POLISI' => $pengaturanWebsite->izinkan_polisi_daftar ? 'POLISI' : null,
            'WARGA' => $pengaturanWebsite->izinkan_warga_daftar ? 'Warga / Masyarakat' : null,
        ])->filter()->toArray();

        // Validasi apakah role user diizinkan
        if (!array_key_exists($user->peran, $allowRegisteredRole)) {
            return $request->wantsJson()
                ? new JsonResponse(['message' => $user->peran . ' tidak diizinkan untuk registrasi.'], 403)
                : redirect()->route('register')
                ->with('error',  $user->peran . ' tidak diizinkan untuk registrasi.');
        }

        if ($user->peran == 'WARGA') {
            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Registrasi berhasil! Silahkan tunggu verifikasi dari Admin.'], 201)
                : redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silahkan tunggu verifikasi dari Admin.');
        }

        if ($user->peran == 'POLISI') {
            $user->profil_polisis()->create([
                'nrp' => DatabaseSeeder::randomNRP(),
                'pangkat_id' => null,
                'unit_id' => null,
                'jenis_kelamin' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Registrasi berhasil! Silahkan tunggu verifikasi dari Admin.'], 201)
                : redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silahkan tunggu verifikasi dari Admin.');
        }

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        $this->guard()->login($user);

        return $request->wantsJson()
            ? new JsonResponse(['message' => 'Registrasi berhasil! Silahkan cek email Anda untuk verifikasi.'], 201)
            : redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silahkan cek email Anda untuk verifikasi.');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
