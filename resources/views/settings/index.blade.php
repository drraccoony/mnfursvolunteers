<x-app-layout>
    @section('title', 'Application Settings')
    <x-slot name="header">
        {{ __('Application Settings') }}
    </x-slot>

    <!-- Hidden forms for reset actions (outside main form to avoid nesting) -->
    <form id="reset-logo-form" action="{{ route('settings.reset-logo') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="reset-favicon-form" action="{{ route('settings.reset-favicon') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" x-data="settingsForm()">
                    @csrf
                    @method('PUT')

                    <!-- Branding Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Branding</h3>
                        
                        <!-- Application Name -->
                        <div class="mb-6">
                            <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Application Name
                            </label>
                            <input type="text" name="app_name" id="app_name"
                                value="{{ old('app_name', app_setting('app_name', config('app.name'))) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="MNFursVolunteers">
                            @error('app_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tagline -->
                        <div class="mb-6">
                            <label for="app_tagline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tagline
                            </label>
                            <input type="text" name="app_tagline" id="app_tagline"
                                value="{{ old('app_tagline', app_setting('app_tagline')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Your organization's tagline">
                            @error('app_tagline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="app_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea name="app_description" id="app_description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Brief description of your organization">{{ old('app_description', app_setting('app_description')) }}</textarea>
                            @error('app_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo Upload -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Logo
                            </label>
                            
                            <div class="mb-3 flex items-center gap-4">
                                <img src="{{ app_logo() }}" alt="Current Logo" class="h-16 w-auto border border-gray-200 dark:border-gray-700 rounded p-2 bg-white dark:bg-gray-900">
                                @if(app_setting('app_logo'))
                                    <button type="button" onclick="if(confirm('Reset logo to default?')) document.getElementById('reset-logo-form').submit();" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Reset to default
                                    </button>
                                @endif
                            </div>

                            <input type="file" name="app_logo" id="app_logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                                @change="previewImage($event, 'logoPreview')"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-green file:text-white hover:file:bg-brand-green-dark">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, or SVG. Max 2MB.</p>
                            
                            <div x-show="logoPreview" class="mt-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">New Logo Preview:</p>
                                <img x-bind:src="logoPreview" alt="Logo Preview" class="h-16 w-auto border border-gray-300 rounded p-2 bg-white dark:bg-gray-900">
                            </div>

                            @error('app_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Favicon Upload -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Favicon
                            </label>
                            
                            <div class="mb-3 flex items-center gap-4">
                                <img src="{{ app_favicon() }}" alt="Current Favicon" class="h-8 w-8 border border-gray-200 dark:border-gray-700 rounded p-1 bg-white dark:bg-gray-900">
                                @if(app_setting('app_favicon'))
                                    <button type="button" onclick="if(confirm('Reset favicon to default?')) document.getElementById('reset-favicon-form').submit();" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Reset to default
                                    </button>
                                @endif
                            </div>

                            <input type="file" name="app_favicon" id="app_favicon" accept="image/x-icon,image/png"
                                @change="previewImage($event, 'faviconPreview')"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-green file:text-white hover:file:bg-brand-green-dark">
                            <p class="mt-1 text-xs text-gray-500">ICO, PNG. Max 512KB. Recommended: 32x32px</p>
                            
                            <div x-show="faviconPreview" class="mt-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">New Favicon Preview:</p>
                                <img x-bind:src="faviconPreview" alt="Favicon Preview" class="h-8 w-8 border border-gray-300 rounded p-1 bg-white dark:bg-gray-900">
                            </div>

                            @error('app_favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>                        <!-- Colors -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Primary Color
                                </label>
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="color" name="primary_color" id="primary_color"
                                        value="{{ old('primary_color', app_setting('primary_color', '#10b981')) }}"
                                        class="h-10 w-20 rounded border-gray-300 dark:border-gray-600">
                                    <input type="text" x-model="$el.previousElementSibling.value"
                                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="#10b981">
                                </div>
                                @error('primary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="secondary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Secondary Color
                                </label>
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="color" name="secondary_color" id="secondary_color"
                                        value="{{ old('secondary_color', app_setting('secondary_color', '#3b82f6')) }}"
                                        class="h-10 w-20 rounded border-gray-300 dark:border-gray-600">
                                    <input type="text" x-model="$el.previousElementSibling.value"
                                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="#3b82f6">
                                </div>
                                @error('secondary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Feature Toggles Section -->
                    <div class="mb-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Feature Toggles</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Enable or disable specific features of the application.</p>

                        <div class="space-y-4">
                            <!-- Elections -->
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex-1">
                                    <label for="feature_elections" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Elections
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Board elections and voting system</p>
                                </div>
                                <input type="checkbox" name="feature_elections" id="feature_elections" value="1"
                                    {{ old('feature_elections', app_setting('feature_elections', true)) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                            </div>

                            <!-- Job Listings -->
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex-1">
                                    <label for="feature_job_listings" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Job Listings
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Job postings and applications</p>
                                </div>
                                <input type="checkbox" name="feature_job_listings" id="feature_job_listings" value="1"
                                    {{ old('feature_job_listings', app_setting('feature_job_listings', true)) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                            </div>

                            <!-- One-Off Events -->
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex-1">
                                    <label for="feature_one_off_events" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        One-Off Events
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Simple event check-ins without shifts</p>
                                </div>
                                <input type="checkbox" name="feature_one_off_events" id="feature_one_off_events" value="1"
                                    {{ old('feature_one_off_events', app_setting('feature_one_off_events', true)) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                            </div>

                            <!-- Volunteer Events -->
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex-1">
                                    <label for="feature_volunteer_events" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Volunteer Events
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Full event management with shifts and signups</p>
                                </div>
                                <input type="checkbox" name="feature_volunteer_events" id="feature_volunteer_events" value="1"
                                    {{ old('feature_volunteer_events', app_setting('feature_volunteer_events', true)) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="mb-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Contact Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contact Email -->
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Contact Email
                                </label>
                                <input type="email" name="contact_email" id="contact_email"
                                    value="{{ old('contact_email', app_setting('contact_email')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="contact@example.com">
                                @error('contact_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Contact Phone
                                </label>
                                <input type="text" name="contact_phone" id="contact_phone"
                                    value="{{ old('contact_phone', app_setting('contact_phone')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="(555) 123-4567">
                                @error('contact_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <button type="submit" class="rounded-md bg-brand-green px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-green-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-green">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function settingsForm() {
            return {
                logoPreview: null,
                faviconPreview: null,
                
                previewImage(event, targetProperty) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this[targetProperty] = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
