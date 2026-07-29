<?php

class TemplateCompiler
{
    protected $viewsPath = "../app/Views/";
    protected $cachePath = "../app/Cache/";

    protected $directives = [
        "@year" => "<?php echo date('Y'); ?>",
    ];

    /**
     * Stores the layout detected by @extends().
     */
    protected $layout = null;

    /**
     * Compile a .nex view into a PHP file.
     */
    public function compile($view)
    {
        $this->reset();

        $viewFile = $this->viewsPath . $view . ".nex";
        $compiledFile = $this->cachePath . $view . ".php";

        if (!file_exists($viewFile)) {
            throw new Exception("View '{$view}' not found.");
        }

        $template = file_get_contents($viewFile);

        $template = $this->compileSimpleDirectives($template);
        $template = $this->compileBlockDirectives($template);
        $template = $this->compileVariables($template);

        $this->writeCache($compiledFile, $template);

        return $compiledFile;
    }

    /**
     * Returns the layout detected during compilation.
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Reset compiler state.
     */
    protected function reset()
    {
        $this->layout = null;
    }

    /**
     * Compile simple directives like @year.
     */
    protected function compileSimpleDirectives($template)
    {
        foreach ($this->directives as $search => $replace) {
            $template = str_replace($search, $replace, $template);
        }

        return $template;
    }

    /**
     * Compile variables.
     */
    protected function compileVariables($template)
    {
        return preg_replace(
            '/\{\{\s*(.+?)\s*\}\}/',
            '<?php echo htmlspecialchars($1); ?>',
            $template
        );
    }

    /**
     * Write compiled file to cache.
     */
    protected function writeCache($compiledFile, $template)
    {
        $directory = dirname($compiledFile);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($compiledFile, $template);
    }

    /**
     * Compile all block directives.
     */
    protected function compileBlockDirectives($template)
    {
        $template = $this->compileExtends($template);

        $template = $this->compileIf($template);
        $template = $this->compileElseIf($template);
        $template = $this->compileElse($template);
        $template = $this->compileEndIf($template);

        $template = $this->compileForeach($template);
        $template = $this->compileEndForeach($template);

        $template = $this->compileSection($template);
        $template = $this->compileEndSection($template);

        $template = $this->compileYield($template);

        $template = $this->compileInclude($template);

        return $template;
    }

    protected function compileIf($template)
    {
        return preg_replace(
            '/@if\((.+?)\)/',
            '<?php if($1): ?>',
            $template
        );
    }

    protected function compileElseIf($template)
    {
        return preg_replace(
            '/@elseif\((.+?)\)/',
            '<?php elseif($1): ?>',
            $template
        );
    }

    protected function compileElse($template)
    {
        return str_replace(
            '@else',
            '<?php else: ?>',
            $template
        );
    }

    protected function compileEndIf($template)
    {
        return str_replace(
            '@endif',
            '<?php endif; ?>',
            $template
        );
    }

    protected function compileForeach($template)
    {
        return preg_replace(
            '/@foreach\((.+?)\)/',
            '<?php foreach($1): ?>',
            $template
        );
    }

    protected function compileEndForeach($template)
    {
        return str_replace(
            '@endforeach',
            '<?php endforeach; ?>',
            $template
        );
    }

    /**
     * Compile @include().
     */
    protected function compileInclude($template)
    {
        return preg_replace_callback(
            '/@include\((.+?)\)/',
            function ($matches) {

                $view = trim($matches[1], " '\"");

                $view = str_replace('.', '/', $view);

                /*
                 * Use a new compiler instance so
                 * the parent compiler's layout
                 * is not reset.
                 */
                $compiler = new TemplateCompiler();

                $compiler->compile($view);

                return "<?php require '../app/Cache/{$view}.php'; ?>";
            },
            $template
        );
    }

    /**
     * Compile @section().
     */
    protected function compileSection($template)
    {
        return preg_replace_callback(
            '/@section\((.+?)\)/',
            function ($matches) {

                $section = trim($matches[1], " '\"");

                return "<?php \$this->startSection('{$section}'); ?>";
            },
            $template
        );
    }

    /**
     * Compile @endsection.
     */
    protected function compileEndSection($template)
    {
        return str_replace(
            '@endsection',
            '<?php $this->endSection(); ?>',
            $template
        );
    }

    /**
     * Compile @yield().
     */
    protected function compileYield($template)
    {
        return preg_replace_callback(
            '/@yield\((.+?)\)/',
            function ($matches) {

                $section = trim($matches[1], " '\"");

                return "<?php echo \$this->yieldSection('{$section}'); ?>";
            },
            $template
        );
    }

    /**
     * Compile @extends().
     */
    protected function compileExtends($template)
    {
        return preg_replace_callback(
            '/@extends\((.+?)\)/',
            function ($matches) {

                $layout = trim($matches[1], " '\"");

                $this->layout = str_replace('.', '/', $layout);

                return '';
            },
            $template
        );
    }
}