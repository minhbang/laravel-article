<?php

namespace Minhbang\Article;

use Illuminate\Database\Eloquent\Collection;
use Laracasts\Presenter\PresentableTrait;
use Minhbang\Category\Categorized;
use Minhbang\Image\ImageableModel as Model;
use Minhbang\Kit\Traits\Model\AttributeQuery;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\FeaturedImage;
use Minhbang\Kit\Traits\Model\SearchQuery;
use Minhbang\Tag\Taggable;
use Minhbang\User\Support\HasOwner;
use Minhbang\Status\Traits\Statusable;

/**
 * Class Article
 *
 * @package Minhbang\Article
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string $content
 * @property int $hit
 * @property int $user_id
 * @property int $category_id
 * @property string|null $featured_image
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $published_at
 * @property string|null $status
 * @property-read \Minhbang\Category\Category $category
 * @property-read string $featured_image_sm_url
 * @property-read string $featured_image_url
 * @property string $linked_image_ids
 * @property-read string $linked_image_ids_original
 * @property string $tag_names
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Image\Image[] $images
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Tag\Tag[] $tags
 * @property-read \Minhbang\User\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article categorized($category = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Kit\Extensions\Model except($ids = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article exclusion($value, $attribute = 'id', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Kit\Extensions\Model findText($columns, $text)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article forSelectize($find, $take = 10)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article hasStr($str, $attribute = 'content', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article mine()
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article notMine()
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article orderByMatchedTag($tags, $direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article queryDefault()
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article ready($action, $by = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article related()
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article searchKeyword($keyword, $columns = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article searchWhereInDependent($column, $column_dependent, $fn, $empty = array())
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article tagged($tags, $all = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article taggedAll($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article taggedOne($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article today($field = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Kit\Extensions\Model whereAttributes($attributes)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereFeaturedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereHit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article withAuthor($attribute = 'username')
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article withCategoryTitle()
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Article\Article yesterday($same_time = false, $field = 'created_at')
 * @mixin \Eloquent
 */
class Article extends Model {
    use AttributeQuery;
    use DatetimeQuery;
    use Categorized;
    use HasOwner;
    use SearchQuery;
    use Taggable;
    use FeaturedImage;
    use Statusable;
    use PresentableTrait;

    protected $presenter = ArticlePresenter::class;
    protected $table = 'articles';
    protected $fillable = [ 'title', 'slug', 'summary', 'content', 'category_id', 'tag_names', 'status' ];
    protected $dates = [ 'published_at' ];

    /**
     * Article constructor.
     *
     * @param array $attributes
     */
    public function __construct( array $attributes = [] ) {
        parent::__construct( $attributes );
        $this->config( [
            'featured_image' => config( 'article.featured_image' ),
        ] );
    }

    /**
     * @return array Các attributes có thể insert image
     */
    public function imageables() {
        return [ 'content' ];
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeQueryDefault( $query ) {
        return $query->select( "{$this->table}.*" );
    }

    /**
     * Lấy $take user phục vụ selectize user
     *
     * @param \Illuminate\Database\Query\Builder|\Minhbang\User\User $query
     * @param string $find
     * @param int $take
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\Article\Article
     */
    public function scopeForSelectize( $query, $find = null, $take = 10 ) {
        return $this->scopeFindText( $query, [ 'title', 'slug' ], $find )->select( [ 'id', 'title' ] )->take( $take );
    }

    /**
     * Url xem Bài viết
     * getter $this->url
     *
     * @return string
     */
    public function getUrlAttribute() {
        return route( 'article.show', [ 'article' => $this->id, 'slug' => $this->slug ] );
    }

    /**
     * @param \Minhbang\Category\Category $category
     *
     * @return string
     */
    public static function getCategoryUrl( $category ) {
        return '#' . $category->id;
    }

    /**
     * setter $this->content = $value
     *
     * @param string $value
     */
    public function setContentAttribute( $value ) {
        $this->attributes['content'] = clean( $value );
    }

    /**
     * Lấy danh sách bài viết liên quan, tiêu chí:
     * - Cùng root category
     * - có liên quan về tags
     * - Sắp xếp theo số lượng tags giống nhau
     *
     * @param int $take
     *
     * @return \Minhbang\Article\Article[]
     */
    public function getRelated( $take = 5 ) {
        if ( ( $category = $this->category ) && ( $tagNames = $this->tagNames() ) ) {
            return Article::queryDefault()->ready('read')->take( $take )->categorized( $category->getRoot() )->except( $this->id )
                          ->taggedAll( $tagNames )->orderByMatchedTag( $tagNames )->orderUpdated()->get()->all();
        } else {
            return [];
        }
    }

    public function updateHit()
    {
        Article::where('id', $this->id)->increment('hit');
        $this->hit++;
    }
}
