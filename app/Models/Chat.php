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

    protected $fillable = ['chat_id', 'user_id', 'companion_id'];

    protected $table = 'chat';

    /**
     * @return belongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->HasMany(ChatMessage::class, 'chat_id');
    }
}
