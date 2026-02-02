export class Select2Wrapper {
    constructor(selector, options = {}) {
        this.selector = selector;
        this.options = options;
        this.init();
    }

    init() {
        const defaultOptions = {
            width: 'resolve',
            allowClear: true,
            placeholder: 'Silakan pilih...',
            theme: 'bootstrap-5'
        };

        if (this.options.ajax && typeof this.options.ajax === 'string') {
            this.options.ajax = {
                url: this.options.ajax,
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term ?? '',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                },
                processResults: function (data) {
                    if (Array.isArray(data.results)) {
                        return {
                            results: data.results.map(item => ({
                                id: item.id,
                                text: item.text || item.name || item.nama || item.label
                            }))
                        };
                    } else {
                        console.error("Invalid data format:", data);
                        return { results: [] };
                    }
                },
                cache: true
            };
        }

        const mergedOptions = Object.assign({}, defaultOptions, this.options);

        $(this.selector).select2(mergedOptions);
    }

    destroy() {
        $(this.selector).select2('destroy');
    }

    setData(data) {
        $(this.selector).empty().select2({
            ...this.options,
            data: data
        });
    }

    setValue(value) {
        $(this.selector).val(value).trigger('change');
    }

    getValue() {
        return $(this.selector).val();
    }

    onChange(callback) {
        $(this.selector).on('change', callback);
    }
}
