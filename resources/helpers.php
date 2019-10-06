<?php

if (! function_exists('avatar')) {
    function avatar($name): string
    {
        return Avatar::create($name)->toGravatar();
    }
}
