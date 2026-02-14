<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="{{ public_path('documents/templates/template_1.css') }}" type="text/css">
    </head>
    <body class="page">
        @php
            $src = ' ';
            if(is_file('storage/customers-profile-photos/' . $subject->profile_photo_url)){
                $path = public_path('storage/customers-profile-photos/' . $subject->profile_photo_url);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $base64 = base64_encode(file_get_contents($path));
                $src = "data:image/{$type};base64,{$base64}";
            }else{
                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($subject->full_name) . '&color=5E81F4&background=5E81F440';
                try {
                    $imageData = file_get_contents($avatarUrl);
                    if ($imageData) {
                        $base64 = base64_encode($imageData);
                        $src = "data:image/png;base64,{$base64}";
                    }
                } catch (Exception $e) {
                    $src = '';
                }
            }
        @endphp

        <div class="sheet">
            <header class="header">
                <img src="{{ $src }}" class="avatar" alt="customer profile image">
                <div class="identity">
                    <h1>{{ $subject->full_name }}</h1>
                    <p class="subtitle">{{ $subject->email }}</p>
                    <div class="identity-meta">
                        <span>Gender: {{ $subject->gender }}</span>
                        <span>Date of Birthday: {{ date_format($subject->dob, 'd-m-Y') }}</span>
                        <span>Nationality: {{ $subject->nationality }}</span>
                    </div>
                </div>
            </header>

            <div class="layout">
                <aside class="sidebar">
                    <section class="section">
                        <h2 class="section-title">Contact</h2>
                        <div class="info-row">
                            <span class="label">Email</span>
                            <span class="value">{{ $subject->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone</span>
                            <span class="value">{{ $subject->phone ?? '—' }}</span>
                        </div>
                    </section>

                    <section class="section">
                        <h2 class="section-title">Personal Details</h2>
                        <div class="info-row">
                            <span class="label">Gender</span>
                            <span class="value">{{ $subject->gender }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Date of birth</span>
                            <span class="value">{{ date_format($subject->dob, 'd-m-Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Nationality</span>
                            <span class="value">{{ $subject->nationality }}</span>
                        </div>
                    </section>

                    <section class="section">
                        <h2 class="section-title">Attributes</h2>
                        @if(!empty($attributes))
                            <ul class="pill-list">
                                @foreach($attributes as $attribute)
                                    <li class="pill">
                                        <strong>{{ $attribute['name'] }}</strong>
                                        <span>{{ $attribute['value'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="muted">No attributes added.</p>
                        @endif
                    </section>
                </aside>

                <main class="main">
                    <section class="section">
                        <h2 class="section-title">Professional Summary</h2>
                        <div class="summary">
                            {{ $summary }}
                        </div>
                    </section>

                    <section class="section">
                        @if(!empty($skills))
                            <h2 class="section-title">Skills</h2>
                            <ul class="document-skill-list">
                                @foreach($skills as $skill)
                                    <li class="document-skill-card">
                                        <div class="document-skill-header">
                                            <span class="document-skill-name">{{ $skill['name'] }}</span>
                                            <span class="document-skill-years">{{ $skill['years'] }} yrs</span>
                                        </div>
                                        <p class="document-skill-desc">{{ $skill['description'] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h2 class="section-title">Skills</h2>
                            <p class="muted">No skills added.</p>
                        @endif
                    </section>
                </main>
            </div>
        </div>
    </body>
</html>
