<?php
namespace Minhbang\LaravelArticle;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\LaravelKit\Traits\Model\AttributeQuery;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;
use Minhbang\LaravelKit\Traits\Model\TaggableTrait;
use Minhbang\LaravelUser\Support\UserQuery;
use Minhbang\LaravelCategory\CategoryQuery;
use Image;

/**
 * Class Article
 *
 * @package Minhbang\LaravelArticle
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
 * @property-write mixed $tags
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
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article hasStr($str, $attribute = 'content', $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article exclusion($value, $attribute = 'id', $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article notMine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article mine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article withAuthor()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article categorized($category = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article searchWhereInDependent($column, $column_dependent, $fn, $empty = array())
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article related()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article orderByMatchedTag($tagNames, $direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article withAllTags($tagNames)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelArticle\Article withAnyTag($tagNames)
 */
class Article extends Model
{
    use AttributeQuery;
    use DatetimeQuery;
    use UserQuery;
    use CategoryQuery;
    use SearchQuery;
    use PresentableTrait;
    use TaggableTrait;
    protected $table = 'articles';
    protected $presenter = 'Minhbang\LaravelArticle\ArticlePresenter';
    protected $fillable = ['title', 'slug', 'summary', 'content', 'category_id', 'tags'];

    /**
     * @var array các attribute có image
     */
    public $has_images = 'content';

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

        // cập nhật image used count
        static::saving(
            function ($model) {
                /** @var static $model */
                Image::updateDB($model);
            }
        );
    }
}
