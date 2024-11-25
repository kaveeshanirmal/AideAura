<style>
    /* Footer container styling for form2 */
    .form2-footer {
        position: relative;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 10px;
        background-color: white;
        box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: border-box; /* Include padding in width calculation */
    }

    /* Footer table styling for form2 */
    .form2-footer-table {
        width: 90%; /* Ensure the table width is responsive */
        display: table; /* Table is hidden by default */
        text-align: center;
        box-sizing: border-box; /* Ensure padding and borders are included in width */
    }

    /* Footer table cell styling for form2 */
    .form2-footer-table td {
        padding: 5px; /* Adjust padding for better spacing */
        vertical-align: middle;
    }

    /* Salary and working hours text styling for form2 */
    .form2-footer-table .form2-salary,
    .form2-footer-table .form2-hours {
        font-size: 14px;
    }

    /* Button styling for form2 */
    .form2-footer-table button {
        background-color: #4C9EBE;
        color: white;
        padding: 10px 30px; /* Adjusted padding to ensure it fits */
        border: none;
        border-radius: 7px;
        cursor: pointer;
        font-size: 14px;
        width: auto; /* Ensure button width adjusts properly */
    }

    /* Estimate text styling for form2 */
    .form2-footer-table .form2-estimate {
        font-size: 12px;
        color: #555;
    }

    /* Row 2 styling for form2 */
    .form2-footer-table tr.form2-row-2 td {
        text-align: left;
        font-size: 12px;
    }

    /* Colored text styling for form2 */
    .form2-coloredText {
        color: #8b0000;
    }

    /* Responsive adjustments for form2 */
    @media (max-width: 768px) {
        .form2-footer-table {
            width: 100%; /* Table takes full width on smaller screens */
            padding: 0 10px; /* Adjust padding on smaller screens */
        }

        .form2-footer-table .form2-salary,
        .form2-footer-table .form2-hours {
            font-size: 12px; /* Reduce font size on smaller screens */
        }

        .form2-footer-table .form2-estimate {
            font-size: 10px; /* Reduce font size for estimate */
        }

        .form2-footer-table button {
            padding: 8px 30px; /* Reduce padding for the button */
            font-size: 12px; /* Reduce font size of the button */
        }

        .form2-footer-table tr.form2-row-2 td {
            font-size: 10px; /* Adjust font size in the row for estimate */
        }
    }
</style>

<div class="form2-footer">
    <table class="form2-footer-table" id="form2-footer-table">
        <tr>
            <td class="form2-salary">Monthly Salary: <span class="form2-coloredText">Rs.30 000</span> approx</td>
            <td class="form2-hours">Daily Working Hours: <span class="form2-coloredText">2:00</span> approx</td>
            <td class="form2-next-btn">
                <button id="form2-done-button">Done</button>
            </td>
        </tr>
        <tr class="form2-row-2">
            <td colspan="3" class="form2-estimate">
                *estimate varies with workload, shifts, timings, and location
            </td>
        </tr>
    </table>
</div>

<script>
    const doneButton = document.getElementById('form2-done-button');
    const footerTable = document.getElementById('form2-footer-table');
    const serviceFormModal = document.querySelector('.service-form-modal');

    // Close the form/modal when the Done button is clicked
    doneButton.addEventListener('click', function() {
        serviceFormModal.style.display = 'none'; // Hide the modal/form
    });
</script>
