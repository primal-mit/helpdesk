
    const steps = [
        { label: 'Open', activeIcon: 'bi-envelope-open-fill' },
        { label: 'Assigned', activeIcon: 'bi-person-fill-check' },
        { label: 'In Progress', activeIcon: 'bi-gear-fill' },
        { label: 'Resolved', activeIcon: 'bi-check-circle-fill' },
        { label: 'Closed', activeIcon: 'bi-lock-fill' }
    ];

    const timelineContainer = document.getElementById('timeline-nodes');
    const progressBar = document.getElementById('progress-bar');
    const controlButtons = document.querySelectorAll('.simulation-controls button');

    /**
     * Initializes the HTML structure for the timeline nodes
     */
    function initTimeline() {
        steps.forEach((step, index) => {
            const stepDiv = document.createElement('div');
            stepDiv.className = 'timeline-step';
            stepDiv.innerHTML = `
                <div class="timeline-circle shadow-sm" id="circle-${index}">
                    <!-- Icon or number injected by JS -->
                </div>
                <span class="small fw-bold step-text" id="text-${index}">${step.label}</span>
            `;
            timelineContainer.appendChild(stepDiv);
        });
    }

    /**
     * Updates the timeline visuals based on the current status index
     * @param {number} currentStepIndex - The active status index (0 to 4)
     */
    function updateTimeline(currentStepIndex) {
        
        // 1. Update the progress bar width
        // With 5 items, the 4 segments are 25% each (0%, 25%, 50%, 75%, 100%)
        const progressPercent = currentStepIndex * 25;
        progressBar.style.width = progressPercent + '%';

        // 2. Loop through and update each node's colors and icons
        steps.forEach((step, index) => {
            const circle = document.getElementById(`circle-${index}`);
            const text = document.getElementById(`text-${index}`);
            
            // Reset base classes for animation
            circle.className = 'timeline-circle';
            text.className = 'small step-text';

            if (index <= currentStepIndex) {
                // COMPLETED STEPS (Green, checkmark)
                circle.classList.add('bg-success', 'text-white');
                text.classList.add('text-success', 'fw-bold');
                circle.innerHTML = '<i class="bi bi-check-lg"></i>';
            } 
            else {
                // PENDING STEPS (Gray, Number)
                circle.classList.add('bg-secondary', 'text-white');
                text.classList.add('text-muted');
                circle.innerHTML = index + 1; // Displays 1, 2, 3... etc.
            }
        });

        // 3. Update the simulation buttons for visual feedback
        controlButtons.forEach((btn, index) => {
            btn.className = index === currentStepIndex 
                ? 'btn btn-orange px-4 rounded-pill shadow-sm' 
                : 'btn btn-outline-secondary px-4 rounded-pill';
        });
    }

    // Initialize the DOM elements
    initTimeline();
