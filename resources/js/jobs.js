class JobsPage {
    constructor() {
        this.currentJobId = null;
        this.currentApplicationId = null;
        this.currentSavedJobId = null;
        this.pageType = 'jobs'; // 'jobs', 'applications', or 'saved-jobs'
        this.applications = []; // Store applications data for application pages
        this.savedJobs = []; // Store saved jobs data for saved jobs pages
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.detectPageType();
        this.loadFirstItem();
        this.checkUrlForJob();
    }

    detectPageType() {
        // Check page type based on available data
        if (window.applicationsPageData) {
            this.pageType = 'applications';
            this.applications = window.applicationsPageData.applications || [];
        } else if (window.savedJobsPageData) {
            this.pageType = 'saved-jobs';
            this.savedJobs = window.savedJobsPageData.savedJobs || [];
        } else {
            this.pageType = 'jobs';
        }
    }

    setupEventListeners() {
        // Load first item on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.loadFirstItem();
        });

        // Handle successful login for pending job actions
        this.handlePostLogin();
    }

    loadFirstItem() {
        if (this.pageType === 'applications') {
            if (this.applications.length > 0) {
                this.loadApplicationDetails(this.applications[0].id);
            }
        } else if (this.pageType === 'saved-jobs') {
            if (this.savedJobs.length > 0) {
                this.loadSavedJobDetails(this.savedJobs[0].id);
            }
        } else {
            // This will be populated by the blade template
            const firstJobId = window.jobsPageData?.firstJobId;
            if (firstJobId) {
                this.loadJobDetails(firstJobId);
            }
        }
    }

    loadSavedJobDetails(savedJobId) {
        if (this.currentSavedJobId === savedJobId) return;
        
        this.currentSavedJobId = savedJobId;
        
        // Update active saved job card styling
        document.querySelectorAll('[data-saved-job-id]').forEach(card => {
            card.classList.remove('border-blue-500', 'shadow-lg');
            card.classList.add('border-gray-200');
            card.style.borderColor = '';
        });
        
        const activeCard = document.querySelector(`[data-saved-job-id="${savedJobId}"]`);
        if (activeCard) {
            activeCard.classList.add('border-blue-500', 'shadow-lg');
            activeCard.classList.remove('border-gray-200');
            activeCard.style.borderColor = '#006EDC';
        }
        
        // Find saved job data
        const savedJob = this.savedJobs.find(item => item.id === savedJobId);
        if (!savedJob) return;
        
        // Show loading state
        const loadingElement = document.getElementById('saved-job-details-loading');
        const contentElement = document.getElementById('saved-job-details-content');
        
        if (loadingElement) loadingElement.style.display = 'none';
        if (contentElement) contentElement.style.display = 'flex';
        
        // Populate details
        this.populateSavedJobDetails(savedJob);
    }

    populateSavedJobDetails(savedJob) {
        const job = savedJob.job;
        
        // Update basic information
        this.updateElement('details-company-logo', job.employer.company_name.charAt(0));
        this.updateElement('details-job-title', job.title);
        this.updateElement('details-company-name', job.employer.company_name);
        this.updateElement('details-location', `<i class="bi bi-geo-alt text-gray-500"></i> ${job.location || 'Not specified'}`, true);
        this.updateElement('details-job-type', job.job_type || 'Not specified');
        
        // Update salary
        const salaryText = job.salary_display && job.salary_min && job.salary_max ? 
            `RM ${job.salary_min.toLocaleString()} - ${job.salary_max.toLocaleString()}` : 'Undisclosed';
        this.updateElement('details-salary', salaryText);
        
        // Update saved date
        this.updateElement('details-saved-date', `Saved ${savedJob.created_at_human || ''}`);
        
        // Update job details
        this.updateElement('details-job-overview', job.job_overview || 'No description available.', true);
        
        // Update sections
        this.updateTextSection('details-responsibilities-section', 'details-responsibilities', job.responsibilities);
        this.updateTextSection('details-requirements-section', 'details-requirements', job.requirements);
        
        // Update skills
        this.updateSkillsSection(job.skills);
        
        // Update action buttons for saved jobs
        this.updateSavedJobActionButtons(savedJob);
    }

    updateSavedJobActionButtons(savedJob) {
        const unsaveBtn = document.getElementById('unsave-job-btn');
        const applyBtn = document.getElementById('apply-saved-job-btn');
        
        // Check if user is authenticated
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
        
        if (unsaveBtn) {
            unsaveBtn.onclick = () => this.unsaveSavedJob(savedJob.id);
        }
        
        if (applyBtn && isAuthenticated) {
            // Check if user has already applied to this job
            const hasApplied = savedJob.job.has_applied || false; // You might need to add this to the controller response
            
            if (hasApplied) {
                applyBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Already Applied';
                applyBtn.style.backgroundColor = '#6B7280';
                applyBtn.disabled = true;
            } else {
                applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Apply Now';
                applyBtn.style.backgroundColor = '#006EDC';
                applyBtn.disabled = false;
                applyBtn.onclick = () => this.applySavedJob(savedJob.job.id);
            }
        }
    }

    unsaveSavedJob(savedJobId) {
        if (confirm('Are you sure you want to remove this job from your saved jobs?')) {
            const savedJob = this.savedJobs.find(item => item.id === savedJobId);
            if (!savedJob) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch(`/jobs/${savedJob.job.id}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Refresh page to see latest changes
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error unsaving job:', error);
                alert('Error removing job from saved jobs');
            });
        }
    }

    applySavedJob(jobId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch(`/jobs/${jobId}/apply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Refresh page to see latest changes
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error applying for job:', error);
            alert('Error submitting application');
        });
    }

    loadJobDetails(jobId) {
        if (this.currentJobId === jobId) return;
        
        this.currentJobId = jobId;
        
        // Update active job card styling with #006EDC color
        document.querySelectorAll('[data-job-id]').forEach(card => {
            card.classList.remove('border-blue-500', 'shadow-lg');
            card.classList.add('border-gray-200');
            card.style.borderColor = '';
        });
        
        const activeCard = document.querySelector(`[data-job-id="${jobId}"]`);
        if (activeCard) {
            activeCard.classList.add('border-blue-500', 'shadow-lg');
            activeCard.classList.remove('border-gray-200');
            activeCard.style.borderColor = '#006EDC';
        }
        
        // Show loading state
        const loadingElement = document.getElementById('job-details-loading');
        const contentElement = document.getElementById('job-details-content');
        
        if (loadingElement) loadingElement.style.display = 'flex';
        if (contentElement) contentElement.style.display = 'none';
        
        // Fetch job details
        fetch(`/jobs/${jobId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.populateJobDetails(data.job);
                    if (loadingElement) loadingElement.style.display = 'none';
                    if (contentElement) contentElement.style.display = 'flex';
                }
            })
            .catch(error => {
                console.error('Error loading job details:', error);
                if (loadingElement) {
                    loadingElement.innerHTML = 
                        '<div class="text-center"><p class="text-red-500">Error loading job details</p></div>';
                }
            });
    }

    loadApplicationDetails(applicationId) {
        if (this.currentApplicationId === applicationId) return;
        
        this.currentApplicationId = applicationId;
        
        // Update active application card styling
        document.querySelectorAll('[data-application-id]').forEach(card => {
            card.classList.remove('border-blue-500', 'shadow-lg');
            card.classList.add('border-gray-200');
            card.style.borderColor = '';
        });
        
        const activeCard = document.querySelector(`[data-application-id="${applicationId}"]`);
        if (activeCard) {
            activeCard.classList.add('border-blue-500', 'shadow-lg');
            activeCard.classList.remove('border-gray-200');
            activeCard.style.borderColor = '#006EDC';
        }
        
        // Find application data
        const application = this.applications.find(app => app.id === applicationId);
        if (!application) return;
        
        // Show loading state
        const loadingElement = document.getElementById('application-details-loading');
        const contentElement = document.getElementById('application-details-content');
        
        if (loadingElement) loadingElement.style.display = 'none';
        if (contentElement) contentElement.style.display = 'flex';
        
        // Populate details
        this.populateApplicationDetails(application);
    }

    populateJobDetails(job) {  
        // Update basic job information
        this.updateElement('details-company-logo', job.company_name.charAt(0));
        this.updateElement('details-job-title', job.title);
        this.updateElement('details-company-name', job.company_name);
        this.updateElement('details-location', `<i class="bi bi-geo-alt text-gray-500"></i> ${job.location || 'Not specified'}`, true);
        this.updateElement('details-job-type', job.job_type || 'Not specified');
        
        // Update salary
        const salaryText = job.salary_display && job.salary_min && job.salary_max ? 
            `RM ${job.salary_min.toLocaleString()} - ${job.salary_max.toLocaleString()}` : 'Undisclosed';
        this.updateElement('details-salary', salaryText);
        
        // Update job overview
        this.updateElement('details-job-overview', job.job_overview || 'No description available.', true);
        
        // Update posted date
        this.updateElement('details-posted-date', job.posted_date);
        this.updateElement('details-job-id', `#${job.id}`);
        
        // Update company logo duplicates
        this.updateElement('company-logo-large', job.company_name.charAt(0));
        this.updateElement('company-name-large', job.company_name);
        
        // Update sections with proper formatting
        this.updateListSection('details-responsibilities-section', 'details-responsibilities', job.responsibilities);
        this.updateListSection('details-requirements-section', 'details-requirements', job.requirements);
        
        // Update skills section
        this.updateSkillsSection(job.skills);
        
        // Update additional details
        this.updateOptionalRow('details-specialization-row', 'details-specialization', job.specialization);
        this.updateOptionalRow('details-education-row', 'details-education', job.education_level);
        
        // Update action buttons
        this.updateActionButtons(job);
    }

    populateApplicationDetails(application) {
        // Update basic information
        this.updateElement('details-company-logo', application.job.employer.company_name.charAt(0));
        this.updateElement('details-job-title', application.job.title);
        this.updateElement('details-company-name', application.job.employer.company_name);
        this.updateElement('details-location', `<i class="bi bi-geo-alt text-gray-500"></i> ${application.job.location || 'Not specified'}`, true);
        this.updateElement('details-job-type', application.job.job_type || 'Not specified');
        
        // Update salary
        const salaryText = application.job.salary_display && application.job.salary_min && application.job.salary_max ? 
            `RM ${application.job.salary_min.toLocaleString()} - ${application.job.salary_max.toLocaleString()}` : 'Undisclosed';
        this.updateElement('details-salary', salaryText);
        
        // Update application status
        const statusElement = document.getElementById('details-status');
        if (statusElement) {
            statusElement.textContent = application.status.charAt(0).toUpperCase() + application.status.slice(1);
            statusElement.className = 'px-3 py-2 rounded-lg text-sm font-medium ';
            
            // Add status-specific styling
            switch(application.status) {
                case 'submitted':
                    statusElement.className += 'bg-blue-100 text-blue-800';
                    break;
                case 'reviewed':
                    statusElement.className += 'bg-yellow-100 text-yellow-800';
                    break;
                case 'interviewed':
                    statusElement.className += 'bg-purple-100 text-purple-800';
                    break;
                case 'accepted':
                    statusElement.className += 'bg-green-100 text-green-800';
                    break;
                case 'rejected':
                    statusElement.className += 'bg-red-100 text-red-800';
                    break;
                case 'withdrawn':
                    statusElement.className += 'bg-gray-100 text-gray-800';
                    break;
                default:
                    statusElement.className += 'bg-gray-100 text-gray-800';
            }
        }
        
        // Update apply date
        this.updateElement('details-apply-date', `Applied ${application.apply_date_human || ''}`);
        
        // Update job details
        this.updateElement('details-job-overview', application.job.job_overview || 'No description available.', true);
        
        // Update sections
        this.updateTextSection('details-responsibilities-section', 'details-responsibilities', application.job.responsibilities);
        this.updateTextSection('details-requirements-section', 'details-requirements', application.job.requirements);
        
        // Update skills
        this.updateSkillsSection(application.job.skills);
        
        // Update action buttons for applications
        this.updateApplicationActionButtons(application);
    }

    updateElement(id, content, isHTML = false) {
        const element = document.getElementById(id);
        if (element) {
            if (isHTML) {
                element.innerHTML = content;
            } else {
                element.textContent = content;
            }
        }
    }

    updateSection(sectionId, contentId, content) {
        const section = document.getElementById(sectionId);
        const contentElement = document.getElementById(contentId);
        
        if (content && section && contentElement) {
            section.style.display = 'block';
            contentElement.innerHTML = content;
        } else if (section) {
            section.style.display = 'none';
        }
    }

    updateTextSection(sectionId, contentId, content) {
        const section = document.getElementById(sectionId);
        const contentElement = document.getElementById(contentId);
        
        if (section && contentElement) {
            if (content && content.trim()) {
                section.style.display = 'block';
                contentElement.textContent = content;
            } else {
                section.style.display = 'none';
            }
        }
    }

    updateSkillsSection(skills) {
        const section = document.getElementById('details-skills-section');
        const container = document.getElementById('details-skills');
        
        if (!section || !container) {
            return;
        }
        
        let skillsArray = [];
        
        // Handle different skill formats
        if (skills) {
            if (Array.isArray(skills)) {
                skillsArray = skills.filter(skill => skill && skill.trim());
            } else if (typeof skills === 'string' && skills.trim()) {
                skillsArray = [skills.trim()];
            }
        }
        
        // Always show the section
        section.style.display = 'block';
        
        if (skillsArray && skillsArray.length > 0) {
            // Create skill badges
            container.innerHTML = '';
            skillsArray.forEach(skill => {
                if (skill && skill.trim()) {
                    const skillBadge = document.createElement('span');
                    skillBadge.className = 'px-3 py-2 rounded-full text-sm font-medium';
                    skillBadge.style.backgroundColor = 'rgba(255, 165, 0, 0.15)';
                    skillBadge.style.color = '#FF8C00';
                    skillBadge.textContent = skill.trim();
                    container.appendChild(skillBadge);
                }
            });
        } else {
            container.innerHTML = '<span class="text-gray-500 italic">No specific skills listed</span>';
        }
    }

    updateOptionalRow(rowId, contentId, content) {
        const row = document.getElementById(rowId);
        const contentElement = document.getElementById(contentId);
        
        if (content && row && contentElement) {
            row.style.display = 'flex';
            contentElement.textContent = content;
        } else if (row) {
            row.style.display = 'none';
        }
    }

    updateActionButtons(job) {
        const applyBtn = document.getElementById('apply-job-btn');
        const saveBtn = document.getElementById('save-job-btn');
        
        // Check if user is authenticated using meta tag
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
        
        // Apply Button
        if (applyBtn) {
            if (isAuthenticated) {
                if (job.has_applied) {
                    applyBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Applied';
                    applyBtn.style.backgroundColor = '#006EDC';
                    applyBtn.disabled = true;
                } else {
                    applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Apply Now';
                    applyBtn.style.backgroundColor = '#006EDC';
                    applyBtn.disabled = false;
                }
            } else {
                applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Login to Apply';
                applyBtn.style.backgroundColor = '#006EDC';
                applyBtn.disabled = false;
            }
            applyBtn.onmouseover = function() { this.style.backgroundColor = '#005BB5'; };
            applyBtn.onmouseout = function() { this.style.backgroundColor = '#006EDC'; };
        }

        // Save Button
        if (saveBtn) {
            if (isAuthenticated) {
                if (job.has_saved) {
                    saveBtn.innerHTML = '<i class="bi bi-heart-fill mr-2"></i>Saved';
                    saveBtn.style.backgroundColor = '#FEE2E2';
                    saveBtn.style.color = '#991B1B';
                    saveBtn.onmouseover = function() { 
                        this.style.backgroundColor = '#fdc9c9';
                    };
                    saveBtn.onmouseout = function() { 
                        this.style.backgroundColor = '#FEE2E2';
                    };
                } else {
                    saveBtn.innerHTML = '<i class="bi bi-heart mr-2"></i>Save Job';
                    saveBtn.classList.remove('bg-red-100', 'text-red-700');
                    saveBtn.classList.add('bg-gray-100', 'text-gray-700');
                    saveBtn.style.backgroundColor = '';
                    saveBtn.style.color = '';
                    saveBtn.onmouseover = function() { 
                        this.classList.remove('bg-gray-100');
                        this.classList.add('bg-gray-200');
                    };
                    saveBtn.onmouseout = function() { 
                        this.classList.remove('bg-gray-200');
                        this.classList.add('bg-gray-100');
                    };
                }
            } else {
                saveBtn.innerHTML = '<i class="bi bi-heart mr-2"></i>Login to Save';
                saveBtn.classList.remove('bg-red-100', 'text-red-700');
                saveBtn.classList.add('bg-gray-100', 'text-gray-700');
                saveBtn.style.backgroundColor = '';
                saveBtn.style.color = '';
                saveBtn.onmouseover = function() { 
                    this.classList.remove('bg-gray-100');
                    this.classList.add('bg-gray-200');
                };
                saveBtn.onmouseout = function() { 
                    this.classList.remove('bg-gray-200');
                    this.classList.add('bg-gray-100');
                };
            }
        }
    }

    updateApplicationActionButtons(application) {
        const withdrawBtn = document.getElementById('withdraw-application-btn');
        
        if (withdrawBtn) {
            if (application.status === 'submitted') {
                withdrawBtn.style.display = 'flex';
                withdrawBtn.onclick = () => this.withdrawApplicationById(application.id);
            } else {
                withdrawBtn.style.display = 'none';
            }
        }
    }

    withdrawApplicationById(applicationId) {
        if (confirm('Are you sure you want to withdraw this application? This action cannot be undone.')) {
            fetch(`/applications/${applicationId}/withdraw`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error withdrawing application');
            });
        }
    }

    updateSort(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
    }

    showLoginModal() {
        // Store the current job ID for after login
        sessionStorage.setItem('pendingJobAction', this.currentJobId);
        
        // Dispatch event to open login modal
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'login' }));
    }

    applyForJob() {
        if (!this.currentJobId) return;
        
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
        
        if (isAuthenticated) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch(`/jobs/${this.currentJobId}/apply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    this.loadJobDetails(this.currentJobId); // Refresh job details
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error applying for job:', error);
                alert('Error submitting application');
            });
        } else {
            this.showLoginModal();
        }
    }

    saveJob() {
        if (!this.currentJobId) return;
        
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
        
        if (isAuthenticated) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch(`/jobs/${this.currentJobId}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Refresh page to see latest changes
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error saving job:', error);
                alert('Error saving job');
            });
        } else {
            this.showLoginModal();
        }
    }

    handlePostLogin() {
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
        
        if (isAuthenticated) {
            document.addEventListener('DOMContentLoaded', () => {
                // Check if user just logged in and had a pending job action
                const pendingJobId = sessionStorage.getItem('pendingJobAction');
                const loginSuccess = window.jobsPageData?.loginSuccess;
                
                if (pendingJobId && loginSuccess) {
                    sessionStorage.removeItem('pendingJobAction');
                    alert('Login successful! You can now apply for jobs.');
                    
                    // Reload the current job details to update button states
                    if (this.currentJobId) {
                        this.loadJobDetails(this.currentJobId);
                    }
                }
            });
        }
    }

    // Add this method to handle list sections with bullet points
    updateListSection(sectionId, contentId, content) {
        const section = document.getElementById(sectionId);
        const contentElement = document.getElementById(contentId);
        
        if (content && section && contentElement) {
            section.style.display = 'block';
            contentElement.textContent = content;
        } else if (section) {
            section.style.display = 'none';
        }
    }

    // Add this method to your JobsPage class
    checkUrlForJob() {
        const urlParams = new URLSearchParams(window.location.search);
        const jobId = urlParams.get('job');
        
        if (jobId && this.pageType === 'jobs') {
            // Auto-load the job details when coming from external link
            this.loadJobDetails(jobId);
        }
    }
}

// Global functions that can be called from HTML
window.loadJobDetails = function(jobId) {
    window.jobsPageInstance?.loadJobDetails(jobId);
};

window.loadApplicationDetails = function(applicationId) {
    window.jobsPageInstance?.loadApplicationDetails(applicationId);
};

window.loadSavedJobDetails = function(savedJobId) {
    window.jobsPageInstance?.loadSavedJobDetails(savedJobId);
};

window.updateSort = function(sortValue) {
    window.jobsPageInstance?.updateSort(sortValue);
};

window.applyForJob = function() {
    window.jobsPageInstance?.applyForJob();
};

window.saveJob = function() {
    window.jobsPageInstance?.saveJob();
};

window.unsaveJob = function() {
    if (window.jobsPageInstance?.currentSavedJobId) {
        window.jobsPageInstance?.unsaveSavedJob(window.jobsPageInstance.currentSavedJobId);
    }
};

window.applySavedJob = function() {
    const savedJob = window.jobsPageInstance?.savedJobs?.find(item => item.id === window.jobsPageInstance?.currentSavedJobId);
    if (savedJob) {
        window.jobsPageInstance?.applySavedJob(savedJob.job.id);
    }
};

window.withdrawApplication = function() {
    if (window.jobsPageInstance?.currentApplicationId) {
        window.jobsPageInstance?.withdrawApplicationById(window.jobsPageInstance.currentApplicationId);
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.jobsPageInstance = new JobsPage();
});