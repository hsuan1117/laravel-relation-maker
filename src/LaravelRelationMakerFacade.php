<?php

namespace Hsuan\LaravelRelationMaker;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hsuan\LaravelRelationMaker\LaravelRelationMaker
 */
class LaravelRelationMakerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-relation-maker';
    }
}
