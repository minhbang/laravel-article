<?php namespace Minhbang\Article;

use Laracasts\Presenter\Presenter;
use Minhbang\Kit\Traits\Presenter\DatetimePresenter;
use Minhbang\Status\StatusPresenter;

/**
 * @property-read \Minhbang\Article\Article $entity
 * Class ArticlePresenter
 */
class ArticlePresenter extends Presenter
{
    use DatetimePresenter;
    use StatusPresenter;

    /**
     * @param array $options
     *
     * @return string
     */
    public function publishedAt($options = [])
    {
        return $this->formatDatetime($this->entity->published_at, $options);
    }

    /**
     * @param string $ver
     * @return string
     */
    public function featured_image($ver = '')
    {
        $src = $this->entity->featuredImageUrl($ver);

        return $src ? "<img src=\"{$src}\"/>" : null;
    }

    /**
     * @param string $classes
     *
     * @return null|string
     */
    public function tagsHtml($classes = 'label label-primary')
    {
        if ($tags = $this->entity->tagNames()) {
            return '<span class="'.$classes.'">'.implode('</span><span class="'.$classes.'">', $tags).'</span>';
        } else {
            return null;
        }
    }

    /**
     * @param null $limit
     * @return string
     */
    public function summary($limit = null)
    {
        $limit = is_null($limit) ? setting('display.summary_limit') : $limit;

        return mb_string_limit($this->entity->summary, $limit);
    }

    /**
     * @return string
     */
    public function link()
    {
        return "<a href=\"{$this->entity->url}\">{$this->entity->title}</a>";
    }

    /**
     * @param array $timeFormat
     * @param string $field
     *
     * @return string
     */
    public function linkWithTime($timeFormat = [], $field = 'updated_at')
    {
        $time = $this->formatDatetime($this->entity->{$field}, $timeFormat);

        return "<a href=\"{$this->entity->url}\">{$this->entity->title} <span class=\"time\">{$time}</span></a>";
    }

    /**
     * @param string $attribute
     *
     * @return mixed|null
     */
    public function author($attribute = 'name')
    {
        return $this->entity->user ? $this->entity->user->{$attribute} : null;
    }

    /**
     * Thông tin meta của article
     *
     * @param bool|string $author
     * @param bool $br
     * @param string $datetime
     * @param array $datetimeOptions
     *
     * @return string
     */
    public function meta($author = true, $br = true, $datetime = 'published_at', $datetimeOptions = [])
    {
        $datetime = $this->entity->{$datetime} ?: $this->entity->updated_at;
        $br = $br ? '<br>' : ' — ';
        $html = $author ? '<strong>'.(is_string($author) ? $author : $this->entity->author)."</strong>$br" : '';
        $html .= trans('article::common.meta', [
            'datetime' => $this->formatDatetime($datetime, $datetimeOptions),
            'hit' => $this->entity->hit,
        ]);

        return $html;
    }

    /**
     * @param bool|string $author
     * @param bool $br
     *
     * @return string
     */
    public function metaBlock($author = true, $br = true)
    {
        return '<div class="article-meta" data-id="'.$this->entity->id.'">'.$this->meta($author, $br).'</div>';
    }
}