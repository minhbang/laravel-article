<?php
namespace Minhbang\Article;

use Minhbang\Kit\Extensions\Html\DatetimeHtml;
use Minhbang\Security\AccessControllableHtml;

/**
 * Class Html
 *
 * @package Minhbang\Article
 */
class Html
{
    use DatetimeHtml;
    use AccessControllableHtml;

    /**
     * @param \Minhbang\Article\Article $article
     * @param string $classes
     *
     * @return null|string
     */
    public function tagsHtml($article, $classes = 'label label-primary')
    {
        if ($tags = $article->tagNames()) {
            return '<span class="' . $classes . '">' . implode('</span><span class="' . $classes . '">', $tags) . '</span>';
        } else {
            return null;
        }
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return string
     */
    public function summary($article)
    {
        return str_limit($article->summary, setting('display.summary_limit'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param bool $sm
     * @param bool $size
     *
     * @return string
     */
    public function featured_image($article, $sm = false, $size = false)
    {
        $src = $article->featuredImageUrl($sm);
        $sm = $sm ? '_sm' : '';
        $width = $article->config['featured_image']["width{$sm}"];
        $height = $article->config['featured_image']["height{$sm}"];
        $size = $size ? "width=\"$width\" height=\"$height\" " : '';

        return "<img src=\"{$src}\" alt=\"{$article->title}\" {$size}/>";
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return string
     */
    public function link($article)
    {
        return "<a href=\"{$article->url}\">{$article->title}</a>";
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param array $timeFormat
     * @param string $field
     *
     * @return string
     */
    public function linkWithTime(Article $article, $timeFormat = [], $field = 'updated_at')
    {
        $time = $this->formatDatetime($article->{$field}, $timeFormat);

        return "<a href=\"{$article->url}\">{$article->title} <span class=\"time\">{$time}</span></a>";
    }

    /**
     * Thông tin meta của article
     *
     * @param \Minhbang\Article\Article $article
     * @param bool|string $author
     * @param bool $br
     * @param string $datetime
     * @param array $datetimeOptions
     *
     * @return string
     */
    public function meta($article, $author = true, $br = true, $datetime = 'published_at', $datetimeOptions = [])
    {
        $br = $br ? '<br>' : ' — ';
        $html = $author ? '<strong>' . (is_string($author) ? $author : $article->author) . "</strong>$br" : '';
        $html .= trans(
            'article::common.meta',
            [
                'datetime' => $this->formatDatetime($article->{$datetime}, $datetimeOptions),
                'hit'      => $article->hit,
            ]
        );

        return $html;
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param bool|string $author
     * @param bool $br
     *
     * @return string
     */
    public function metaBlock($article, $author = true, $br = true)
    {
        return '<div class="meta" data-id="' . $article->id . '">' . $this->meta($article, $author, $br) . '</div>';
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return string
     */
    public function renderFeatured($article)
    {
        return <<<"ARTICLE"
<div class="latest">
    <a href="{$article->url}">
        {$this->featured_image($article)}
        <div class="info">
            <h4 class="title">{$article->title}</h4>
            {$this->metaBlock($article)}
        </div>
    </a>
</div>
ARTICLE;
    }

    /**
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
     * @param string $grid_cell
     *
     * @return string
     */
    public function renderList($articles, $grid_cell = 'col-lg-6 col-md-12')
    {
        $html = '';
        /** @var \Minhbang\Article\Article $article */
        foreach ($articles as $article) {
            $html .= <<<"ITEM"
<div class="{$grid_cell}">
    <div class="item">
        <div class="title"><a href="{$article->url}">{$article->title}</a></div>
        <a href="{$article->url}">{$this->featured_image($article, true)}</a>
        <div class="summary">
            {$this->summary($article)}
        </div>
        {$this->metaBlock($article)}
    </div>
</div>
ITEM;
        }

        return "<div class=\"list\"><div class=\"row\">$html</div></div>";
    }

    /**
     * Danh sách links bài viết
     *
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @param string $title
     * @param array $timeFormat
     * @param string $field
     *
     * @return string
     */
    public function renderListLink($items, $title, $timeFormat = [], $field = 'published_at')
    {
        $html = '';
        if ($items->count()) {
            foreach ($items as $item) {
                $html .= '<li>' . $this->linkWithTime($item, $timeFormat, $field) . '</li>';
            }
            $html = <<<"LIST"
<div class="article-list">
    <div class="title"><span>$title</span></div>
    <ul class="items">$html</ul>
</div>
LIST;
        }

        return $html;
    }
}