<?php
return [
     'HTML_CACHE_ON' => 'on', //on is use html cache; off is shutup

     'HTML_CACHE_RULES' =>[
                'Index:index'     => [md5($_SERVER['HTTP_HOST'].'-'.$_SERVER['REQUEST_URI']), 180],
                'Index:testviewcache'     => [md5($_SERVER['HTTP_HOST'].'-'.$_SERVER['REQUEST_URI']), 180],
                'Search:index'    => [md5($_SERVER['HTTP_HOST'].'-'.$_SERVER['REQUEST_URI']), 120],
                'Article:'        => [md5($_SERVER['HTTP_HOST'].'-'.$_SERVER['REQUEST_URI']), 600],
     ],
];