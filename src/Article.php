<?php
namespace Minhbang\Article;

use Minhbang\AccessControl\Contracts\ResourceLevel;
use Minhbang\AccessControl\Contracts\ResourceModel;
use Minhbang\AccessControl\Contracts\ResourceStatus;
use Minhbang\AccessControl\Traits\Resource\DefaultStatuses;
use Minhbang\AccessControl\Traits\Resource\HasLevel;
use Minhbang\AccessControl\Traits\Resource\HasPermission;
use Minhbang\AccessControl\Traits\Resource\HasPolicy;
use Minhbang\AccessControl\Traits\Resource\HasStatus;
use Minhbang\Category\Categorized;
use Minhbang\LaravelImage\ImageableModel as Model;
use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Traits\Model\AttributeQuery;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\FeaturedImage;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;
use Minhbang\LaravelKit\Traits\Model\TaggableTrait;
use Minhbang\LaravelUser\Support\UserQuery;

/**
 * Class Article
 *
 * @package Minhbang\Article
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string $content
 * @property integer $status
 * @property integer $level
 * @property integer $hit
 * @property integer $user_id
 * @property integer $category_id
 * @property string $image
 * @property string $published_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read mixed $url
 * @property-read mixed $resource_name
 * @property-read \Minhbang\LaravelUser\User $user
 * @property-read \Minhbang\Category\Item $category
 * @property mixed $tags
 * @property-read \Illuminate\Database\Eloquent\Collection|\Conner\Tagging\Tagged[] $tagged
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereHit($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article hasStr($str, $attribute = 'content', $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article exclusion($value, $attribute = 'id', $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article notMine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article mine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article withAuthor()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article categorized($category = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article related()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article orderByMatchedTag($tagNames, $direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article withAllTags($tagNames)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Article\Article withAnyTag($tagNames)
 */
class Article extends Model implements ResourceModel, ResourceStatus, ResourceLevel
{
    use AttributeQuery;
    use DatetimeQuery;
    use Categorized;
    use UserQuery;
    use SearchQuery;
    use PresentableTrait;
    use TaggableTrait;
    use FeaturedImage;
    use HasPolicy;
    use HasPermission;
    use HasLevel;
    use HasStatus;
    use DefaultStatuses;

    protected $table = 'articles';
    protected $presenter = 'Minhbang\Article\Presenter';
    protected $policy_class = 'Minhbang\AccessControl\Policies\StatusBasedPolicy';
    protected $fillable = ['title', 'slug', 'summary', 'content', 'category_id', 'tags'];

    /**
     * Article constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->config([
            'featured_image' => config('article.featured_image'),
        ]);
    }

    /**
     * @var string Loại article (chính là slug của root category)
     */
    public $type;

    /**
     * @return string
     */
    protected function resourceName()
    {
        return 'article';
    }

    /**
     * @return string
     */
    protected function resourceTitle()
    {
        return trans('article::common.article');
    }

    /**
     * @return array Các attributes có thể insert image
     */
    public function imageables()
    {
        return ['content'];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return ['create', 'show', 'update', 'delete', 'return', 'approve', 'publish'];
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeQueryDefault($query)
    {
        return $query->select("{$this->table}.*");
    }

    /**
     * Url xem Bài viết
     *
     * @return string $article->url
     */
    public function getUrlAttribute()
    {
        if (empty($this->type)) {
            return route('article.show', ['article' => $this->id, 'slug' => $this->slug]);
        } else {
            return route(
                'article.show_with_type',
                ['article' => $this->id, 'slug' => $this->slug, 'type' => $this->type]
            );
        }
    }

    /**
     * @param string $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = clean($value);
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // trước khi xóa $model, sẽ xóa hình đại diện của nó
        static::deleting(
            function ($model) {
                /** @var static $model */
                $model->deleteFeaturedImage();
            }
        );
    }
}
