<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreUserPost;
use App\Models\Estates;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Estates $estates
     * @return Response
     */
    public function index(Estates $estates)
    {
        $users = $this->user->visible()->with(["estates"])->get()->sortBy("address");
        return view("users", [
            "users" => $users,
            "num_estates" => $estates->all()->count(),
            "confirmed" => $this->user->confirmed()->count() / $this->user->visible()->count() * 100,
            "canceled" => $this->user->canceled()->count() / $this->user->visible()->count() * 100
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view("new_user");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserPost $request
     * @param Estates $estates
     * @throws
     * @return Response
     */
    public function store(StoreUserPost $request, Estates $estates)
    {
         $newUser = $this->user->create([
             "name" => $request->input("name"),
             "name2" => $request->input("name2"),
             "email" => $request->input("email"),
             "visible" => 1,
             "password" => Hash::make("fiber")
         ]);

        if($newUser) {
            $estate = new $estates([
                "address" => $request->input("address"),
                "postalcode" => $request->input("postalcode"),
                "city" => $request->input("city"),
                "property_nbr" => $request->input("property_nbr"),
                "connections" => $request->input("connections"),
                "lat" => $request->input("lat"),
                "lon" => $request->input("lon")
            ]);

            $newUser->estates()->save($estate);

            return redirect("/users/" . $newUser->id);
        }

        throw exception("Kunde inte skapa en ny medlem");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view("user", ["user" => $this->user->with(["estates"])->find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        return view("edit_user", ["user" => $this->user->with("estates")->find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user_id
     * @return Response
     */
    public function update($user_id)
    {
        $this->user->find($user_id)->update([
            "name" => Input::get("name"),
            "name2" => Input::get("name2"),
            "email" => Input::get("email")
        ]);

        $to = Auth::User()->id === $user_id ? "/user" : "/users/" . $user_id;

        return redirect($to);
    }

    /**
     * @param $user_id
     * @param Carbon $carbon
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirmInterest($user_id, Carbon $carbon)
    {
        $this->user->find($user_id)->update([
            "confirmed_interest" => 1,
            "confirmed_interest_date" => $carbon->toDateString()
        ]);
        return redirect("/users/" . $user_id);
    }

    /**
     * @param $user_id
     * @param Carbon $carbon
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancelInterest($user_id, Carbon $carbon)
    {
        $this->user->find($user_id)->update([
            "confirmed_interest" => 2,
            "confirmed_interest_date" => $carbon->toDateString()
        ]);
        return redirect("/users/" . $user_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
