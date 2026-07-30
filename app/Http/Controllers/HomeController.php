<?php

class HomeController extends BaseController
{
    public function index()
    {
        $this->view("dashboard.index", [
            "title" => "Dashboard"
        ]);
    }

    public function show(Request $request)
    {
        echo "Student ID: " . $request->route("id");
    }
}