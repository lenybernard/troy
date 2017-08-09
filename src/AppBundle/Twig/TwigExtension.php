<?php

namespace AppBundle\Twig;

/**
 * Class TwigExtension.
 *
 * @see http://stackoverflow.com/questions/10788138/instanceof-operator-in-twig-symfony-2/27038954#27038954
 */
class TwigExtension extends \Twig_Extension
{
    /**
     * @var
     */
    private $giphyApiKey;

    /**
     * TwigExtension constructor.
     *
     * @param $giphyApiKey
     */
    public function __construct($giphyApiKey)
    {
        $this->giphyApiKey = $giphyApiKey;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('giphy', [$this, 'giphy']),
        ];
    }

    /**
     * @param $value
     * @param $size
     * @param mixed $term
     *
     * @return string
     */
    public function giphy($term = 'random')
    {
        $gif = json_decode(
            file_get_contents(
                sprintf(
                    'http://api.giphy.com/v1/gifs/random?api_key=%s&tag='.$term,
                    $this->giphyApiKey
                )
            ),
            true);

        return $gif['data']['image_url'];
    }
}
