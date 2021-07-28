<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ChatMessage
 * @package App\Models
 */
class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'chat_id'];

    protected $table = 'chat_message';

    /**
     * @return belongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    /**
     * @return belongsTo
     */
    public function chat(): belongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }
}
