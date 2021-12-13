export default {
    methods: {
        errorMessages(res) {
            let message = this.generateMessage(res);
            swal({
                html: true,
                icon: "error",
                title: "خطأ",
                text: message
            });
        },
        generateMessage(res) {
            let message = 'هتاك خطأ ما, الرجاء المحاوله فى وقت لاحق .';
            if ('errors' in res) {
                let errors = res.errors;
                if (!errors) {
                    if (res.message) {
                        message = res.message;
                    }
                    return message
                }
                message = '';
                for (const error of errors) {
                    console.log(error);
                    message += error.msg + '\r\n' + '\r\n';
                    // message +=  key.toUpperCase()+"" + ':- ' + error + '\r\n'+'\r\n'
                }
            }
            return message
        }
    }
}
