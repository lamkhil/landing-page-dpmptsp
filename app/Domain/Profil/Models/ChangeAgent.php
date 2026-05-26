<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A change agent (agen perubahan) of the Zona Integritas team, established by
 * the SK ZI. Each agent is assigned to one area perubahan (ProfilPoint) and is
 * shown inside that area's detail modal: foto, nama, NIK/NIP, jabatan, peran.
 */
class ChangeAgent extends Model
{
    public const ROLE_KETUA       = 'Ketua';
    public const ROLE_KOORDINATOR = 'Koordinator';
    public const ROLE_ANGGOTA     = 'Anggota';

    public const ROLES = [
        self::ROLE_KETUA       => 'Ketua',
        self::ROLE_KOORDINATOR => 'Koordinator',
        self::ROLE_ANGGOTA     => 'Anggota',
    ];

    protected $fillable = ['profil_point_id', 'name', 'nip', 'position', 'role', 'photo_path', 'sort_order', 'is_published'];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(ProfilPoint::class, 'profil_point_id');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
