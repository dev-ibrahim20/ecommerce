<?php

function lang( $phrase ) {
    
    static $lang = array(
        
        'MESSAGE' => 'WELCOME arabic',
        
        'ADMIN' => 'Administrator arabic'
    
    );
    
    return $lang[$phrase];
}