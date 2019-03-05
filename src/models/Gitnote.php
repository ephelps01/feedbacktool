<?php

namespace Ecd\Feedbacktool\Models;

use Illuminate\Database\Eloquent\Model;

class Gitnote extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gitnotes';
    protected $fillable = ['issue_id', 'comment_id', 'comment', 'developer_name'];

    public function issue()
    {
        return $this->belongsTo('Ecd\Feedbacktool\Models\Issue');
    }

}
