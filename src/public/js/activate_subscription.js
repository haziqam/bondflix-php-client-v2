document.addEventListener('DOMContentLoaded', function() {
    const sendButton = document.getElementById('send-button');
    const amountInput = document.getElementById('amount');

    sendButton.addEventListener('click', function() {
        const amount = amountInput.value;

        if(amount && !isNaN(amount)) {
            // Send data to soap if yes then use the route in php to update the user data

        } else {
            console.log('Please enter a valid amount');
        }
    });
});
