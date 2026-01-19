<?php

namespace App\Services\DocumentGeneration;

use App\Models\Template;
use App\Models\TemplateSection;

class DocxRenderer implements RendererInterface
{

    public function render(Template $template, $model): string
    {
        // TODO: Implement render() method.
    }

    public function renderSection(TemplateSection $section, $data): mixed
    {
        // TODO: Implement renderSection() method.
    }
}