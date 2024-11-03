<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;


class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    use HasFactory;

    protected $table = 'm_user'; //nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id'; //primary key dari tabel yang digunakan

    protected $fillable = ['username', 'nama', 'password', 'level_id','avatar','updates_at'];

    protected $hidden = ['password']; //tidak ditampilkan saat select
    protected $casts = ['password' => 'hashed']; //otomatis meng-hash password

    //relasi ke tabel level
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($avatar) => url('/storage/posts/' . $avatar),
        );
    }

    //mendapatkan nama role
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    //cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    public function getRole()
    {
        return $this->level->level_kode;
    }
}

