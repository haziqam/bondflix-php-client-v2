class Helper {
    constructor() {
    }

    openModal(modal, openButton, closeButton, submitButton, onSubmit) {
        openButton.addEventListener("click", () => {
            modal.style.display = "block";
        });

        closeButton.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        if (onSubmit) {
            submitButton.addEventListener("click", async (e) => {
                e.preventDefault();
                onSubmit(modal);
            });
        }
    }

    getUrlParameter(name) {
        name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
}