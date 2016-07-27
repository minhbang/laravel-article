<?php
namespace Minhbang\Article;

use Minhbang\Kit\Support\VnString;
use DB;

/**
 * Class Seeder
 *
 * @package Minhbang\Article
 */
class Seeder
{
    /**
     * @param array $data
     */
    public function seed($data)
    {
        DB::table('articles')->truncate();

        $articles = $data;
        foreach ($articles as &$article) {
            $article['slug'] = VnString::to_slug($article['title']);
        }

        DB::table('articles')->insert($articles);
    }
}