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
}