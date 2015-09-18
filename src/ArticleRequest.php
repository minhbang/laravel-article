<?php
namespace Minhbang\LaravelArticle;

use Minhbang\LaravelKit\Extensions\Request;

/**
 * Class ArticleRequest
 *
 * @package Minhbang\LaravelArticle
 */
class ArticleRequest extends Request
{
    public $trans_prefix = 'article::common';
    public $rules = [
        'title'       => 'required|max:255',
        'slug'        => 'required|max:255|alpha_dash',
        'summary'     => 'required',
        'content'     => 'required',
        'category_id' => 'required|exists:categories,id',
        'image'       => 'image',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var \Minhbang\LaravelArticle\Article $article */
        if ($article = $this->route('article')) {
            // update
        } else {
            // create
            $this->rules['image'] .= '|required';
        }
        return $this->rules;
    }

}
