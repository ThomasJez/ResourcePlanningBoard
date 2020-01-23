<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rule()
    {
        return $this->belongsTo('App\Rule');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getEndAttribute() {
        return (new \DateTime($this->start))
            ->add(new \DateInterval('P' . $this->duration . 'D'))
            ->format('Y-m-d');
    }
}
