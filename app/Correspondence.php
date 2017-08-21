<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correspondence extends Model
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id')->first();
    }

    public function correspondent()
    {
        return $this->belongsTo(Correspondent::class, 'correspondent_id', 'id')->first();
    }

    public function reply_correspondence()
    {
        return $this->belongsTo(Correspondence::class, 'reply_correspondence_id', 'id')->first();
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id')->first();
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id')->first();
    }


    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function document()
    {
        return $this->hasOne(Document::class, 'id', 'document_id')->first();
    }
}
