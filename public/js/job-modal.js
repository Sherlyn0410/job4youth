class JobModal {
    constructor() {
        this.currentJobId = null;
        this.currentJobData = null; // Add this to store full job data
        this.modal = document.getElementById('job_detail_modal');
        this.loading = document.getElementById('modal-loading');
        this.details = document.getElementById('modal-job-details');
        this.pendingAction = null;
        this.init();
    }

    init() {
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.close();
            }
        });

        // Listen for successful login to execute pending action
        document.addEventListener('login-success', () => {
            this.executePendingAction();
        });

        // Listen for when modals close to potentially reopen job modal
        document.addEventListener('modal-closed', (event) => {
            if (event.detail?.name === 'login' && this.pendingAction && this.currentJobId) {
                // If login modal was closed and user is now authenticated, reopen job modal
                if (this.isAuthenticated()) {
                    setTimeout(() => {
                        this.show(this.currentJobId);
                    }, 100);
                }
            }
        });
    }

    // Check if user is authenticated
    isAuthenticated() {
        // Check if user is logged in by looking for auth indicators
        return document.querySelector('meta[name="user-authenticated"]')?.content === 'true' ||
               document.body.classList.contains('authenticated') ||
               (window.Laravel && window.Laravel.user);
    }

    // Show login modal and set pending action
    requireLogin(action) {
        this.pendingAction = action;
        
        // Close job modal first
        this.close();
        
        // Small delay to ensure job modal is closed before opening login modal
        setTimeout(() => {
            // Use Alpine.js to trigger the login modal
            // Method 1: Direct Alpine dispatch
            if (window.Alpine) {
                window.Alpine.store('modals', window.Alpine.store('modals') || {});
                document.dispatchEvent(new CustomEvent('open-modal', { 
                    detail: 'login'
                }));
            }
            
            // Method 2: Try to find and click the login trigger button
            const loginTrigger = document.querySelector('[x-on\\:click*="open-modal"][x-on\\:click*="login"]') ||
                               document.querySelector('button[onclick*="login"]') ||
                               document.querySelector('[data-modal="login"]');
            
            if (loginTrigger) {
                loginTrigger.click();
            }
            
            // Method 3: Manually dispatch the event that your modal listens for
            window.dispatchEvent(new CustomEvent('open-modal', { 
                detail: 'login' 
            }));
            
            // Store the action and job ID in sessionStorage for persistence
            sessionStorage.setItem('pendingJobAction', JSON.stringify({
                action: action,
                jobId: this.currentJobId
            }));
            
            // Debug log
            console.log('Attempting to open login modal for action:', action);
        }, 100);
    }

    // Execute the action user wanted to do after login
    executePendingAction() {
        // First check for pending action in memory
        if (this.pendingAction && this.currentJobId) {
            if (this.pendingAction === 'save') {
                this.performSaveJob();
            } else if (this.pendingAction === 'apply') {
                this.performApplyForJob();
            }
            this.pendingAction = null;
            return;
        }

        // Check sessionStorage for persistent pending action
        const pendingData = sessionStorage.getItem('pendingJobAction');
        if (pendingData) {
            try {
                const { action, jobId } = JSON.parse(pendingData);
                this.currentJobId = jobId;
                
                if (action === 'save') {
                    this.performSaveJob();
                } else if (action === 'apply') {
                    this.performApplyForJob();
                }
                
                // Clear the stored action
                sessionStorage.removeItem('pendingJobAction');
            } catch (e) {
                console.error('Error parsing pending job action:', e);
            }
        }
    }

    async show(jobId) {
        this.currentJobId = jobId;
        
        // Show modal and loading state
        this.modal.showModal();
        this.loading.style.display = 'flex';
        this.details.style.display = 'none';
        
        // Reset all sections
        this.resetSections();
        
        try {
            const response = await fetch(`/jobs/${jobId}/details`);
            const data = await response.json();
            
            if (data.success) {
                this.currentJobData = data.job; // Store the job data
                this.populate(data.job);
                this.loading.style.display = 'none';
                this.details.style.display = 'block';
            } else {
                alert('Error loading job details');
                this.modal.close();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading job details');
            this.modal.close();
        }
    }

    resetSections() {
        // Hide all optional sections
        const sectionsToHide = [
            'modal-responsibilities-section',
            'modal-requirements-section', 
            'modal-skills-section',
            'modal-education-section',
            'modal-specialization-section',
            'modal-location-container'
        ];
        
        sectionsToHide.forEach(id => {
            const element = document.getElementById(id);
            if (element) element.style.display = 'none';
        });
        
        // Clear all content
        const elementsToReset = [
            'modal-job-title', 'modal-company-name', 'modal-location', 'modal-job-type',
            'modal-salary', 'modal-job-overview', 'modal-responsibilities', 'modal-requirements',
            'modal-skills', 'modal-education', 'modal-specialization', 'modal-posted-date'
        ];
        
        elementsToReset.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = '';
                element.innerHTML = '';
            }
        });
    }

    populate(job) {
        // Basic info
        this.setElementText('modal-job-title', job.title || 'Untitled Position');
        this.setElementText('modal-company-name', job.company_name || 'Company');
        
        // Location
        this.handleLocation(job.location);
        
        // Job type
        this.handleJobType(job.job_type);
        
        // Company logo
        this.handleCompanyLogo(job);
        
        // Salary
        this.handleSalary(job);
        
        // Job overview
        this.handleJobOverview(job.job_overview);
        
        // Responsibilities
        this.handleSection('responsibilities', job.responsibilities, true);
        
        // Requirements
        this.handleSection('requirements', job.requirements, true);
        
        // Skills
        this.handleSection('skills', job.skills, false);
        
        // Education Level
        this.handleSection('education', job.education_level, false);
        
        // Specialization
        this.handleSection('specialization', job.specialization, false);
        
        // Posted date
        this.setElementText('modal-posted-date', job.posted_date || 'Recently');
    }

    setElementText(id, text) {
        const element = document.getElementById(id);
        if (element) element.textContent = text;
    }

    handleLocation(location) {
        const locationElement = document.getElementById('modal-location');
        const locationContainer = document.getElementById('modal-location-container');
        
        if (location && location.trim()) {
            locationElement.textContent = location;
            locationContainer.style.display = 'flex';
        }
    }

    handleJobType(jobType) {
        const jobTypeElement = document.getElementById('modal-job-type');
        
        if (jobType && jobType.trim()) {
            jobTypeElement.textContent = jobType;
            jobTypeElement.style.display = 'inline-block';
        } else {
            jobTypeElement.style.display = 'none';
        }
    }

    handleCompanyLogo(job) {
        const logoElement = document.getElementById('modal-company-logo');
        
        if (job.company_logo) {
            logoElement.innerHTML = `<img src="${job.company_logo}" alt="${job.company_name}" class="w-full h-full rounded-xl object-cover">`;
        } else {
            logoElement.textContent = job.company_name ? job.company_name.charAt(0).toUpperCase() : 'C';
        }
    }

    handleSalary(job) {
        const salaryElement = document.getElementById('modal-salary');
        
        if (job.salary_display && (job.salary_min || job.salary_max)) {
            let salaryText = '';
            if (job.salary_min && job.salary_max) {
                salaryText = `RM ${this.formatNumber(job.salary_min)} - RM ${this.formatNumber(job.salary_max)}`;
            } else if (job.salary_max) {
                salaryText = `Up to RM ${this.formatNumber(job.salary_max)}`;
            }
            salaryElement.textContent = salaryText;
            salaryElement.style.display = 'inline';
        } else {
            salaryElement.style.display = 'none';
        }
    }

    handleJobOverview(overview) {
        const overviewElement = document.getElementById('modal-job-overview');
        const overviewSection = document.getElementById('modal-overview-section');
        
        if (overview && overview.trim()) {
            overviewElement.textContent = overview;
            overviewSection.style.display = 'block';
        } else {
            overviewElement.textContent = 'No job overview available.';
            overviewSection.style.display = 'block';
        }
    }

    handleSection(sectionName, content, isList) {
        const section = document.getElementById(`modal-${sectionName}-section`);
        const element = document.getElementById(`modal-${sectionName}`);
        
        if (content && content.trim()) {
            element.innerHTML = isList ? this.formatTextAsList(content) : this.formatText(content);
            section.style.display = 'block';
        }
    }

    formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }

    formatTextAsList(text) {
        if (!text || !text.trim()) return '';
        
        const lines = text.split('\n').filter(line => line.trim() !== '');
        return lines.map(line => {
            const trimmedLine = line.trim();
            const cleanLine = trimmedLine.replace(/^[\-\*\•\d+\.\)\s]+/, '');
            return `<li class="flex items-start gap-3">
                        <span class="text-gray-400 mt-1.5 text-xs">•</span>
                        <span class="flex-1">${cleanLine}</span>
                    </li>`;
        }).join('');
    }

    formatText(text) {
        if (!text || !text.trim()) return '';
        return text.replace(/\n/g, '<br>');
    }

    close() {
        this.modal.close();
        // Don't clear currentJobId here if there's a pending action
        if (!this.pendingAction) {
            this.currentJobId = null;
        }
    }

    saveJob() {
        if (!this.currentJobId) {
            alert('No job selected');
            return;
        }

        // Check if user is authenticated
        if (!this.isAuthenticated()) {
            this.requireLogin('save');
            return;
        }

        this.performSaveJob();
    }

    performSaveJob() {
        console.log('Saving job ID:', this.currentJobId);
        
        // Show immediate feedback
        const saveBtn = document.getElementById('save-job-btn');
        if (saveBtn) {
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
            
            // Make API call to save job
            fetch(`/jobs/${this.currentJobId}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    saveBtn.textContent = 'Saved!';
                    saveBtn.classList.add('bg-green-50', 'text-green-600', 'border-green-600');
                    
                    setTimeout(() => {
                        saveBtn.textContent = originalText;
                        saveBtn.classList.remove('bg-green-50', 'text-green-600', 'border-green-600');
                        saveBtn.disabled = false;
                    }, 2000);
                } else {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                    alert(data.message || 'Error saving job');
                }
            })
            .catch(error => {
                console.error('Error saving job:', error);
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
                alert('Error saving job. Please try again.');
            });
        }
    }

    applyForJob() {
        if (!this.currentJobId) {
            alert('No job selected');
            return;
        }

        // Check if user is authenticated
        if (!this.isAuthenticated()) {
            this.requireLogin('apply');
            return;
        }

        // Show the apply modal instead of redirect
        this.showApplyModal();
    }

    showApplyModal() {
        if (!this.currentJobData) {
            alert('Job data not available');
            return;
        }

        // Set job title in apply modal
        const jobTitleElement = document.getElementById('apply-job-title');
        if (jobTitleElement) {
            jobTitleElement.textContent = this.currentJobData.title;
        }

        // Load user's resumes
        this.loadUserResumes();
        
        // Clear previous data
        const coverLetterTextarea = document.getElementById('cover-letter-textarea');
        const resumeSelect = document.getElementById('resume-select');
        const submitBtn = document.getElementById('submit-application-btn');
        
        if (coverLetterTextarea) coverLetterTextarea.value = '';
        if (resumeSelect) resumeSelect.value = '';
        if (submitBtn) submitBtn.disabled = true;
        
        // Reset character counter
        const counter = document.getElementById('cover-letter-count');
        if (counter) counter.textContent = '0';
        
        // Show apply modal
        const applyModal = document.getElementById('apply_job_modal');
        if (applyModal) {
            applyModal.showModal();
        } else {
            // Fallback to redirect if modal doesn't exist
            window.location.href = `/jobs/${this.currentJobId}/apply`;
        }
    }

    async loadUserResumes() {
        const resumeSelect = document.getElementById('resume-select');
        if (!resumeSelect) return;

        try {
            // Clear existing options except the first one
            resumeSelect.innerHTML = '<option value="">Select...</option>';
            
            // TODO: Replace with actual API call to load user's resumes
            // For now, add some dummy resumes
            const dummyResumes = [
                { id: 1, name: 'Software Developer Resume.pdf' },
                { id: 2, name: 'Marketing Specialist CV.pdf' },
                { id: 3, name: 'General Resume 2024.pdf' }
            ];
            
            dummyResumes.forEach(resume => {
                const option = document.createElement('option');
                option.value = resume.id;
                option.textContent = resume.name;
                resumeSelect.appendChild(option);
            });
            
        } catch (error) {
            console.error('Error loading resumes:', error);
        }
    }

    closeApplyModal() {
        const applyModal = document.getElementById('apply_job_modal');
        if (applyModal) {
            applyModal.close();
        }
    }

    async submitApplication() {
        if (!this.currentJobId) {
            alert('No job selected');
            return;
        }

        const resumeSelect = document.getElementById('resume-select');
        const coverLetterTextarea = document.getElementById('cover-letter-textarea');
        const submitBtn = document.getElementById('submit-application-btn');
        
        if (!resumeSelect?.value) {
            alert('Please select a resume');
            return;
        }
        
        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Applying...';
        }
        
        try {
            const response = await fetch(`/jobs/${this.currentJobId}/apply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    resume_id: resumeSelect.value,
                    cover_letter: coverLetterTextarea?.value || ''
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Close apply modal
                this.closeApplyModal();
                
                // Show success message
                alert(data.message || 'Application submitted successfully!');
                
                // Optionally close job modal after delay
                setTimeout(() => {
                    this.close();
                }, 2000);
            } else {
                alert(data.message || 'Error submitting application');
            }
        } catch (error) {
            console.error('Error applying for job:', error);
            alert('Error submitting application. Please try again.');
        } finally {
            // Reset button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Apply Now';
            }
        }
    }

    // Remove the old performApplyForJob method or update it
    performApplyForJob() {
        console.log('Applying for job ID:', this.currentJobId);
        
        // Check if we have the apply modal
        const applyModal = document.getElementById('apply_job_modal');
        if (applyModal) {
            this.showApplyModal();
        } else {
            // Fallback to old behavior
            if (confirm('Are you sure you want to apply for this job?')) {
                this.close();
                window.location.href = `/jobs/${this.currentJobId}/apply`;
            }
        }
    }
}

// Initialize the modal when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.jobModal = new JobModal();
    
    // Check for pending actions after page load (in case of login redirect)
    if (window.jobModal.isAuthenticated()) {
        window.jobModal.executePendingAction();
    }
});

// Global functions for onclick handlers
function showJobModal(jobId) {
    window.jobModal.show(jobId);
}

function closeJobModal() {
    window.jobModal.close();
}

function saveJob() {
    window.jobModal.saveJob();
}

function applyForJob() {
    window.jobModal.applyForJob();
}

function updateSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

// Add global functions for the apply modal
function closeApplyModal() {
    if (window.jobModal) {
        window.jobModal.closeApplyModal();
    }
}

function submitApplication() {
    if (window.jobModal) {
        window.jobModal.submitApplication();
    }
}