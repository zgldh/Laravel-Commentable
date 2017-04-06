<?php

/*
 * This file is part of Laravel Commentable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * This file is part of Laravel Commentable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Commentable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kalnoy\Nestedset\NodeTrait;

class Comment extends Model
{
    use NodeTrait;

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * @return mixed
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function creator()
    {
        return $this->morphTo('creator');
    }

    /**
     * @param Model $commentable
     * @param $data
     * @param Model $creator
     *
     * @return static
     */
    public function createComment(Model $commentable, $data, Model $creator)
    {
        return $commentable->comments()->create(array_merge($data, [
            'creator_id'   => $creator->id,
            'creator_type' => get_class($creator),
        ]));
    }

    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateComment($id, $data)
    {
        return (bool) static::find($id)->update($data);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteComment($id)
    {
        return (bool) static::find($id)->delete();
    }
}
