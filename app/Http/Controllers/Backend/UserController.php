<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
Use Nexmo;
class UserController extends Controller
{
    public function create()
    {
        return view('backend.user.create');
    }

    public function store(Request $request){
        $code = rand(1111, 9999);
        $user = new Contact();
        $user->phone = $request->phone;
        $user->code = $code;
        $user->save();

        $nexmo = app('Nexmo\Client');
        $nexmo->message()->send([
            'to' => '+880'.(int)$request->phone,
            'from' => 'Anik',
            'text' => 'Verify Code: '.$code,
        ]);

        return redirect('/verify');
    }

    public function getVerify(){
        return view('backend.user.verify');
    }

    public function postVerify(Request $request){
        $check = Contact::where('code', $request->code)->first();
        if($check){
            $check->code=Null;
            $check->save();
            return redirect('/');
        }else{
            return back()->withMessage('Verify Code is not correct');
        }
    }
}
