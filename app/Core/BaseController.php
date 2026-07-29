<?php

class BaseController
{
    protected function view($view, $data = [])
    {
        // Compile the requested view
        $compiler = new TemplateCompiler();

        $compiledView = $compiler->compile($view);

        // Create the rendering engine
        $engine = new TemplateEngine();

        // Render the child view first
        $content = $engine->render($compiledView, $data);

        // Check if the view extends a layout
        $layout = $compiler->getLayout();

        if ($layout)
        {
            // Compile the layout
            $compiledLayout = $compiler->compile($layout);

            // Render the layout
            echo $engine->render($compiledLayout, $data);
        }
        else
        {
            echo $content;
        }
    }
}