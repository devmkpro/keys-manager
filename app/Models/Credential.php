<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Credential extends Model
{
     /**
     * Campos preenchíveis via mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'service_name',
        'username',
        'encrypted_password',
        'site_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    /**
     * Acessor para obter a senha descriptografada.
     *
     * @return string
     */
    public function getPasswordAttribute()
    {
        return Crypt::decryptString($this->encrypted_password);
    }

    /**
     * Mutator para criptografar a senha antes de salvá-la.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['encrypted_password'] = Crypt::encryptString($value);
    }

    /**
     * Relacionamento com o usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
