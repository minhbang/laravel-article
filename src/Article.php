<?php
namespace Minhbang\LaravelArticle;

use Laracasts\Presenter\PresentableTrait;
use Conner\Tagging\TaggableTrait;
use Conner\Tagging\TaggingUtil;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;
use Minhbang\LaravelUser\Support\UserQuery;
use Minhbang\LaravelCategory\CategoryQuery;
use DB;

/**
 * Minhbang\LaravelArticle\Article
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string $content
 * @property integer $status
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
 * @property-read \Minhbang\LaravelCategory\CategoryItem $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\Conner\Tagging\Tagged[] $tagged
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereHit($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article related()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article orderByMatchedTag($tagNames, $direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article notMine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article mine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article withAuthor()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article categorized($category = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article withAllTags($tagNames)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article withAnyTag($tagNames)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 */
class Article extends Model
{
    use DatetimeQuery;
    use UserQuery;
    use CategoryQuery;
    use SearchQuery;
    use PresentableTrait;
    use TaggableTrait;
    protected $table = 'articles';
    protected $presenter = 'Minhbang\LaravelArticle\ArticlePresenter';
    protected $fillable = ['title', 'slug', 'summary', 'content', 'category_id'];
    /**
     * @var string Loại article (chính là slug của root category)
     */
    public $type;

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeQueryDefault($query)
    {
        return $query->select("{$this->table}.*");
    }

    /**
     * Bài viết liên quan
     *
     * @param \Illuminate\Database\Query\Builder|static $query
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeRelated($query)
    {
        return $this->scopeWithAllTags($query, $this->tagNames());
    }

    /**
     * Sắp xếp theo tags
     *
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param mixed $tagNames
     * @param string $direction
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeOrderByMatchedTag($query, $tagNames, $direction = 'desc')
    {
        if (empty($tagNames)) {
            return $query;
        }
        $pdo = DB::connection()->getPdo();
        $tagNames = TaggingUtil::makeTagArray($tagNames);
        $normalizer = config('tagging.normalizer');
        $normalizer = empty($normalizer) ? 'Conner\Tagging\TaggingUtil::slug' : $normalizer;
        $tagNames = array_map(
            function ($tagSlug) use ($pdo, $normalizer) {
                return $pdo->quote(call_user_func($normalizer, $tagSlug));
            },
            $tagNames
        );
        $tagNames = implode(',', $tagNames);
        $type = $pdo->quote(static::class);
        $t = 'tagging_tagged';
        return $query->addSelect(
            DB::raw(
                "(
                    SELECT COUNT(*)
                    FROM `$t`
                    WHERE `$t`.`taggable_id` = `articles`.`id` AND `$t`.`taggable_type` = $type AND `tag_slug` IN ($tagNames)
                ) AS `count_matched_tag`"
            )
        )->orderBy('count_matched_tag', $direction);
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
     * Call khi save Article
     *
     * @param \App\Http\Requests\Request $request
     */
    public function fillTags($request)
    {
        if ($this->exists) {
            if ($tags = $request->get('tags')) {
                $this->retag($tags);
            }
        } else {
            abort(500, 'Error: Must be call Article retag after save new record');
        }
    }

    /**
     * @param $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = clean($value);
    }

    /**
     * @param bool $full
     * @return string
     */
    public function getImageDirectory($full = true)
    {
        return ($full ? public_path() : '') . '/' . setting('system.public_files') . '/' . config('article.images_dir');
    }

    /**
     * @param null|string $image
     * @return string
     */
    public function getImagePath($image = null)
    {
        return $this->getImageDirectory() . '/' . ($image ?: $this->image);
    }

    /**
     * @param bool $small
     * @param bool $no_image
     * @return string|null
     */
    public function getImageUrl($small = false, $no_image = false)
    {
        if ($this->image) {
            return $this->getImageDirectory(false) . '/' . ($small ? $this->getImageSmall() : $this->image);
        } else {
            return $no_image ? '/build/img/no-image.png' : null;
        }
    }

    /**
     * Thêm '-small' vào tên file hình
     *
     * @return string
     */
    public function getImageSmall()
    {
        return "sm-$this->image";
    }

    /**
     * Xử lý image upload
     * - SEO tên file, thêm date time
     * - move đúng thư mục
     * - nếu edit thì xóa file cũ
     *
     * @param \Minhbang\LaravelArticle\ArticleRequest $request
     */
    public function fillImage($request)
    {
        $this->image = save_image(
            $request,
            'image',
            $this->image ? [$this->getImagePath(), $this->getImagePath($this->getImageSmall())] : null,
            $this->getImageDirectory(),
            [
                'main' => [
                    'width'  => setting('display.image_width_md'),
                    'height' => setting('display.image_height_md')
                ],
                'sm'   => [
                    'width'  => setting('display.image_width_sm'),
                    'height' => setting('display.image_height_sm')
                ]
            ],
            ['method' => 'fit'],
            $this->image
        );
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // trước khi xóa Post, sẽ xóa hình đại diện của nó
        static::deleting(
            function ($model) {
                /** @var static $model */
                if ($model->image) {
                    @unlink($model->getImagePath());
                    @unlink($model->getImagePath($model->getImageSmall()));
                }
            }
        );
    }
}
