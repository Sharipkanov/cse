<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id')->first();
    }

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id', 'id')->first();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function copy()
    {
        return $this->belongsTo(Document::class, 'parent_id', 'id')->first();
    }
}
