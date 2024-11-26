// Global step variable
let step = 1;

document.addEventListener('DOMContentLoaded', function() {
    const nextButton = document.getElementById('next-button');
    const backArrow = document.getElementById('back-arrow');
    const headerTitle = document.getElementById('header-title');
    const progressBar = document.getElementById('progress-bar');
    
    // Get content elements
    const step1Content = document.getElementById('step1-content');
    const step2Content = document.getElementById('step2-content');
    const step3Content = document.getElementById('step3-content');

    console.log('Found elements:', {
        step1: !!step1Content,
        step2: !!step2Content,
        step3: !!step3Content
    });

    function updateUI() {
        console.log('Updating UI for step:', step);

        // Hide all content
        document.querySelectorAll('.content').forEach(content => {
            content.style.display = 'none';
            console.log('Hiding content:', content.id);
        });

        // Show current step content
        switch(step) {
            case 1:
                if (step1Content) {
                    step1Content.style.display = 'block';
                    console.log('Showing step 1');
                }
                headerTitle.textContent = "Select a Service";
                backArrow.style.visibility = "hidden";
                progressBar.style.width = "33%";
                nextButton.textContent = "Next";
                break;
            
            case 2:
                if (step2Content) {
                    step2Content.style.display = 'block';
                    console.log('Showing step 2');
                }
                headerTitle.textContent = "Select the Working Hours & Starting Date";
                backArrow.style.visibility = "visible";
                progressBar.style.width = "66%";
                nextButton.textContent = "Next";
                break;
            
            case 3:
                if (step3Content) {
                    step3Content.style.display = 'block';
                    console.log('Showing step 3');
                }
                headerTitle.textContent = "Booking Summary";
                backArrow.style.visibility = "visible";
                progressBar.style.width = "100%";
                nextButton.textContent = "Confirm";
                break;
        }
    }

    // Next button click handler
    nextButton.addEventListener('click', function() {
        console.log('Next clicked, current step:', step);
        if (step < 3) {
            step++;
            updateUI();
        }
    });

    // Back arrow click handler
    backArrow.addEventListener('click', function() {
        console.log('Back clicked, current step:', step);
        if (step > 1) {
            step--;
            updateUI();
        }
    });

    // Initialize UI
    updateUI();
});
