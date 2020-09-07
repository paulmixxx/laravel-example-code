<?php

namespace Core\Auth\Entities\User;

use Core\AggregateRootInterface;
use Core\Auth\Events\NewActivateTokenGeneratedEvent;
use Core\Auth\Events\UserRegisterConfirmedByEmailEvent;
use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Events\UserRegisteredByPhoneEvent;
use Core\Auth\Services\ActivateTokenizer;
use Core\Auth\Services\PasswordHasher;
use Core\Auth\Services\RememberTokenizer;
use Core\EventsTrait;
use DateTimeImmutable;

class User implements AggregateRootInterface
{
    use EventsTrait;

    /**
     * @var Id
     */
    private $id;
    /**
     * @var Code
     */
    private $code;
    /**
     * @var PasswordHash
     */
    private $passwordHash;
    /**
     * @var DateTimeImmutable
     */
    private $dateCreate;
    /**
     * @var DateTimeImmutable
     */
    private $dateUpdate;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var Role
     */
    private $role;
    /**
     * @var Name|null
     */
    private $name;
    /**
     * @var Email|null
     */
    private $email;
    /**
     * @var Phone|null
     */
    private $phone;
    /**
     * @var RememberToken
     */
    private $rememberToken;
    /**
     * @var ActivateToken
     */
    private $activateToken;

    public function __construct(
        Id $id,
        Code $code,
        PasswordHash $passwordHash,
        RememberToken $rememberToken,
        DateTimeImmutable $dateCreate,
        DateTimeImmutable $dateUpdate,
        Status $status,
        Role $role,
        ?Name $name = null,
        ?Email $email = null,
        ?Phone $phone = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->passwordHash = $passwordHash;
        $this->rememberToken = $rememberToken;
        $this->dateCreate = $dateCreate;
        $this->dateUpdate = $dateUpdate;
        $this->status = $status;
        $this->role = $role;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * @param Name $name
     * @param Email $email
     * @param Password $password
     * @param DateTimeImmutable $date
     * @param ActivateToken $activateToken
     * @return User
     * @throws \Core\Auth\Exceptions\UserIdException
     */
    public static function addByEmail(
        Name $name,
        Email $email,
        Password $password,
        DateTimeImmutable $date,
        ActivateToken $activateToken
    ) {
        $user = new self(
            new Id(null),
            Code::gen(),
            PasswordHasher::getHash($password),
            RememberTokenizer::gen(),
            $date,
            $date,
            Status::unconfirmed(),
            Role::user(),
            $name,
            $email,
            null
        );
        $user->setActivateToken($activateToken);

        $user->recordEvent(new UserRegisteredByEmailEvent($user));

        return $user;
    }

    /**
     * @param Phone $phone
     * @param Password $password
     * @param DateTimeImmutable $date
     * @return User
     * @throws \Core\Auth\Exceptions\UserIdException
     */
    public static function addByPhone(
        Phone $phone,
        Password $password,
        DateTimeImmutable $date
    ) {
        $user = new self(
            new Id(null),
            Code::gen(),
            PasswordHasher::getHash($password),
            RememberTokenizer::gen(),
            $date,
            $date,
            Status::inactive(),
            Role::user(),
            null,
            null,
            $phone
        );

        $user->recordEvent(new UserRegisteredByPhoneEvent($user));

        return $user;
    }

    /**
     * @return $this
     */
    public function confirmByEmail()
    {
        $this->deleteActivateToken();
        $this->setStatus(Status::active());

        $this->recordEvent(new UserRegisterConfirmedByEmailEvent($this));

        return $this;
    }

    /**
     * @param ActivateToken $activateToken
     * @return $this
     */
    public function generateNewActivateToken(ActivateToken $activateToken)
    {
        $this->setActivateToken($activateToken);

        $this->recordEvent(new NewActivateTokenGeneratedEvent($this));

        return $this;
    }

    /**
     * @return Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Email|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @return ActivateToken
     */
    public function getActivateToken()
    {
        return $this->activateToken;
    }

    /**
     * @return Name|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PasswordHash
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Phone|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param Name|null $name
     */
    public function setName(?Name $name)
    {
        $this->name = $name;
    }

    /**
     * @param Email|null $email
     */
    public function setEmail(?Email $email)
    {
        $this->email = $email;
    }

    /**
     * @param Phone|null $phone
     */
    public function setPhone(?Phone $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param ActivateToken|null $activateToken
     */
    public function setActivateToken(?ActivateToken $activateToken)
    {
        $this->activateToken = $activateToken;
    }

    /**
     * @return bool
     */
    public function hasActiveToken()
    {
        return $this->getActivateToken() !== null;
    }

    /**
     * @return $this
     */
    public function deleteActivateToken()
    {
        $this->activateToken = null;

        return $this;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;
    }

    public function isUnconfirmed()
    {
        return $this->status->isUnconfirmed();
    }
}
