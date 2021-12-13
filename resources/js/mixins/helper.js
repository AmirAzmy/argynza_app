export default {
    methods: {
        buildFormData(formData, data, parentKey) {
            if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File) && !(data instanceof Blob)) {
                Object.keys(data).forEach(key => {
                    this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
                });
            } else {
                let value = data == null ? '' : data;
                if (typeof (data) === 'boolean' && data === false) {
                    value = '0'
                }
                if (typeof (data) === 'boolean' && data === true) {
                    value = '1'
                }
                formData.append(parentKey, value);
            }
        }
    }
}
