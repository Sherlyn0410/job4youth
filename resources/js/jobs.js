class JobsPage {
    constructor() {
        this.currentJobId = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadFirstJob();
    }

    setupEventListeners() {
        // Load first job on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.loadFirstJob();
        });

        // Handle successful login for pending job actions
        this.handlePostLogin();
    }

    loadFirstJob() {
        // This will be populated by the blade template
        const firstJobId = window.jobsPageData?.firstJobId;
        if (firstJobId) {
            this.loadJobDetails(firstJobId);
        }
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
            } else if (typeof skills === 'string') {
                // Clean up the string - remove escaped quotes and extra quotes
                let cleanSkills = skills
                    .replace(/\\/g, '')           // Remove backslashes
                    .replace(/^["']+|["']+$/g, '') // Remove leading/trailing quotes
                    .trim();
                
                try {
                    // Try to parse as JSON first
                    if (cleanSkills.startsWith('[') && cleanSkills.endsWith(']')) {
                        skillsArray = JSON.parse(cleanSkills);
                    } else if (cleanSkills.includes(',')) {
                        // Handle comma-separated values
                        skillsArray = cleanSkills.split(',')
                            .map(skill => skill.replace(/["']/g, '').trim())
                            .filter(skill => skill);
                    } else if (cleanSkills.trim()) {
                        // Single skill
                        skillsArray = [cleanSkills.trim()];
                    }
                } catch (error) {
                    console.error('Error parsing skills:', error);
                    // Fallback: treat as comma-separated and clean quotes
                    skillsArray = cleanSkills.split(',')
                        .map(skill => skill.replace(/["']/g, '').trim())
                        .filter(skill => skill);
                }
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
                    skillBadge.className = 'px-3 py-2 rounded-full text-sm font-medium border';
                    skillBadge.style.backgroundColor = '#E3F2FD';
                    skillBadge.style.color = '#006EDC';
                    skillBadge.style.borderColor = '#006EDC';
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
        
        if (!applyBtn) return;

        // Check if user is authenticated using meta tag
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
        
        if (isAuthenticated) {
            // User is authenticated
            if (job.has_applied) {
                applyBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Applied';
                applyBtn.style.backgroundColor = '#006EDC';
                applyBtn.disabled = true;
                applyBtn.onmouseover = function() { this.style.backgroundColor = '#005BB5'; };
                applyBtn.onmouseout = function() { this.style.backgroundColor = '#006EDC'; };
            } else {
                applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Apply Now';
                applyBtn.style.backgroundColor = '#006EDC';
                applyBtn.disabled = false;
                applyBtn.onmouseover = function() { this.style.backgroundColor = '#005BB5'; };
                applyBtn.onmouseout = function() { this.style.backgroundColor = '#006EDC'; };
            }
        } else {
            // User is not authenticated
            applyBtn.innerHTML = '<i class="bi bi-send mr-2"></i>Login to Apply';
            applyBtn.style.backgroundColor = '#006EDC';
            applyBtn.disabled = false;
            applyBtn.onmouseover = function() { this.style.backgroundColor = '#005BB5'; };
            applyBtn.onmouseout = function() { this.style.backgroundColor = '#006EDC'; };
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
}

// Global functions that can be called from HTML
window.loadJobDetails = function(jobId) {
    window.jobsPageInstance?.loadJobDetails(jobId);
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

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.jobsPageInstance = new JobsPage();
});