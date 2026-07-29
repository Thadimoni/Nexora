<?php

class TemplateEngine
{
    /**
     * The compiler instance.
     */
    protected $compiler;

    /**
     * Stores all captured sections.
     */
    protected $sections = [];

    /**
     * The section currently being captured.
     */
    protected $currentSection = null;

    /**
     * The layout detected by the compiler.
     */
    protected $layout = null;

    /**
     * Create a new Template Engine.
     */
    public function __construct($compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Render a view.
     */
    public function render($view, $data = [])
    {
        // Reset engine state
        $this->resetSections();
        $this->layout = null;

        // Compile the child view
        $compiled = $this->compiler->compile($view);

        // Get layout detected by compiler
        $this->layout = $this->compiler->getLayout();

        // Make variables available
        extract($data);

        // Execute child view
        ob_start();

        require $compiled;

        $content = ob_get_clean();

        // If the child extends a layout,
        // render the layout instead.
        if ($this->layout !== null) {
            return $this->renderLayout($data);
        }

        // Otherwise return child content
        return $content;
    }

    /**
     * Render the parent layout.
     */
    protected function renderLayout($data = [])
    {
        $compiled = $this->compiler->compile($this->layout);

        extract($data);

        ob_start();

        require $compiled;

        return ob_get_clean();
    }

    /**
     * Begin capturing a section.
     */
    public function startSection($name)
    {
        $this->currentSection = $name;

        ob_start();
    }

    /**
     * Finish capturing a section.
     */
    public function endSection()
    {
        $this->sections[$this->currentSection] = ob_get_clean();

        $this->currentSection = null;
    }

    /**
     * Output a section.
     */
    public function yieldSection($name)
    {
        return $this->sections[$name] ?? '';
    }

    /**
     * Determine whether a section exists.
     */
    public function hasSection($name)
    {
        return isset($this->sections[$name]);
    }

    /**
     * Reset all captured sections.
     */
    public function resetSections()
    {
        $this->sections = [];

        $this->currentSection = null;
    }
}