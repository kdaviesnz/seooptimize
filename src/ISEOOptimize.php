<?php

namespace kdaviesnz\seooptimize;


interface ISEOOptimize
{

    public static function remove_stopwords( string $post );
    public static function processContent( string $content, array $callbacks );
    public static function remove_stopwords_callback() ;
    public static function localize_title(array $address, string $title);
    public static function optimize_content_for_search_engines( string $content );
    public static function remove_stopwords_from_content( string $content );
    public static function boldKeywordsCallback( array $keywords ) ;
    public static function italiciseKeywordsCallback( array $keywords );
    public static function optimize_meta_description_for_search_engines( string $meta_content );

}