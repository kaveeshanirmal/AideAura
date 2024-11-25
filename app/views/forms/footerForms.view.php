<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/formFooter.css">
<div class="form-footer">
    <table class="form-footer-table" id="form-footer-table" style="display: table;">
        <tr>
            <td class="salary">Monthly Salary: <span class="coloredText" id="modal-salary">Rs.0</span> approx</td>
            <td class="hours">Daily Working Hours: <span class="coloredText" id="modal-hours">0:00</span> approx</td>
            <td class="next-btn">
                <button id="modal-done-btn" onclick="handleFormSubmit()">Done</button>
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
    const PRICING_DATA = <?php echo json_encode($pricingData); ?>;
    
    // Initialize calculations when form loads
    const form = document.querySelector('form');
    if (form) {
        const serviceType = form.id === 'homeStyleForm' ? 'home-style-food' : 'dishwashing';
        initializeFormCalculations(serviceType);
    }
});

function handleFormSubmit() {
    const form = document.querySelector('form');
    if (!form) return;

    const serviceType = form.id === 'homeStyleForm' ? 'homeStyleFood' : 'dishwashing';
    
    // Close modal immediately
    closeModal();
}

function formatHours(hours) {
    const wholeHours = Math.floor(hours);
    const minutes = Math.round((hours - wholeHours) * 60);
    return `${wholeHours}:${minutes.toString().padStart(2, '0')}`;
}
</script>
