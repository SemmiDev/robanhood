<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OneSignalController extends Controller
{

    public function register(Request $request)
    {
        $oneSignalId = $request->get('oneSignalId');
        if ($oneSignalId != "") {
            try {
                User::where('id', auth()->user()->id)->update(['onesignal_id' => $oneSignalId]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
