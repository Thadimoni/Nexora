<?php

interface MiddlewareInterface
{
    /**
     * Handle the incoming request.
     */
    public function handle(Request $request);
}