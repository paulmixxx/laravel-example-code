<?php

namespace Core\Auth\Repositories;

use App\User;
use App\UserActivateTokenByEmail;
use Core\Auth\Entities\User\Id;
use Core\Auth\Entities\User\Code;
use Core\Auth\Entities\User\Email;
use Core\Auth\Entities\User\ActivateToken;
use Core\Auth\Entities\User\Phone;
use Core\Auth\Entities\User\Role;
use Core\Auth\Entities\User\Status;
use Core\Auth\Entities\User\Name;
use Core\Auth\Entities\User\User as UserEntity;
use Core\Auth\Services\ActivateTokenizer;
use Core\Auth\Services\PasswordHasher;
use Core\Auth\Services\RememberTokenizer;
use DateTimeImmutable;
use Exception;

class UserRepository
{
    /**
     * @param UserEntity $User
     * @return bool
     */
    public function add(UserEntity $User)
    {
        $user = $this->pack($User);
        $user->save();
        $user = $this->saveActivateToken($user, $User);

        return $user->save();
    }


    public function update(UserEntity $User)
    {
        $user = $this->pack($User);
        $user->save();
        $user = $this->saveActivateToken($user, $User);

        return $user->save();
    }

    /**
     * @param Email $email
     * @return bool
     */
    public function hasByEmail(Email $email)
    {
        return User::where("email", $email->getValue())->count() > 0;
    }

    /**
     * @param Email $email
     * @return UserEntity|null
     * @throws Exception
     */
    public function findByEmail(Email $email)
    {
        $user = User::where('email', $email->getValue())->first();

        if (null === $user) {
            return null;
        }

        return $this->unpack($user);
    }

    /**
     * @param $token
     * @return UserEntity
     * @throws Exception
     */
    public function findByActivateToken(ActivateToken $token)
    {
        $activateToken = UserActivateTokenByEmail::where('token', '=', $token->getValue())->first();

        if (null === $activateToken) {
            return null;
        }

        return $this->unpack($activateToken->user);
    }

    public function saveActivateToken(User $user, UserEntity $userEntity)
    {
        if ($userEntity->hasActiveToken()) {
            if ($user->activateToken()->exists()) {
                $user->activateToken()->update([
                    'token' => $userEntity->getActivateToken()->getValue(),
                    'expire_time' => $userEntity->getActivateToken()->getExpiresTime()
                ]);
            } else {
                $user->activateToken()->create([
                    'token' => $userEntity->getActivateToken()->getValue(),
                    'expire_time' => $userEntity->getActivateToken()->getExpiresTime()
                ]);
            }
        } else {
            $user->activateToken()->delete();
        }

        return $user;
    }

    private function pack($User)
    {
        /** @var UserEntity $User */
        if (!$user = User::find($User->getId()->getValue())) {
            $user = new User();
        }
        $user->id = $User->getId()->getValue();
        $user->code = $User->getCode()->getValue();
        $user->name = $User->getName()->getFullName();
        $user->email = $User->getEmail()->getValue();
        $user->password = $User->getPasswordHash()->getValue();
        $user->remember_token = $User->getRememberToken()->getValue();
        $user->created_at = $User->getDateCreate();
        $user->updated_at = $User->getDateUpdate();
        $user->role_id = $User->getRole()->getValue();
        $user->status_id = $User->getStatus()->getValue();

        return $user;
    }

    /**
     * @param User $user
     * @return UserEntity
     * @throws Exception
     */
    private function unpack($user)
    {
        $User = new UserEntity(
            new Id($user->id),
            new Code($user->code),
            new PasswordHasher($user->password),
            new RememberTokenizer($user->remember_token),
            new DateTimeImmutable($user->created_at),
            new DateTimeImmutable($user->updated_at),
            new Status($user->status_id),
            new Role($user->role_id),
            null,
            null,
            null
        );

        if ($name = $user->name) {
            $User->setName(new Name($name));
        }

        if ($email = $user->email) {
            $User->setEmail(new Email($email));
        }

        if ($phone = $user->phone) {
            $User->setPhone(new Phone($phone));
        }

        if ($activateToken = $user->activateToken) {
            $User->setActivateToken(new ActivateTokenizer(
                $activateToken->token,
                new DateTimeImmutable($activateToken->expire_time)
            ));
        }

        return $User;
    }
}
