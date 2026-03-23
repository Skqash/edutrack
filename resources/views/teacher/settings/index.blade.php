@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Account Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Appearance Settings -->
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Appearance</h6>
                                
                                <div class="mb-3">
                                    <label for="theme" class="form-label">Theme</label>
                                    <select class="form-select" id="theme" name="theme">
                                        <option value="light" {{ ($teacher->theme ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ ($teacher->theme ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="auto" {{ ($teacher->theme ?? 'light') === 'auto' ? 'selected' : '' }}>Auto (System)</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="en" {{ ($teacher->language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ ($teacher->language ?? 'en') === 'es' ? 'selected' : '' }}>Spanish</option>
                                        <option value="fr" {{ ($teacher->language ?? 'en') === 'fr' ? 'selected' : '' }}>French</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone" name="timezone">
                                        <option value="UTC" {{ ($teacher->timezone ?? 'UTC') === 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="Asia/Manila" {{ ($teacher->timezone ?? 'UTC') === 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila</option>
                                        <option value="America/New_York" {{ ($teacher->timezone ?? 'UTC') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Notification Settings -->
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Notifications</h6>
                                
                                @php
                                    $settings = json_decode($teacher->settings ?? '{}', true);
                                @endphp
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" 
                                           name="email_notifications" value="1" 
                                           {{ ($settings['email_notifications'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        Email Notifications
                                    </label>
                                    <small class="text-muted d-block">Receive notifications via email</small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="grade_reminders" 
                                           name="grade_reminders" value="1" 
                                           {{ ($settings['grade_reminders'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="grade_reminders">
                                        Grade Reminders
                                    </label>
                                    <small class="text-muted d-block">Reminders for pending grade entries</small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="attendance_reminders" 
                                           name="attendance_reminders" value="1" 
                                           {{ ($settings['attendance_reminders'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="attendance_reminders">
                                        Attendance Reminders
                                    </label>
                                    <small class="text-muted d-block">Reminders for attendance recording</small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="system_updates" 
                                           name="system_updates" value="1" 
                                           {{ ($settings['system_updates'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="system_updates">
                                        System Updates
                                    </label>
                                    <small class="text-muted d-block">Notifications about system updates and maintenance</small>
                                </div>
                                
                                @if($teacher->campus)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="campus_announcements" 
                                               name="campus_announcements" value="1" 
                                               {{ ($settings['campus_announcements'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="campus_announcements">
                                            Campus Announcements
                                        </label>
                                        <small class="text-muted d-block">Announcements from your campus administration</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection