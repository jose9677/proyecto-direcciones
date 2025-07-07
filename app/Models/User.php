<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'v_e',
        'ced',
        'p_a',
        's_a',
        'p_n',
        's_n',
        'f_n',
        'login',
        'email',
        'email_active',
        'id_rol',
        'otp',
        'active',
        'employee',
        'api_token',
        'email_verified_at',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        //'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

     public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'login' => $this->login,
            'id_rol' => $this->id_rol
        ];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function register(Request $request)
    {
        $user = User::create([
            'v_e' => Str::upper($request->v_e),
            'ced' => $request->ced,
            'p_a' => Str::upper($request->p_a),
            's_a' => Str::upper($request->s_a),
            'p_n' => Str::upper($request->p_n),
            's_n' => Str::upper($request->s_n),
            'f_n' => $request->f_n,
            'login' => $request->v_e.'-'.$request->ced,
            'email' => $request->email,
            'email_active' => false,
            'id_rol' => $request->id_rol,
            'otp' => $this->generateCodOtp(),
            'active' => false,
            'employee' => false,
            'password' => $request->password,
        ]);

        return $user;
    }

    public function registerEmployee(Request $request)
    {
        $user = User::create([
            'v_e' => Str::upper($request->v_e),
            'ced' => $request->ced,
            'p_a' => Str::upper($request->p_a),
            's_a' => Str::upper($request->s_a),
            'p_n' => Str::upper($request->p_n),
            's_n' => Str::upper($request->s_n),
            'f_n' => $request->f_n,
            'login' => $request->v_e.'-'.$request->ced,
            'email' => $request->email,
            'email_active' => false,
            'id_rol' => $request->id_rol,
            'active' => false,
            'employee' => true,
            'password' => $this->employeePaswword()
        ]);

        return $user;
    }

    protected function employeePaswword()
    {
        $lenght = 8;

        $characters = '0123456789#$%&/=-+_.';

        $characterLenght = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $lenght ; $i ++) { 
            $randomString .= $characters[rand(0, $characterLenght -1)];
        }

        return $randomString;
    }

    protected function generateCodOtp()
    {
        $lenght = 6;

        $characters = '0123456789';

        $charactersLenght = strlen($characters);

        $randomString = '';

        for ($i=0; $i < $lenght; $i++) { 
            $randomString .= $characters[rand(0, $charactersLenght -1)];
        }

        return $randomString;
    }

    public function refreshOTP(Request $request)
    {
            $new_otp = $this->generateCodOtp();

            $query = User::query()->where('login', $request->login)->update(['otp' => $new_otp]);
            $email = User::where('login', $request->login)->value('email');
            
            return $new_otp;
    }

    public function validateEmail(Request $request)
    {   
        $email = User::where('otp', $request->otp)->value('email');
        User::query()->where('otp', $request->otp)->update(['otp' => null, 'email_active' => true]);
    }

    public function changePassword(Request $request)
    {
        $new_pass = User::query()->where('login', $request->login)->update(['password' => Hash::make($request->password)]);
    }

    protected $table = 'users';
    protected $primaryKey = 'login';
    public $incrementing = false;
}
