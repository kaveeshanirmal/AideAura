document.addEventListener('DOMContentLoaded', function() {
    const doneButton = document.getElementById('next-button');
    const alertMessage = document.getElementById('alert-message');
    let messageTimeout;

    function showMessage() {
        alertMessage.classList.add('show');
        
        // Clear any existing timeout
        if (messageTimeout) {
            clearTimeout(messageTimeout);
        }
        
        // Hide message after 3 seconds
        messageTimeout = setTimeout(() => {
            alertMessage.classList.remove('show');
        }, 3000);
    }

    doneButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Get all radio button groups
        const people = document.querySelector('input[name="people"]:checked');
        const meals = document.querySelector('input[name="meals"]:checked');
        const preference = document.querySelector('input[name="preference"]:checked');
        const dogs = document.querySelector('input[name="dogs"]:checked');

        // Check if any option is not selected
        if (!people || !meals || !preference || !dogs) {
            showMessage();
            return;
        }

        // If all options are selected, you can proceed
        console.log('All questions answered!');
    });
});