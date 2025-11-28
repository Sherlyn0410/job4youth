<x-employer-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ isset($job) ? 'Edit Job' : 'Post a Job' }}
                    </h1>
                    @if(isset($job))
                    <a href="{{ route('employer.jobs.manage') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Back to Jobs
                    </a>
                    @endif
                </div>

                <form method="POST" 
                      action="{{ isset($job) ? route('employer.jobs.update', $job->id) : route('employer.jobs.store') }}" 
                      class="px-6 pb-6 space-y-6"
                      x-data="{ 
                          isSubmitting: false,
                          // Pre-fill skills for editing
                          softSkills: {{ isset($job) && $job->soft_skills ? json_encode($job->soft_skills) : '[]' }},
                          hardSkills: {{ isset($job) && $job->hard_skills ? json_encode($job->hard_skills) : '[]' }}
                      }"
                      @submit="isSubmitting = true">
                    @csrf
                    @if(isset($job))
                        @method('PUT')
                    @endif

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Job Title and Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Job Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Job Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                id="title" 
                                name="title" 
                                required
                                value="{{ old('title', isset($job) ? $job->title : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500"
                                placeholder="Add job title, role, vacancies etc">
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <select id="location" 
                                    name="location" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                                <option value="">Select location</option>
                                @php
                                $locations = ['Kuala Lumpur', 'Selangor', 'Penang', 'Johor', 'Perak', 'Sabah', 'Sarawak', 'Negeri Sembilan', 'Pahang', 'Kelantan', 'Terengganu', 'Melaka', 'Perlis', 'Putrajaya', 'Labuan', 'Other'];
                                $currentLocation = old('location', isset($job) ? $job->location : '');
                                @endphp
                                @foreach($locations as $location)
                                <option value="{{ $location }}" {{ $currentLocation == $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Job Details Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Job Type -->
                            <div>
                                <label for="job_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Job Type <span class="text-red-500">*</span>
                                </label>
                                <select id="job_type" 
                                        name="job_type" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                                    <option value="">Select job type</option>
                                    @php
                                    $jobTypes = ['full-time' => 'Full-time', 'part-time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship', 'temporary' => 'Temporary'];
                                    $currentJobType = old('job_type', isset($job) ? $job->job_type : '');
                                    @endphp
                                    @foreach($jobTypes as $value => $label)
                                    <option value="{{ $value }}" {{ $currentJobType == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Specialization -->
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">
                                    Specialization <span class="text-red-500">*</span>
                                </label>
                                <select id="specialization" 
                                        name="specialization" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                                    <option value="">Select specialization</option>
                                    @php
                                    $specializations = ['technology' => 'Technology', 'marketing' => 'Marketing', 'finance' => 'Finance', 'healthcare' => 'Healthcare', 'education' => 'Education', 'sales' => 'Sales', 'design' => 'Design', 'other' => 'Other'];
                                    $currentSpecialization = old('specialization', isset($job) ? $job->specialization : '');
                                    @endphp
                                    @foreach($specializations as $value => $label)
                                    <option value="{{ $value }}" {{ $currentSpecialization == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Education Level -->
                            <div>
                                <label for="education_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Education Level <span class="text-red-500">*</span>
                                </label>
                                <select id="education_level" 
                                        name="education_level" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                                    <option value="">Select education level</option>
                                    @php
                                    $educationLevels = ['high-school' => 'High School', 'diploma' => 'Diploma', 'bachelor' => 'Bachelor\'s Degree', 'master' => 'Master\'s Degree', 'phd' => 'PhD'];
                                    $currentEducation = old('education_level', isset($job) ? $job->education_level : '');
                                    @endphp
                                    @foreach($educationLevels as $value => $label)
                                    <option value="{{ $value }}" {{ $currentEducation == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Salary Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Salary Range
                                </label>
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        <span class="text-gray-500 text-sm mr-2">RM</span>
                                        <input type="number" 
                                            name="salary_min" 
                                            value="{{ old('salary_min', isset($job) ? $job->salary_min : '') }}"
                                            class="w-50 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500"
                                            placeholder="Minimum">
                                    </div>
                                    <span class="text-gray-400">to</span>
                                    <div class="flex items-center">
                                        <span class="text-gray-500 text-sm mr-2">RM</span>
                                        <input type="number" 
                                            name="salary_max" 
                                            value="{{ old('salary_max', isset($job) ? $job->salary_max : '') }}"
                                            class="w-50 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500"
                                            placeholder="Maximum">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                            name="salary_display" 
                                            value="1"
                                            {{ old('salary_display', isset($job) ? $job->salary_display : false) ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-sm">
                                        <span class="ml-2 text-sm text-gray-600">Display salary range</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Overview with Word Count -->
                    <div>
                        <label for="job_overview" class="block text-sm font-medium text-gray-700 mb-2">
                            Job Overview <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ 
                            content: `{{ old('job_overview', isset($job) ? str_replace('`', '\`', $job->job_overview) : '') }}`,
                            wordLimit: 500,
                            get wordCount() {
                                return this.content.trim() === '' ? 0 : this.content.trim().split(/\s+/).length;
                            },
                            get isOverLimit() {
                                return this.wordCount > this.wordLimit;
                            }
                        }" class="relative">
                            <textarea id="job_overview" 
                                    name="job_overview" 
                                    required
                                    rows="6"
                                    x-model="content"
                                    :class="isOverLimit ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'"
                                    class="w-full px-3 py-3 border rounded-lg bg-white text-gray-900 placeholder-gray-500 resize-vertical"
                                    placeholder="Add your job description..."></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">Describe the role and company culture</p>
                                <span :class="isOverLimit ? 'text-red-600' : wordCount > wordLimit * 0.9 ? 'text-yellow-600' : 'text-gray-500'" 
                                      class="text-sm font-medium">
                                    <span x-text="wordCount"></span>/<span x-text="wordLimit"></span> words
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Responsibilities with Word Count -->
                    <div>
                        <label for="responsibilities" class="block text-sm font-medium text-gray-700 mb-2">
                            Responsibilities <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ 
                            content: `{{ old('responsibilities', isset($job) ? str_replace('`', '\`', $job->responsibilities) : '') }}`,
                            wordLimit: 750,
                            get wordCount() {
                                return this.content.trim() === '' ? 0 : this.content.trim().split(/\s+/).length;
                            },
                            get isOverLimit() {
                                return this.wordCount > this.wordLimit;
                            }
                        }" class="relative">
                            <textarea id="responsibilities" 
                                    name="responsibilities" 
                                    required
                                    rows="6"
                                    x-model="content"
                                    :class="isOverLimit ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'"
                                    class="w-full px-3 py-3 border rounded-lg bg-white text-gray-900 placeholder-gray-500 resize-vertical"
                                    placeholder="Add your job responsibilities..."></textarea>
                            <div class="items-center mt-2">
                                <span :class="isOverLimit ? 'text-red-600' : wordCount > wordLimit * 0.9 ? 'text-yellow-600' : 'text-gray-500'" 
                                      class="text-sm font-medium">
                                    <span x-text="wordCount"></span>/<span x-text="wordLimit"></span> words
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Requirements with Word Count -->
                    <div>
                        <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                            Requirements <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ 
                            content: `{{ old('requirements', isset($job) ? str_replace('`', '\`', $job->requirements) : '') }}`,
                            wordLimit: 500,
                            get wordCount() {
                                return this.content.trim() === '' ? 0 : this.content.trim().split(/\s+/).length;
                            },
                            get isOverLimit() {
                                return this.wordCount > this.wordLimit;
                            }
                        }" class="relative">
                            <textarea id="requirements" 
                                    name="requirements" 
                                    required
                                    rows="6"
                                    x-model="content"
                                    :class="isOverLimit ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'"
                                    class="w-full px-3 py-3 border rounded-lg bg-white text-gray-900 placeholder-gray-500 resize-vertical"
                                    placeholder="Add your job requirements..."></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">Specify qualifications and experience needed</p>
                                <span :class="isOverLimit ? 'text-red-600' : wordCount > wordLimit * 0.9 ? 'text-yellow-600' : 'text-gray-500'" 
                                      class="text-sm font-medium">
                                    <span x-text="wordCount"></span>/<span x-text="wordLimit"></span> words
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Soft Skills -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Soft Skills <span class="text-red-500">*</span>
                            </label>
                            <p class="text-sm text-gray-600 mb-3">Select at least 1 soft skill (max 10)</p>
                            
                            <div x-data="{ 
                                skills: softSkills,
                                newSkill: '',
                                maxSkills: 10,
                                addSkill() {
                                    if (this.newSkill.trim() && this.skills.length < this.maxSkills && !this.skills.includes(this.newSkill.trim())) {
                                        this.skills.push(this.newSkill.trim());
                                        this.newSkill = '';
                                    }
                                },
                                removeSkill(index) {
                                    this.skills.splice(index, 1);
                                }
                            }">
                                <!-- Skills Input -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <input type="text" 
                                        x-model="newSkill"
                                        @keydown.enter.prevent="addSkill()"
                                        :disabled="skills.length >= maxSkills"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500 disabled:bg-gray-100"
                                        placeholder="e.g., Communication, Leadership">
                                    <button type="button" 
                                            @click="addSkill()"
                                            :disabled="skills.length >= maxSkills"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>

                                <!-- Skills Display -->
                                <div class="flex flex-wrap gap-2 mb-2" x-show="skills.length > 0">
                                    <template x-for="(skill, index) in skills" :key="index">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <span x-text="skill"></span>
                                            <button type="button" 
                                                    @click="removeSkill(index)"
                                                    class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full text-green-600 hover:bg-green-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </div>

                                <div class="text-sm text-gray-500">
                                    <span x-text="skills.length"></span>/<span x-text="maxSkills"></span> skills added
                                </div>

                                <!-- Hidden input to submit skills -->
                                <input type="hidden" name="soft_skills" :value="JSON.stringify(skills)">
                            </div>
                        </div>

                        <!-- Hard Skills -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hard Skills <span class="text-red-500">*</span>
                            </label>
                            <p class="text-sm text-gray-600 mb-3">Select at least 1 hard skill (max 10)</p>
                            
                            <div x-data="{ 
                                skills: hardSkills,
                                newSkill: '',
                                maxSkills: 10,
                                addSkill() {
                                    if (this.newSkill.trim() && this.skills.length < this.maxSkills && !this.skills.includes(this.newSkill.trim())) {
                                        this.skills.push(this.newSkill.trim());
                                        this.newSkill = '';
                                    }
                                },
                                removeSkill(index) {
                                    this.skills.splice(index, 1);
                                }
                            }">
                                <!-- Skills Input -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <input type="text" 
                                        x-model="newSkill"
                                        @keydown.enter.prevent="addSkill()"
                                        :disabled="skills.length >= maxSkills"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500 disabled:bg-gray-100"
                                        placeholder="e.g., Python, Adobe Photoshop">
                                    <button type="button" 
                                            @click="addSkill()"
                                            :disabled="skills.length >= maxSkills"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>

                                <!-- Skills Display -->
                                <div class="flex flex-wrap gap-2 mb-2" x-show="skills.length > 0">
                                    <template x-for="(skill, index) in skills" :key="index">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <span x-text="skill"></span>
                                            <button type="button" 
                                                    @click="removeSkill(index)"
                                                    class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full text-blue-600 hover:bg-blue-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </div>

                                <div class="text-sm text-gray-500">
                                    <span x-text="skills.length"></span>/<span x-text="maxSkills"></span> skills added
                                </div>

                                <!-- Hidden input to submit skills -->
                                <input type="hidden" name="hard_skills" :value="JSON.stringify(skills)">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('employer.jobs.manage') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        
                        <button type="submit" 
                                :disabled="isSubmitting"
                                :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-base font-medium transition-colors flex items-center gap-2">
                            <span x-show="!isSubmitting">
                                {{ isset($job) ? 'Save Changes' : 'Post Job' }}
                            </span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isset($job) ? 'Saving Changes...' : 'Posting Job...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-employer-layout>