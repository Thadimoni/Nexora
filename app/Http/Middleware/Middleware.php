<?php

abstract class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     */
    abstract public function handle(Request $request);
}