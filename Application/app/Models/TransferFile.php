<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'transfer_id',
        'storage_provider_id',
        'ip',
        'name',
        'filename',
        'mime',
        'extension',
        'size',
        'path',
        'downloads',
        'starred',
        'archived',
        'can_edit',
        'can_view',
        'is_public',
        'folder_id'
        
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function storageProvider()
    {
        return $this->belongsTo(StorageProvider::class, 'storage_provider_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id', 'id');
    }

}
