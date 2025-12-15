export class FormHandler {
    constructor(route, formData, formElement, event, options = {}) {
        this.route = route;
        this.formData = formData;
        this.formElement = formElement;
        this.event = event;
        this.options = options;

        this.submitButton = $(formElement).find('button[type="submit"]');
        this.loadingClass = 'loading';

        this.handleSuccess = this.handleSuccess.bind(this);
        this.handleError = this.handleError.bind(this);
    }

    sendRequest() {
        this.event.preventDefault();

        this.setLoadingState(true);

        return new Promise((resolve, reject) => {
            $.ajax({
                url: this.route,
                type: 'POST',
                data: this.formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    this.handleSuccess(response);
                    this.setLoadingState(false);
                    resolve(response);
                },
                error: (xhr) => {
                    this.handleError(xhr);
                    this.setLoadingState(false);
                    reject(xhr);
                }
            });
        });
    }

    setLoadingState(isLoading) {
        if (isLoading) {
            this.submitButton.prop('disabled', true);
            this.submitButton.addClass(this.loadingClass);
            this.submitButton.html('<span class="spinner-border spinner-border-sm"></span>');
        } else {
            this.submitButton.prop('disabled', false);
            this.submitButton.removeClass(this.loadingClass);
            this.submitButton.html('Update');
        }
    }

    handleSuccess(response) {
        App.Toast.showToast({
            title: 'Success!',
            message: response.message,
            type: 'success',
            delay: 5000
        });

        if (this.options.redirectUrl) {
            window.location.href = this.options.redirectUrl;
        } else {
            $(this.formElement)[0].reset();
        }
    }

    handleError(xhr) {
        let errorMessage = xhr.responseJSON?.message || 'An error occurred while processing your request.';

        App.Toast.showToast({
            title: 'Error!',
            message: errorMessage,
            type: 'error',
            delay: 5000
        });
    }
}
