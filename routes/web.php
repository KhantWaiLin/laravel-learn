<?php

use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\password;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
// $users = DB::select("select * from users where id = 1");
// $users = DB::select("select * from users where email=?", ["khantkhant.cm@gmail.com"]);
// $users = DB::select("select * from users ");
// $user = DB::insert("insert into users(name,email,password) values (?, ? , ?)", ['Marry', 'marry@gmail.com', 'qwerty']);
// $user = DB::update("update users set email='email@gmail.com' where id =?",[1]);
// $user = DB::delete("delete from users where id=?", [1]);
// $users = DB::table('users')->get();
// $user = DB::table('users')->where('id',3)->get();
// $email = DB::table('users')->where('name', 'Marry')->value('email');
// $user = DB::table('users')->find(3);
// $names = DB::table('users')->pluck('name');
// $insert = DB::table('users')->insert([
//     'name' => "kelly",
//     "email" => "kely@gmail.com",
//     "password" => "password"
// ]);
// $update = DB::table('users')->where('id',4)->update([
//     'email'=>'newemail@gmail.com'
// ]);
// $delete = DB::table('users')->where('email','newemail@gmail.com')->delete();
// $users = User::get();
// User::create([
//     "name" => "new user",
//     "email" => "newuser1@gmail.com",
//     "password" => "newpassword"
// ]);
// User::where('id',3)->update([
//     "name"=>"Marry Queen",
//     "password"=>"changedPassword"
// ]);
// $user = User::find(2);
// User::truncate();
// $user->delete();
// $user = User::find(1);
// dd($user->name);
// return view('welcome');
// });

Route::get("/", function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';

// Route::get('/openai', function () {
//     $result = OpenAI::completions()->create([
//         'model' => 'text-davinci-003',
//         'prompt' => 'PHP is',
//     ]);

//     echo $result['choices'][0]['text'];
// });
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    // dd($user);
    $user = User::firstOrCreate([
        'email' => $user->email,
    ], [
        'name' => $user->nickname,
        'password' => 'password'
    ]);
    // $user = User::find(
    //     'email',
    //     $user->email,
    // );
    Auth::login($user);

    return redirect('/dashboard');
});

Route::middleware('auth')->prefix('ticket')->group(function () {
    Route::resource('/', TicketController::class);
    // Route::get("/ticket/create", [TicketController::class, 'create'])->name('ticket.create');
    // Route::post("/ticket/create", [TicketController::class], 'store')->name('ticket.create');
});
