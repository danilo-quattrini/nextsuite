<?php

namespace App\Services\DocumentGeneration;

use App\Models\Template;
use App\Models\TemplateSection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfRenderer implements RendererInterface
{

    public function render(Template $template, $model): string
    {
        $sections = $template->sections;
        $renderedSections = [];

        foreach ($sections as $section) {
            $data = $this->extractData($model, $section);
            $renderedSections[] = $this->renderSection($section, $data);
        }
        // Generate PDF using DomPDF or similar
        $pdf = Pdf::loadView('documents.pdf.base-template', [
            'sections' => $renderedSections,
        ])
            ->setPaper($template->settings['paper_size'] ?? 'a4', $template->settings['orientation'] ?? 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = "documents/{$model->id}_" . time() . ".pdf";

        Storage::put($filename, $pdf->output());

        return $filename;
    }

    public function renderSection(TemplateSection $section, $data): mixed
    {
        // Render based on section type
        return match($section->section_type) {
            'personal_info' => view('documents.pdf.sections.profile-info', [
                    'section' => $section,
                    'data' => $data
                ])->render(),

//            'skills' => view('pdf.sections.skills', [
//                'section' => $section,
//                'skills' => $data,
//                'config' => $section->config
//            ])->render(),
//
//            'custom_text' => view('pdf.sections.custom-text', [
//                'section' => $section,
//                'content' => $data
//            ])->render(),

            default => ''
        };
    }

    private function extractData($model, TemplateSection $section)
    {
        return match($section->section_type) {
            'personal_info' => $this->getPersonalInfo($model, $section->config),
            'skills' => $model->skills,
            'attributes' => $model->attributes,
            'custom_text' => $section->config['content'] ?? '',
            default => null
        };
    }

    private function getPersonalInfo($model, array $config): array
    {
        $data = [];
        foreach ($config['fields'] ?? [] as $field) {
            $data[$field['key']] = $model->{$field['key']} ?? null;
        }
        return $data;
    }
}