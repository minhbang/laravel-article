<?php
namespace Minhbang\LaravelArticle;

use Laracasts\Presenter\Presenter;
use Html;
use Minhbang\LaravelKit\Traits\Presenter\DatetimePresenter;

class ArticlePresenter extends Presenter
{
    use DatetimePresenter;

    public function summary()
    {
        return mb_string_limit($this->entity->summary, setting('display.summary_limit'));
    }

    /**
     * @param bool $small
     * @param string $class
     * @return string
     */
    public function imageHtml($class = '', $small = true)
    {
        if ($this->entity->image) {
            $url = $this->entity->getImageUrl($small);
            $title = $this->entity->title;
            $class = $class ? " class=\"{$class}\"" : '';
            return "<img{$class} src=\"{$url}\" alt=\"{$title}\" title=\"{$title}\">";
        } else {
            return '';
        }
    }

    /**
     * @param bool $small
     * @return mixed
     */
    public function imageUrl($small = true)
    {
        return $this->entity->getImageUrl($small);
    }

    /**
     * Ex: danh sách bài viết liên quan, bài viết mới nhất
     *
     * @param \Minhbang\LaravelArticle\Article[] $items
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
        $result = '';
        if ($tags = $this->entity->tagNames()) {
            $result = implode('</span><span class="label label-primary">', $tags);
        }
        return "<span class=\"label label-primary\">$result</span>";
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
                'hit'      => $this->entity->hit
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