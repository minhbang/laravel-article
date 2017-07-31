<?php namespace Minhbang\Article;

use Minhbang\Kit\Extensions\ModelTransformer;
use Html as HtmlBuilder;

class ArticleTransformer extends ModelTransformer
{
    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return array
     */
    public function transform(Article $article)
    {
        return [
            'id'      => (int)$article->id,
            'title'   => HtmlBuilder::linkQuickUpdate(
                $article->id,
                $article->title,
                [
                    'attr'  => 'title',
                    'title' => trans("article::common.title"),
                    'class' => 'w-lg',
                ]
            ),
            'author'  => $article->author,
            'status'         => $article->present()->statusQuickUpdate( route( "{$this->zone}.article.status", [ 'article' => $article->id, 'status' => 'STATUS' ] ) ),
            'updated_at'     => $article->present()->updatedAt(['template' => ':time, :date']),
            'actions' => HtmlBuilder::tableActions(
                "{$this->zone}.article",
                ['article' => $article->id],
                $article->title,
                trans("article::common.article"),
                [
                    'renderPreview' => 'modal-large',
                    'renderEdit'    => 'link',
                    'renderShow'    => 'link',
                ]
            ),
        ];
    }
}