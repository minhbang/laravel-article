<?php
namespace Minhbang\Article;

use Laracasts\Presenter\Presenter as BasePresenter;
use Html;
use Minhbang\LaravelKit\Traits\Presenter\DatetimePresenter;

/**
 * Class Presenter
 *
 * @package Minhbang\Article
 */
class Presenter extends BasePresenter
{
    use DatetimePresenter;

    /**
     * @return string
     */
    public function summary()
    {
        return mb_string_limit($this->entity->summary, setting('display.summary_limit'));
    }

    /**
     * @param string|null $class
     * @param bool $sm
     * @param bool $title
     *
     * @return string
     */
    public function featured_image($class = 'img-responsive', $sm = false, $title = false)
    {
        $src = $this->entity->featuredImageUrl($sm);
        $class = $class ? " class =\"$class\"" : '';
        $html = $title ? "<div class=\"title\">{$this->entity->name}</div>" : '';
        $sm = $sm ? '_sm' : '';
        $width = $this->entity->config['featured_image']["width{$sm}"];
        $height = $this->entity->config['featured_image']["height{$sm}"];
        return "<img{$class} src=\"$src\" title=\"{$this->entity->name}\" ath=\"{$this->entity->name}\" width=\"$width\" height=\"$height\" />{$html}";
    }

    /**
     * Ex: danh sách bài viết liên quan, bài viết mới nhất
     *
     * @param \Minhbang\Article\Article[] $items
     * @param string $name
     * @param string $title
     * @param array $timeFormat
     * @return string
     */
    public function linkListOf($items, $name, $title, $timeFormat = [])
    {
        $html = '';
        if (count($items)) {
            foreach ($items as $item) {
                $html .= '<li>' . $item->present()->linkWithTime($timeFormat) . '</li>';
            }
            $html = <<<"LIST"
<div class="link-list $name">
    <h3 class="link-list-title">$title</h3>
    <ul class="link-list-items">$html</ul>
</div>
LIST;
        }
        return $html;
    }

    public function tagsHtml()
    {
        if ($tags = $this->entity->tagNames()) {
            return '<span class="label label-primary">' . implode('</span><span class="label label-primary">', $tags) . '</span>';
        } else {
            return null;
        }
    }

    /**
     * Link xem article
     *
     * @return string
     */
    public function link()
    {
        return Html::link($this->entity->url, $this->entity->title);
    }

    /**
     * Link xem article
     *
     * @param array $timeFormat
     * @return string
     */
    public function linkWithTime($timeFormat = [])
    {
        return "<a href=\"{$this->entity->url}\">{$this->entity->title} <span class=\"time\">{$this->updatedAt($timeFormat)}</span></a>";
    }

    /**
     * Thông tin metadata của article
     *
     * @param bool $author
     * @return string
     */
    public function metadata($author = true)
    {
        $metadata = $author ? "<strong>{$this->entity->author}</strong><br>" : '';
        $metadata .= trans(
            'article.metadata',
            [
                'datetime' => $this->updatedAt(),
                'hit'      => $this->entity->hit,
            ]
        );
        return $metadata;
    }

    /**
     * Thông tin metadata của article
     *
     * @param bool $author
     * @return string
     */
    public function metadataBlock($author = true)
    {
        return '<div class="metadata" data-id="' . $this->entity->id . '">' . $this->metadata($author) . '</div>';
    }
}