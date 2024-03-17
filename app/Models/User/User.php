<?php

namespace App\Models\User;

use App\Services\Auth\Dtos\CreateUserDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function findForPassport($username): User
    {
        return $this->where('username', $username)->first();
    }

    protected $fillable = [
        'name',
        'surname',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['password' => 'hashed'];

    public static function staticCreate(CreateUserDto $dto): User
    {
        $user = new static();

        $user->setName($dto->name);
        $user->setSurname($dto->surname);
        $user->setUsername($dto->username);
        $user->setPassword($dto->password);

        return $user;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = bcrypt($password);
    }
}
