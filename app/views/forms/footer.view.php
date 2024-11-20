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
        const nextButton = document.getElementById('next-button');
        const footerTable = document.getElementById('footer-table');

        // Show the footer table when the page loads (change this logic if needed)
        window.addEventListener('load', function() {
            footerTable.style.display = 'table'; // Make the table visible
        });

        // Move to the next step when the Next button is clicked
        nextButton.addEventListener('click', function() {
            if (step < 3) {
                step++;
                window.updateHeader(); // Update the header dynamically
            }
        });
</script>
