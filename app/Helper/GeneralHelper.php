<?php

use Illuminate\Support\Facades\Cache;
use Spatie\Valuestore\Valuestore;

function getSettingsOf($key) {
    $settings = Valuestore::make(config_path('settings.json'));
    return $settings->get($key);
}

function get_gravatar( $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

function clear_cache()
{
    Cache::forget('recent_comments');
    Cache::forget('recent_posts');
    Cache::forget('global_categories');
    Cache::forget('global_archives');
    Cache::forget('global_tags');
}
