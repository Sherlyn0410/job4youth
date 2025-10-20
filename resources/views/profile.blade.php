<x-public-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-4 h-screen" style="height: calc(100vh - 130px);">
                
                <!-- Left Sidebar - Profile Header (Sticky) -->
                <div class="w-1/3 shrink-0">
                    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden sticky top-8">
                        <div class="p-6">
                            <!-- Profile Image with Upload -->
                            <div class="flex justify-center mb-6" x-data="{ showUpload: false }">
                                <div class="relative">
                                    <div class="w-24 h-24 bg-linear-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center overflow-hidden">
                                        @if(auth()->user()->profile_picture)
                                            <img src="{{ auth()->user()->profile_picture_url }}" 
                                                 alt="{{ auth()->user()->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-20 h-20 rounded-full overflow-hidden">
                                                <svg viewBox="0 0 100 100" class="w-full h-full">
                                                    <!-- Hair -->
                                                    <path d="M25 35 Q50 60 75 35 Q75 25 50 25 Q25 25 25 35" fill="#8B4513"/>
                                                    <!-- Face -->
                                                    <circle cx="50" cy="45" r="15" fill="#FDBCB4"/>
                                                    <!-- Eyes -->
                                                    <circle cx="44" cy="42" r="2" fill="#000"/>
                                                    <circle cx="56" cy="42" r="2" fill="#000"/>
                                                    <!-- Nose -->
                                                    <ellipse cx="50" cy="47" rx="1" ry="2" fill="#F4A460"/>
                                                    <!-- Mouth -->
                                                    <path d="M46 52 Q50 55 54 52" stroke="#000" stroke-width="1" fill="none"/>
                                                    <!-- Body -->
                                                    <rect x="42" y="60" width="16" height="25" fill="#4F46E5" rx="3"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Upload Button Overlay -->
                                    <button @click="showUpload = true"
                                            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 hover:opacity-100 transition-opacity duration-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Profile Picture Upload Modal -->
                                <div x-show="showUpload" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold">Update Profile Picture</h3>
                                            <button @click="showUpload = false" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <!-- File Upload Area -->
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-4">
                                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-2">Drag your photo to start uploading</p>
                                                <p class="text-sm text-gray-400 mb-4">OR</p>
                                                <input type="file" name="profile_picture" accept="image/*" class="hidden" id="profile-picture-input">
                                                <label for="profile-picture-input" class="cursor-pointer px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                                                    Browse files
                                                </label>
                                            </div>

                                            <p class="text-xs text-gray-500 mb-4">Accepted file types: JPG, PNG, GIF (Max 2MB)</p>

                                            <!-- Action Buttons -->
                                            <div class="flex gap-3">
                                                <button type="button" @click="showUpload = false" 
                                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                    Upload
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- User Name -->
                            <h1 class="text-center text-xl font-bold text-gray-900 mb-6">{{ auth()->user()->name ?? 'Jenny Tan' }}</h1>
                            
                            <!-- Contact Information -->
                            <div class="space-y-3 text-sm text-gray-600 mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <span>{{ auth()->user()->phone ?? '+60173492734' }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span>{{ auth()->user()->email ?? 'jennytan03@gmail.com' }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span>{{ auth()->user()->location ?? 'Georgetown, Penang' }}</span>
                                </div>
                            </div>

                            <!-- About Me Section -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">About Me</h3>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    {{ auth()->user()->bio ?? 'A recent Computer Science graduate with a strong interest in UI/UX design, web development, and digital solutions. Eager to apply academic knowledge and internship experience in a real-world setting. Passionate about learning new skills, contributing to meaningful projects, and growing in a dynamic work environment.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Scrollable Sections -->
                <div class="flex-1 overflow-y-auto pr-2" style="max-height: calc(100vh - 120px);">
                    <div class="space-y-6">
                        
                        <!-- Resume Section -->
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden" 
                             x-data="{ 
                                 showAddResume: false,
                                 resumes: [
                                     {
                                         id: 1,
                                         name: 'JennyTan_Resume.pdf',
                                         size: '2.3MB',
                                         type: 'pdf',
                                         isDefault: true,
                                         isVisible: true
                                     }
                                 ]
                             }">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-xl font-bold text-gray-900">Resume</h2>
                                    <span class="text-sm text-gray-500">Supported file type: pdf, doc, docx</span>
                                </div>

                                <!-- Resume List -->
                                <div class="space-y-4">
                                    <template x-for="resume in resumes" :key="resume.id">
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-900" x-text="resume.name"></span>
                                                        <span x-show="resume.isDefault" class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-sm">Default</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600" x-show="resume.isVisible">This resume is visible to employers.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button class="p-2 text-gray-400 hover:text-gray-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Add Resume Button -->
                                <button @click="showAddResume = true" 
                                        class="mt-4 px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Add resume
                                </button>

                                <!-- Add Resume Modal -->
                                <div x-show="showAddResume" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold">Add Resume</h3>
                                            <button @click="showAddResume = false" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-4">Add up to 5 resumes. Accepted file types: pdf, doc, docx</p>
                                        
                                        <!-- File Upload Area -->
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-4">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">Drag your file(s) to start uploading</p>
                                            <p class="text-sm text-gray-400 mb-4">OR</p>
                                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                                                Browse files
                                            </button>
                                        </div>

                                        <!-- Make Default Checkbox -->
                                        <label class="flex items-center mb-6">
                                            <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded-sm">
                                            <span class="ml-2 text-sm text-gray-700">Make this my default resume</span>
                                        </label>

                                        <!-- Action Buttons -->
                                        <div class="flex gap-3">
                                            <button @click="showAddResume = false" 
                                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                                Cancel
                                            </button>
                                            <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Experience Section -->
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-xl font-bold text-gray-900">Work Experience</h2>
                                </div>
                                <p class="text-gray-600 text-sm mb-4">Showcase your work experience. Highlight your roles, responsibilities, and what you achieved in each position.</p>
                                
                                <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Add work experience
                                </button>
                            </div>
                        </div>

                        <!-- Education Section -->
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-6">Education</h2>
                                
                                <!-- Education Item -->
                                <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                    <h3 class="font-semibold text-gray-900 mb-1">Diploma of Computer Science</h3>
                                    <p class="text-gray-600 mb-2">INTI International College Penang</p>
                                    <p class="text-sm text-gray-500 mb-2">Graduated in 2024</p>
                                    <p class="text-sm font-medium text-gray-900 mb-2">CGPA: 3.52 / 4.00</p>
                                    <p class="text-sm text-gray-700">Completed a comprehensive program covering programming, data structures, databases, and web development. Gained hands-on experience through coursework, projects, and industry-relevant training.</p>
                                </div>

                                <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Add education
                                </button>
                            </div>
                        </div>

                        <!-- Skills Section -->
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Skills</h2>
                                <p class="text-gray-600 text-sm mb-4">Showcase your relevant skills, both technical and soft skills to attract employer.</p>
                                
                                <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Add skills
                                </button>
                            </div>
                        </div>

                        <!-- Licences & Certifications Section -->
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Licences & certifications</h2>
                                <p class="text-gray-600 text-sm mb-4">Showcase your professional credentials. Add your relevant licences, certificates, memberships and accreditations here.</p>
                                
                                <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Add licence or certification
                                </button>
                            </div>
                        </div>

                        <!-- Language Section -->
                        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Language</h2>
                                <p class="text-gray-600 text-sm mb-4">Add languages to appeal to more companies and employers.</p>
                                
                                <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Add language
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>