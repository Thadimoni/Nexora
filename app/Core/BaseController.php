<?php

class BaseController
{
    protected function view($view, $data = [])
    {
        $compiler = new TemplateCompiler();

        $compiledView = $compiler->compile($view);

        $engine = new TemplateEngine();

        $content = $engine->render($compiledView, $data);

        $layout = $compiler->getLayout();

        if ($layout) {

            $compiledLayout = $compiler->compile($layout);

            echo $engine->render($compiledLayout, $data);

        } else {

            echo $content;

        }
    }

    /**
     * Redirect to another page.
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back.
     */
    protected function back()
    {
        header(
            "Location: " .
            $_SERVER['HTTP_REFERER']
        );

        exit;
    }

    /**
     * Return JSON.
     */
    protected function json($data, $status = 200)
    {
        http_response_code($status);

        header("Content-Type: application/json");

        echo json_encode($data);

        exit;
    }
}