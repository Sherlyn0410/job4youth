class JobsPage {
    constructor() {
        this.currentJobId = null;
        this.currentApplicationId = null;
        this.currentSavedJobId = null;
        this.pageType = 'jobs'; // 'jobs', 'applications', or 'saved-jobs'
        this.applications = []; 
        this.savedJobs = []; 
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
        this.updateSkillsSection(job.soft_skills, job.hard_skills);
        
        // Update additional details
        this.updateOptionalRow('details-specialization-row', 'details-specialization', job.specialization);
        this.updateOptionalRow('details-education-row', 'details-education', job.education_level);
        
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
                    // Refresh page to update latest changes
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
                // Refresh page to update latest changes
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
        
        // Update active job card styling
        document.querySelectorAll('[data-job-id]').forEach(card => {
            card.classList.remove('border-blue-500', 'shadow-lg');
            card.classList.add('border-gray-200');
        });
        
        const activeCard = document.querySelector(`[data-job-id="${jobId}"]`);
        if (activeCard) {
            activeCard.classList.add('border-blue-500', 'shadow-lg');
            activeCard.classList.remove('border-gray-200');
        }
        
        // Show loading state
        const loadingElement = document.getElementById('job-details-loading');
        const contentElement = document.getElementById('job-details-content');
        
        if (loadingElement) loadingElement.style.display = 'flex';
        if (contentElement) contentElement.style.display = 'none';
        
        // Fetch job details
        fetch(`/jobs/${jobId}/details`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON');
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success && data.job) {
                const job = data.job;
                
                // Hide loading and show content
                if (loadingElement) loadingElement.style.display = 'none';
                if (contentElement) contentElement.style.display = 'flex';
                
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
                
                // Update sections with proper formatting
                this.updateListSection('details-responsibilities-section', 'details-responsibilities', job.responsibilities);
                this.updateListSection('details-requirements-section', 'details-requirements', job.requirements);
                
                // Update skills section
                this.updateSkillsSection(job.soft_skills, job.hard_skills);
                
                // Update additional details
                this.updateOptionalRow('details-specialization-row', 'details-specialization', job.specialization);
                this.updateOptionalRow('details-education-row', 'details-education', job.education_level);
                
                // Update action buttons
                this.updateActionButtons(job);
            } else {
                throw new Error(data.message || 'Invalid response format');
            }
        })
        .catch(error => {
            console.error('Error loading job details:', error);
            
            // Hide loading and show error
            if (loadingElement) loadingElement.style.display = 'none';
            if (contentElement) {
                contentElement.style.display = 'flex';
                contentElement.innerHTML = `
                    <div class="flex justify-center items-center h-full p-6">
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                            <p class="text-red-600 font-medium">Error loading job details</p>
                            <p class="text-gray-500 text-sm mt-2">${error.message}</p>
                            <button onclick="window.jobsPageInstance.loadJobDetails(${jobId})" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Try Again
                            </button>
                        </div>
                    </div>
                `;
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
        this.updateSkillsSection(application.job.soft_skills, application.job.hard_skills);
        
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

    updateSkillsSection(softSkills, hardSkills) {
        const section = document.getElementById('details-skills-section');
        const container = document.getElementById('details-skills');
        
        if (!section || !container) {
            return;
        }
        
        // Process soft skills
        let softSkillsArray = [];
        if (softSkills) {
            if (Array.isArray(softSkills)) {
                softSkillsArray = softSkills.filter(skill => skill && skill.trim());
            } else if (typeof softSkills === 'string' && softSkills.trim()) {
                try {
                    const parsed = JSON.parse(softSkills);
                    softSkillsArray = Array.isArray(parsed) ? parsed.filter(skill => skill && skill.trim()) : [softSkills.trim()];
                } catch (e) {
                    softSkillsArray = [softSkills.trim()];
                }
            }
        }
        
        // Process hard skills
        let hardSkillsArray = [];
        if (hardSkills) {
            if (Array.isArray(hardSkills)) {
                hardSkillsArray = hardSkills.filter(skill => skill && skill.trim());
            } else if (typeof hardSkills === 'string' && hardSkills.trim()) {
                try {
                    const parsed = JSON.parse(hardSkills);
                    hardSkillsArray = Array.isArray(parsed) ? parsed.filter(skill => skill && skill.trim()) : [hardSkills.trim()];
                } catch (e) {
                    hardSkillsArray = [hardSkills.trim()];
                }
            }
        }
        
        // Show/hide section based on whether we have any skills
        if (softSkillsArray.length > 0 || hardSkillsArray.length > 0) {
            section.style.display = 'block';
            container.innerHTML = '';
            
            // Add soft skills section
            if (softSkillsArray.length > 0) {
                const softSkillsRow = document.createElement('div');
                softSkillsRow.className = 'flex items-start gap-4 mb-6';
                
                // Title on the left
                const softSkillsTitle = document.createElement('div');
                softSkillsTitle.className = 'shrink-0 w-32';
                softSkillsTitle.innerHTML = '<h4 class="text-lg font-semibold text-gray-900">Soft Skills</h4>';
                
                // Skills on the right
                const softSkillsDiv = document.createElement('div');
                softSkillsDiv.className = 'flex flex-wrap gap-2 flex-1';
                softSkillsArray.forEach(skill => {
                    if (skill && skill.trim()) {
                        const skillBadge = document.createElement('span');
                        skillBadge.className = 'inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-orange-50 text-orange-600 border border-orange-200';
                        skillBadge.textContent = skill.trim();
                        softSkillsDiv.appendChild(skillBadge);
                    }
                });
                
                softSkillsRow.appendChild(softSkillsTitle);
                softSkillsRow.appendChild(softSkillsDiv);
                container.appendChild(softSkillsRow);
            }
            
            // Add hard skills section
            if (hardSkillsArray.length > 0) {
                const hardSkillsRow = document.createElement('div');
                hardSkillsRow.className = 'flex items-start gap-4';
                
                // Title on the left
                const hardSkillsTitle = document.createElement('div');
                hardSkillsTitle.className = 'shrink-0 w-32';
                hardSkillsTitle.innerHTML = '<h4 class="text-lg font-semibold text-gray-900 flex items-center">Hard Skills</h4>';
                
                // Skills on the right
                const hardSkillsDiv = document.createElement('div');
                hardSkillsDiv.className = 'flex flex-wrap gap-2 flex-1';
                hardSkillsArray.forEach(skill => {
                    if (skill && skill.trim()) {
                        const skillBadge = document.createElement('span');
                        skillBadge.className = 'inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-orange-50 text-orange-600 border border-orange-200';
                        skillBadge.textContent = skill.trim();
                        hardSkillsDiv.appendChild(skillBadge);
                    }
                });
                
                hardSkillsRow.appendChild(hardSkillsTitle);
                hardSkillsRow.appendChild(hardSkillsDiv);
                container.appendChild(hardSkillsRow);
            }
        } else {
            section.style.display = 'block';
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
            // Clear existing hover events first
            applyBtn.onmouseover = null;
            applyBtn.onmouseout = null;
            
            if (isAuthenticated) {
                if (job.has_applied) {
                    applyBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Applied';
                    applyBtn.style.backgroundColor = '#6B7280';
                    applyBtn.style.color = '#FFFFFF';
                    applyBtn.disabled = true;
                } else {
                    applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Apply Now';
                    applyBtn.style.backgroundColor = '#006EDC';
                    applyBtn.style.color = '#FFFFFF';
                    applyBtn.disabled = false;
                    // Add hover effects only for active buttons
                    applyBtn.onmouseover = function() { this.style.backgroundColor = '#005BB5'; };
                    applyBtn.onmouseout = function() { this.style.backgroundColor = '#006EDC'; };
                }
            } else {
                applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Login to Apply';
                applyBtn.style.backgroundColor = '#006EDC';
                applyBtn.style.color = '#FFFFFF';
                applyBtn.disabled = false;
                applyBtn.onmouseover = function() { this.style.backgroundColor = '#005BB5'; };
                applyBtn.onmouseout = function() { this.style.backgroundColor = '#006EDC'; };
            }
        }

        // Save Button
        if (saveBtn) {
            // Clear all existing styles and events
            saveBtn.className = 'flex-1 px-6 py-3 font-medium rounded-lg transition-colors flex items-center justify-center gap-2';
            saveBtn.style.backgroundColor = '';
            saveBtn.style.color = '';
            saveBtn.onmouseover = null;
            saveBtn.onmouseout = null;
            
            if (isAuthenticated) {
                if (job.has_saved) {
                    saveBtn.innerHTML = '<i class="bi bi-heart-fill mr-2"></i>Saved';
                    saveBtn.style.backgroundColor = '#FEE2E2';
                    saveBtn.style.color = '#991B1B';
                    saveBtn.style.borderColor = '#FECACA';
                    saveBtn.style.border = '1px solid #FECACA';
                    // Add hover effect for saved state
                    saveBtn.onmouseover = function() { 
                        this.style.backgroundColor = '#FDC2C4';
                    };
                    saveBtn.onmouseout = function() { 
                        this.style.backgroundColor = '#FEE2E2';
                    };
                } else {
                    saveBtn.innerHTML = '<i class="bi bi-heart mr-2"></i>Save Job';
                    saveBtn.style.backgroundColor = '#F3F4F6';
                    saveBtn.style.color = '#374151';
                    saveBtn.style.borderColor = '#D1D5DB';
                    saveBtn.style.border = '1px solid #D1D5DB';
                    // Add hover effect for unsaved state
                    saveBtn.onmouseover = function() { 
                        this.style.backgroundColor = '#E5E7EB';
                    };
                    saveBtn.onmouseout = function() { 
                        this.style.backgroundColor = '#F3F4F6';
                    };
                }
            } else {
                saveBtn.innerHTML = '<i class="bi bi-heart mr-2"></i>Login to Save';
                saveBtn.style.backgroundColor = '#F3F4F6';
                saveBtn.style.color = '#374151';
                saveBtn.style.borderColor = '#D1D5DB';
                saveBtn.style.border = '1px solid #D1D5DB';
                saveBtn.onmouseover = function() { 
                    this.style.backgroundColor = '#E5E7EB';
                };
                saveBtn.onmouseout = function() { 
                    this.style.backgroundColor = '#F3F4F6';
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
                    // Refresh page to update latest changes
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
                    // Refresh page to update button states
                    window.location.reload();
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
                    // Refresh page to update button states
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