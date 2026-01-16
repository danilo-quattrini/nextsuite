<?php

namespace App\Services\DocumentGeneration;

use App\Models\Template;
use App\Models\TemplateSection;

interface RendererInterface
{
    public function render(Template $template, $model): string;
    public function renderSection(TemplateSection $section, $data): mixed;
}