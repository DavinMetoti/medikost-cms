export class ToastRenderer {
    constructor(containerId = 'toastContainer') {
        this.containerId = containerId;
        this._initContainer();
    }

    _initContainer() {
        if (!document.getElementById(this.containerId)) {
            const container = document.createElement('div');
            container.id = this.containerId;
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055'; // Bootstrap modal level
            document.body.appendChild(container);
        }
    }

    showToast({ title = 'Notification', message = '', type = 'info', delay = 3000 }) {
        const types = {
            success: {
                alertClass: 'alert-subtle-success',
                icon: 'fas fa-check-circle text-success',
                title: 'Success'
            },
            error: {
                alertClass: 'alert-subtle-danger',
                icon: 'fas fa-times-circle text-danger',
                title: 'Error'
            },
            warning: {
                alertClass: 'alert-subtle-warning',
                icon: 'fas fa-exclamation-circle text-warning',
                title: 'Warning'
            },
            info: {
                alertClass: 'alert-subtle-info',
                icon: 'fas fa-info-circle text-info',
                title: 'Info'
            }
        };

        const toastId = `toast-${Date.now()}`;
        const { alertClass, icon, title: toastTitle } = types[type] || types.info;

        const toastHtml = `
            <div id="${toastId}" class="alert ${alertClass} d-flex align-items-center" role="alert" data-bs-delay="${delay}">
                <span class="${icon} fs-5 me-3"></span>
                <p class="mb-0 flex-1">${message}</p>
            </div>
        `;

        document.getElementById(this.containerId).insertAdjacentHTML('beforeend', toastHtml);

        // Auto-close toast after a delay (optional)
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            toastElement.classList.add('fade');
            toastElement.remove();
        }, delay);
    }
}
