<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseEntity;
use DateTime;

class User extends BaseEntity
{
    public ?string $id = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public ?string $avatar = null;

    public string $timezone = 'Asia/Jakarta';

    public string $locale = 'id';

    public string $theme = 'light';

    public ?string $remember_token = null;

    public ?DateTime $email_verified_at = null;

    public ?DateTime $last_login_at = null;

    public ?string $last_login_ip = null;

    public bool $is_active = true;

    public ?DateTime $created_at = null;

    public ?DateTime $updated_at = null;

    public ?DateTime $deleted_at = null;

    public function fill(array $data): void
    {
        $this->id = $data['id'] ?? $this->id;

        $this->name = $data['name'] ?? $this->name;

        $this->email = strtolower($data['email'] ?? $this->email);

        $this->password = $data['password'] ?? $this->password;

        $this->avatar = $data['avatar'] ?? $this->avatar;

        $this->timezone = $data['timezone'] ?? $this->timezone;

        $this->locale = $data['locale'] ?? $this->locale;

        $this->theme = $data['theme'] ?? $this->theme;

        $this->remember_token = $data['remember_token'] ?? $this->remember_token;

        $this->email_verified_at = $this->parseDate($data['email_verified_at'] ?? null);

        $this->last_login_at = $this->parseDate($data['last_login_at'] ?? null);

        $this->last_login_ip = $data['last_login_ip'] ?? $this->last_login_ip;

        $this->is_active = (bool)($data['is_active'] ?? $this->is_active);

        $this->created_at = $this->parseDate($data['created_at'] ?? null);

        $this->updated_at = $this->parseDate($data['updated_at'] ?? null);

        $this->deleted_at = $this->parseDate($data['deleted_at'] ?? null);
    }

    public function toArray(): array
    {
        return [

            'id'=>$this->id,

            'name'=>$this->name,

            'email'=>$this->email,

            'password'=>$this->password,

            'avatar'=>$this->avatar,

            'timezone'=>$this->timezone,

            'locale'=>$this->locale,

            'theme'=>$this->theme,

            'remember_token'=>$this->remember_token,

            'email_verified_at'=>$this->formatDate($this->email_verified_at),

            'last_login_at'=>$this->formatDate($this->last_login_at),

            'last_login_ip'=>$this->last_login_ip,

            'is_active'=>$this->is_active,

            'created_at'=>$this->formatDate($this->created_at),

            'updated_at'=>$this->formatDate($this->updated_at),

            'deleted_at'=>$this->formatDate($this->deleted_at)
        ];
    }
}