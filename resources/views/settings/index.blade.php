@extends('layouts.teacher')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Settings & Preferences</h1>
            <small class="text-muted">Manage your account and application preferences</small>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Account Settings -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i> Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                    <p class="mb-0"><strong>Role:</strong> <span
                            class="badge bg-primary">{{ ucfirst($user->role) }}</span></p>
                </div>
            </div>
            <!-- Grading Scheme -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Grading Scheme
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><strong>Default Grading Scheme</strong></label>
                            <select name="grading_scheme" class="form-select mb-2" id="settingsSchemeSelect">
                                <option value="">-- Use system default --</option>
                                @foreach(($schemes ?? []) as $key => $s)
                                    <option value="{{ $key }}" {{ (isset($userScheme) && $userScheme === $key) ? 'selected' : '' }}>{{ $s['label'] ?? $key }}</option>
                                @endforeach
                                <option value="custom" {{ (isset($userScheme) && $userScheme === 'custom') ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <div id="settingsCustomWeights" style="display: none;">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <label class="form-label">Knowledge %</label>
                                    <input type="number" name="grading_weights[knowledge]" class="form-control" min="0" max="100" value="{{ $userWeights['knowledge'] ?? 40 }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Skills %</label>
                                    <input type="number" name="grading_weights[skills]" class="form-control" min="0" max="100" value="{{ $userWeights['skills'] ?? 50 }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Attitude %</label>
                                    <input type="number" name="grading_weights[attitude]" class="form-control" min="0" max="100" value="{{ $userWeights['attitude'] ?? 10 }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary">Save Scheme</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Theme Selection -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-palette me-2"></i> Appearance - Theme Selection
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label mb-3"><strong>Select Theme:</strong></label>
                            <div class="row g-3">
                                @foreach ($themes as $themeKey => $themeName)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check theme-card">
                                            <input class="form-check-input" type="radio" name="theme"
                                                id="theme_{{ $themeKey }}" value="{{ $themeKey }}"
                                                {{ $user->theme === $themeKey ? 'checked' : '' }}>
                                            <label class="form-check-label d-block" for="theme_{{ $themeKey }}">
                                                <div class="theme-preview p-3 rounded border"
                                                    id="preview_{{ $themeKey }}">
                                                    <span class="fw-bold">{{ $themeName }}</span>
                                                    <div class="mt-2 d-flex gap-2">
                                                        <div class="theme-color-box bg-primary rounded"
                                                            style="width: 20px; height: 20px;"></div>
                                                        <div class="theme-color-box rounded"
                                                            style="width: 20px; height: 20px; background-color: #6c757d;">
                                                        </div>
                                                        <div class="theme-color-box bg-success rounded"
                                                            style="width: 20px; height: 20px;"></div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Available Themes:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Light:</strong> Clean, bright default theme</li>
                            <li><strong>Dark:</strong> Easy on the eyes, dark theme</li>
                            <li><strong>Ocean Blue:</strong> Professional blue-themed interface</li>
                            <li><strong>Forest Green:</strong> Natural green-themed interface</li>
                            <li><strong>Sunset Orange:</strong> Warm orange-themed interface</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i> Theme Tips
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small mb-3">
                        <strong>Personalize your experience</strong> by selecting your preferred theme. Your choice will be
                        saved and applied across all pages.
                    </p>
                    <ul class="small list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #0d6efd; font-size: 8px;"></i>
                            <strong>Light</strong> - Perfect for daytime use
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #1a1a2e; font-size: 8px;"></i>
                            <strong>Dark</strong> - Reduces eye strain in low light
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #0066cc; font-size: 8px;"></i>
                            <strong>Ocean</strong> - Calming blue palette
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #1b5e20; font-size: 8px;"></i>
                            <strong>Forest</strong> - Natural green tones
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-circle" style="color: #e65100; font-size: 8px;"></i>
                            <strong>Sunset</strong> - Warm, energetic orange
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i> Privacy
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small mb-0">
                        Your theme preference is stored securely in your account. It will be automatically applied when you
                        log in from any device.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .theme-card {
            cursor: pointer;
        }

        .theme-preview {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-check-input:checked~label .theme-preview {
            border-color: #0d6efd !important;
            background-color: #e7f1ff;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .form-check-input {
            margin-top: 0.5rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const select = document.getElementById('settingsSchemeSelect');
            const custom = document.getElementById('settingsCustomWeights');
            function toggle(){
                if(!select) return;
                if(select.value === 'custom') custom.style.display = 'block'; else custom.style.display = 'none';
            }
            if(select){ select.addEventListener('change', toggle); toggle(); }
        });
    </script>
@endsection
