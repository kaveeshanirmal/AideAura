<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/formFooter.css">
<div class="form-footer">
    <table class="form-footer-table" id="form-footer-table">
        <tr>
            <td class="salary">Monthly Salary: <span class="coloredText">Rs.30 000</span> approx</td>
            <td class="hours">Daily Working Hours: <span class="coloredText">2:00</span> approx</td>
            <td class="next-btn">
                <button id="next-button">Next</button>
            </td>
        </tr>
        <tr class="row-2">
            <td colspan="3" class="estimate">
                *estimate varies with workload, shifts, timings, and location
            </td>
        </tr>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nextButton = document.getElementById('next-button');
        const footerTable = document.getElementById('form-footer-table');
        const contents = document.querySelectorAll('.content');
        const headerTitle = document.getElementById('header-title');
        let currentStep = 1;

        function updateUI(stepNumber) {
            // Update content visibility
            contents.forEach((content, index) => {
                content.style.display = (index + 1 === stepNumber) ? 'block' : 'none';
            });

            // Update header title
            switch(stepNumber) {
                case 1:
                    headerTitle.textContent = "Select a Service";
                    break;
                case 2:
                    headerTitle.textContent = "Select the Working Hours & Starting Date";
                    break;
                case 3:
                    headerTitle.textContent = "Booking Summary";
                    break;
            }

            // Update button text
            nextButton.textContent = stepNumber === 3 ? 'Done' : 'Next';

            // Update progress bar
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.width = `${(stepNumber / 3) * 100}%`;

            // Show/hide back arrow
            const backArrow = document.getElementById('back-arrow');
            backArrow.style.visibility = stepNumber === 1 ? 'hidden' : 'visible';
        }

        // Initialize UI
        footerTable.style.display = 'table';
        updateUI(currentStep);

        // Handle next button clicks
        nextButton.addEventListener('click', function() {
            if (currentStep < 3) {
                currentStep++;
                updateUI(currentStep);
            }
        });

        // Handle back arrow clicks
        const backArrow = document.getElementById('back-arrow');
        backArrow.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateUI(currentStep);
            }
        });
    });
</script>
