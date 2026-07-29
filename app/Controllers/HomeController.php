<?php

class HomeController extends BaseController
{


    public function index()
        {
            echo "Welcome to Nexora!";
            }

    public function show(Request $request)
{
    echo "Student ID: " . $request->route('id');
}
   
}