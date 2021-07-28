<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Chat
 * @package App\Models
 */
class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'owner_user_id', 'invited_user_id'];

    protected $table = 'chat';

    /**
     * @return belongsTo
     */
    public function invited(): belongsTo
    {
        return $this->belongsTo(User::class, 'invited_user_id', 'id');
    }

    public function owner(): belongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->HasMany(ChatMessage::class, 'chat_id');
    }
}
