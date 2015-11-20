<?php
namespace Minhbang\Article;

use Minhbang\LaravelKit\Extensions\Request as BaseRequest;

/**
 * Class Request
 *
 * @package Minhbang\Article
 */
class Request extends BaseRequest
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
        /** @var \Minhbang\Article\Article $article */
        if ($article = $this->route('article')) {
            // update
        } else {
            // create
            $this->rules['image'] .= '|required';
        }
        return $this->rules;
    }

}
