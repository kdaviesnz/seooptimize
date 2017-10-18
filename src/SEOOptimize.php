<?php
declare(strict_types=1);

namespace kdaviesnz\seooptimize;

use kdaviesnz\seokeywords\SEOKeywords;

class SEOOptimize implements ISEOOptimize
{

    public static function optimize_meta_description_for_search_engines( string $meta_description ) {

        $wordlist = SEOKeywords::get_stop_words();

        foreach ($wordlist as &$word) {
            $word = '/\b' . preg_quote($word, '/') . '\b/';
        }

        $meta_descrp_trimmed = preg_replace($wordlist, '', $meta_description); // remove the stop words from description
        $meta_descrp_trimmed = preg_replace('!\s+!', ' ', $meta_descrp_trimmed);

        return $meta_descrp_trimmed;

    }

    public static function italiciseKeywordsCallback( array $keywords )  {
        return function ( $content ) use ( $keywords ) {
            $italicised_keywords = array_map( function( $item ) {
                return '<i>' . $item . '</i>';
            }, $keywords );
            $italicised_keywords = str_replace( $keywords, $italicised_keywords, $content );
            return $italicised_keywords;
        };
    }


    public static function boldKeywordsCallback( array $keywords )  {
        return function ( $content ) use ( $keywords ) {
            $bolded_keywords = array_map( function( $item ) {
                return '<b>' . $item . '</b>';
            }, $keywords );
            $bolded_content = str_replace( $keywords, $bolded_keywords, $content );
            return $bolded_content;
        };
    }

    public static function remove_stopwords_from_content( string $content )
    {

        $wordlist = SEO::get_stop_words();

        foreach ($wordlist as &$word) {
            $word = '/\b' . preg_quote($word, '/') . '\b/';
        }

        $content = preg_replace($wordlist, '', $content);

        $content_arr = explode('-', $content);


        $final_content_arr = array_count_values($content_arr);
        arsort($final_content_arr);


        // $content_with_stopwords_arr = explode(' ', $contetnt);

        $content = str_replace('-', ' ', $content);
        $content = preg_replace('!\s+!', ' ', $content); // remove double space

        $content = preg_replace('/\s+/', ' ', trim($content)); // remove enter key spaces

        return $content;

    }

    public static function optimize_content_for_search_engines(string $content)
    {

        $content = strip_tags($content);
        $content_original = strtolower($content);
        $content = strtolower($content);

        $content = str_replace('/[^\w]/', "", $content);

        $content = preg_replace('!\s+!', ' ', $content); // remove double space

        $content = preg_replace('/\s+/', ' ', trim($content)); // remove enter key spaces

        $content = preg_replace('/[^A-Za-z0-9\-]/', ' ', $content);
        $content = preg_replace("/[0-9]/", " ", $content);
        $content = str_replace(' ', '-', $content);
        $content = preg_replace('/-{2,}/', '-', $content);
        $content = str_replace('-', ' ', $content);

        $solo_stp_wrd = array('w', 'q', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'l', 'k', 'j', 'h', 'g', 'f', 'd', 's', 'a', 'z', 'c', 'x', 'v', 'b', 'n', 'm');
        foreach ($solo_stp_wrd as &$solo_stp) {
            $solo_stp = '/\b' . preg_quote($solo_stp, '/') . '\b/';
        }

        $content = preg_replace($solo_stp_wrd, '', $content);

        return $content;

    }

    public static function localize_title(array $address, string $title ) {
        $slug = str_replace( '-', ' ', $title );
        $slug_localized = $slug . (isset( $address['city'])? '-' . $address['city'] : '' )  . (isset( $address['country'])? '-' . $address['country'] : '' ) . (isset( $address['region'])? '-' . $address['region'] : '' );
        ;
        return $slug_localized;
    }

    public static function remove_stopwords(string $content)
    {
        $content_with_stopwords_removed = SEOOptimize::processContent( $content, array( SEOOptimize::remove_stopwords_callback()) );
        return $content_with_stopwords_removed;
    }

    public static function processContent( string $content, array $callbacks ) {

        foreach( $callbacks as $callback ) {
            $content = $callback( $content );
        }
        return $content;
    }

    public static function remove_stopwords_callback()  {
        $kw = new SEOKeywords();
        $stopwords = $kw->get_stop_words();
        return function ( $content ) use ( $stopwords ) {
            $content_with_no_stopwords = $content;
            foreach( $stopwords as $stopword ) {
                $content_with_no_stopwords = preg_replace("/\s\b$stopword\b\s/", ' ', $content_with_no_stopwords) ;
                $content_with_no_stopwords = trim(preg_replace("/^$stopword\b/", '', $content_with_no_stopwords) );
                $content_with_no_stopwords = trim(preg_replace("/\b$stopword$/", '', $content_with_no_stopwords) );

            }
            return $content_with_no_stopwords;
        };
    }

}
