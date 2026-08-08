<?php

class FileGenerator
{
    /**
     * Generate a file from a stub.
     */
    public function generate(
        string $stub,
        string $className,
        string $destination,
        ?string $fileName = null,
        array $replacements = []
    )
    {
        $stubPath = __DIR__ . "/../../stubs/" . $stub;

        if (!file_exists($stubPath)) {
            throw new Exception("Stub not found: {$stub}");
        }

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        // Default filename = ClassName.php
        if ($fileName === null) {
            $fileName = $className;
        }

        $file = $destination . $fileName . ".php";

        if (file_exists($file)) {
            throw new Exception("File already exists.");
        }

        $contents = file_get_contents($stubPath);

        // Default replacement
        $contents = str_replace(
            "{{class}}",
            $className,
            $contents
        );

        // Additional replacements
        foreach ($replacements as $search => $replace) {
            $contents = str_replace(
                "{{{$search}}}",
                $replace,
                $contents
            );
        }

        file_put_contents($file, $contents);

        echo "Created: {$file}" . PHP_EOL;
    }
}