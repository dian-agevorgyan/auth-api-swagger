<?php

namespace App\Services\Auth\Actions;

use Laravel\Passport\Client;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\Auth\TokensResource;
use App\Exceptions\User\UserNotFoundException;
use App\Repositories\Read\User\UserReadRepositoryInterface;

class LoginAction
{
    public function __construct(protected UserReadRepositoryInterface $userReadRepository) {}

    /**
     * @throws UserNotFoundException
     */
    public function run(string $username, string $password): TokensResource
    {
        $user = $this->userReadRepository->getByUsername($username);

        $oClientId = config('passport.password_grant_client.id');
        $oClient = Client::where('id', $oClientId)->first();

        $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'username' => $username,
            'password' => $password,
            'scope' => '*',
        ]);

        $data = json_decode($response->getBody()->getContents());
        $data->user = $user;

        if (property_exists($data, 'errors')) {
            throw new UserNotFoundException();
        }

        return new TokensResource($data);
    }
}
