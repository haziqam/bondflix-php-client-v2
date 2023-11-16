async function fetchData(amount) {
    try {
        const dto = {
            paymentValue: amount
        }
        const httpClient = new HttpClient();
        let url = `/api/payment`;
        const response = await httpClient.post(url, dto, false);
        // const data = JSON.parse(response.body).data
        // console.log(data)
    //     Kalo true kenapa ini...
    } catch (error) {
        console.log(error);
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
            fetchData(amount).then(() => {console.log("Harusnya pop up tapi redirect aja")})
        } else {
            console.log('Please enter a valid amount');
        }
    });
});

