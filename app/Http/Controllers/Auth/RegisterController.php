<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\RegistersUsers;
use App\Models\ProfilPolisi;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'peran' => ['required', 'string', 'in:POLISI,WARGA'],
            'nik' => ['required', 'string', 'numeric'],
            'no_hp' => ['required', 'string', 'min:10', 'max:13']
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa string.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.string' => 'Email harus berupa string.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email telah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'peran.required' => 'Peran harus dipilih.',
            'peran.string' => 'Format peran tidak valid.',
            'nik.required' => 'NIK harus diisi.',
            'nik.string' => 'NIK harus berupa string.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'no_hp.required' => 'Nomor HP harus diisi.',
            'no_hp.string' => 'Nomor HP harus berupa string.',
            'no_hp.min' => 'Nomor HP minimal 10 digit.',
            'no_hp.max' => 'Nomor HP maksimal 13 digit.',
            'no_hp.regex' => 'Format Nomor HP tidak valid.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'peran' => $data['peran'],
            'no_telepon' => $data['no_hp'],
            'no_whatsapp' => $data['no_hp'],
            'nik' => $data['nik'],
            'acc' => false, // polisi atau warga perlu acc dari admin terlebih dahulu.
            'password' => Hash::make($data['password']),
            'email_verified_at' => now()
        ]);

        if ($newUser->peran == 'POLISI') {
            ProfilPolisi::create([
                'user_id' => $newUser->id,
                'nrp' => DatabaseSeeder::randomNRP(),
                'jenis_kelamin' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $newUser;
    }
}
