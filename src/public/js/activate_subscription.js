async function fetchData(amount) {
    try {
        const dto = {
            paymentValue: amount
        }
        const httpClient = new HttpClient();
        let url = `/api/payment`;
        const response = await httpClient.post(url, dto, false);
        const responseData = JSON.parse(response.body).data;
        if (responseData === 'false') {
            alert("Payment failed. Please try again.");
            window.location.reload();
        } else {
            alert("Payment processed successfully!");
            window.location.href = "/dashboard";
        }
    } catch (error) {
        console.error("An error occurred during fetch data:", error);
        alert("An error occurred during fetch data.");
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sendButton = document.getElementById('send-button');
    const amountInput = document.getElementById('amount');

    sendButton.addEventListener('click', function() {
        const amount = amountInput.value;

        if(amount && !isNaN(amount)) {
            fetchData(amount)
        } else {
            console.log('Please enter a valid amount');
        }
    });
});

