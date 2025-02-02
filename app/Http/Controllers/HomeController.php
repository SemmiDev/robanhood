<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Http\Controllers\Helpers\TimeBasedGreetingHelper;
use App\Models\Kasu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('app.index');
    }

    public function root(Request $request)
    {

        if (auth()->user()->peran == 'POLISI') {
            return redirect(route('root.dashboardPolisi'));
        }

        if (auth()->user()->peran == 'WARGA') {
            return redirect(route('root.dashboardWarga'));
        }

        return redirect(route('root.dashboardAdmin'));
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');

            // Buat nama file unik dengan timestamp
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();

            // Simpan di storage Laravel (storage/app/public/images)
            $avatarPath = $avatar->storeAs('images', $avatarName, 'public');

            // Simpan path ke database
            $user->avatar = $avatarPath;
            $user->save();
        }


        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "User Details Updated successfully!"
            // ], 200); // Status code here
            return redirect()->back();
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "Something went wrong!"
            // ], 200); // Status code here
            return redirect()->back();
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }

    public static function getResponseTimeMetrics()
    {
        $metrics = [
            'average_minutes' => 0,
            'fastest_response' => 0,
            'slowest_response' => 0,
            'total_cases' => 0,
            'responded_cases' => 0,
            'pending_cases' => 0
        ];

        $cases = Kasu::whereNotNull('waktu_respon')->get();
        $count = $cases->count();

        if ($count > 0) {
            $responseTimes = [];

            foreach ($cases as $case) {
                $responseTime = Carbon::parse($case->waktu_respon)
                    ->diffInMinutes(Carbon::parse($case->created_at));
                $responseTimes[] = $responseTime;
            }

            $metrics['average_minutes'] = array_sum($responseTimes) / count($responseTimes);
            $metrics['fastest_response'] = min($responseTimes);
            $metrics['slowest_response'] = max($responseTimes);
            $metrics['responded_cases'] = $count;
            $metrics['pending_cases'] = Kasu::whereNull('waktu_respon')->count();
            $metrics['total_cases'] = $metrics['responded_cases'] + $metrics['pending_cases'];
        }

        return $metrics;
    }
}
