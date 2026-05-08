<?php

namespace App\Domain\Complaint\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUS_NEW      = 'new';
    public const STATUS_READ     = 'read';
    public const STATUS_REPLIED  = 'replied';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = ['name', 'email', 'subject', 'body', 'status', 'ip_address'];

    public function scopeUnread(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_NEW);
    }
}
