<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "genres";

    protected $guarded = ['id'];

}
