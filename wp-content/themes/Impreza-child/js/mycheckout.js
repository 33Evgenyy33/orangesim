jQuery(document).ready(function ($) {
    $("#billing_phone").inputmask({mask:"79999999999"});
    let orangeReplenishment = $("#orange_replenishment");
    orangeReplenishment.inputmask({
        mask:"699999999",
         "oncomplete": function(e){
             // $( document.body ).trigger( 'update_checkout' );
             // $('form.checkout').trigger( 'update' );
             e.preventDefault();
             postcodeAjax();
             $('body').trigger('update_checkout');
         }
    });

    if(orangeReplenishment.val()){
        postcodeAjax();
    }

    function postcodeAjax(){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data:  {
                action: 'woocommerce_apply_state',
                orange_replenishment: orangeReplenishment.val(),
            },
            url: submit_dropzonejs.url,
            success: function (response) {
                $('body').trigger('update_checkout');
                console.log(response);
            }
        });
    }

    // $(document).ajaxSuccess(function(event, xhr, settings) {
    //     console.log(xhr);
    // });
    // $(document).ajaxSuccess(function(event, xhr, settings) {
    //     console.log(xhr);
    // });

    let dropzoneForm;
    let fileList = [];
    // let dropzoneForm = new Dropzone("#dropzone-wordpress-form", {
    Dropzone.options.dropzoneWordpressForm = {
        // autoProcessQueue: false,
        acceptedFiles: ".jpg, .png, .pdf, .doc, .docx", // only .jpg files
        // maxFiles: 1,
        uploadMultiple: true,
        maxFilesize: 10, // 10 MB
        parallelUploads: 1,
        addRemoveLinks: true,
        url: submit_dropzonejs.url,
        paramName: "file",
        params: {
            action: "submit_dropzonejs"
        },
        dictRemoveFile: 'Удалить файл',
        dictCancelUpload: 'Отменить загрузку',
        dictFileTooBig: "Размер файла слишком большой ({{filesize}}Мб). Максимальный размер: {{maxFilesize}}Мб.",
        successmultiple: function (file, serverFileName) {
            fileList.push({"serverFileName": serverFileName, "fileName": file[0].name});
            // console.log(fileList);
        },
        queuecomplete: function () {
            // console.log(fileList);
            let forUploadedFiles = fileList;
            let forUploadedFilesLength = forUploadedFiles.length;
            let uploadedFilesElement = $('#uploaded_files');
            uploadedFilesElement.val('');
            $.each(forUploadedFiles, function (index, value) {
                // console.log(value['serverFileName']);
                if (index === (forUploadedFilesLength - 1)) {
                    uploadedFilesElement.val(uploadedFilesElement.val() + value['serverFileName']);
                } else {
                    uploadedFilesElement.val(uploadedFilesElement.val() + value['serverFileName'] + ',');
                }
            });
        },
        removedfile: function (file) {
            let rmvFile = "";
            for (let f = 0; f < fileList.length; f++) {

                if (fileList[f].fileName == file.name) {
                    rmvFile = fileList[f].serverFileName;

                }
            }
            // console.log(fileList);


            if (rmvFile) {
                data = {
                    action: 'remove_dropzonejs_file',
                    "fileList": rmvFile
                };
                jQuery.post(submit_dropzonejs.url, data, function (response) {
                    // console.log(response);
                    let forUploadedFiles = fileList;
                    var forUploadedFilesLength = forUploadedFiles.length;
                    let uploadedFilesElement = $('#uploaded_files');
                    uploadedFilesElement.val('');
                    $.each(forUploadedFiles, function (index, value) {
                        // console.log(value['serverFileName']);
                        if (index === (forUploadedFilesLength - 1)) {
                            uploadedFilesElement.val(uploadedFilesElement.val() + value['serverFileName']);
                        } else {
                            uploadedFilesElement.val(uploadedFilesElement.val() + value['serverFileName'] + ',');
                        }
                    });
                })
            }

            for (let j = 0; j < fileList.length; j++) {
                if (fileList[j].fileName && fileList[j].fileName === file.name) {
                    fileList.splice(j, 1);
                    break;
                }
            }

            // console.log(fileList);
            let _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        },
        init: function () {
            dropzoneForm = this;
            // $('#dropzone-wordpress-form').addClass('dropzone');
        },
    };

    let fullAddress = $("#autofill_address");
    $('<p id="message"></p>').insertAfter("#autofill_address");
    let $message = $("#message");

    function join(arr /*, separator */) {
        // console.log(arguments);
        let separator = arguments.length > 1 ? arguments[1] : ", ";
        return arr.filter(function (n) {
            return n
        }).join(separator);
    }

    function showPostalCode(address) {
        $("#billing_postcode").val(address.postal_code);
    }

    function showRegion(address) {
        $("#billing_state").val(address.region_with_type);
    }

    function showCity(address) {
        $("#billing_city").val(join([
            join([address.city_type, address.city], " "),
            join([address.settlement_type, address.settlement], " ")
        ]));
    }

    function showStreet(address) {
        $("#billing_address_1").val(join([
            join([address.street_type, address.street], " "),
            join([address.house_type, address.house], " "),
            join([address.block_type, address.block], " "),
            join([address.flat_type, address.flat], " ")
        ]));
    }

    function showFias(address) {
        // console.log(address.fias_id);
        $("#fias_field input").val(address.city_fias_id);
    }

    function showSelected(suggestion) {
        // console.log(suggestion);
        let address = suggestion.data;

        if (address.house) {
            $message.html('<span style="color: limegreen;font-weight: bold">Отлично, теперь можно продолжить оформление!</span>');
        } else {
            fullAddress.val('').change();
            $message.html('<span style="color: red;font-weight: bold">Укажите адрес, включая номер дома, чтобы продолжить</span>');
            return;
        }

        // console.log(address);
        showPostalCode(address);
        showRegion(address);
        showCity(address);
        showStreet(address);
        showFias(address);
        $("#billing_state").change();
        // $( document.body ).trigger( 'update_checkout' );
    }

    fullAddress.suggestions({
        token: "94efab2e13b37cf6fe0d782a4c3f685ca2bf7627",
        type: "ADDRESS",
        onSelect: showSelected,
        onSelectNothing: function () {
            fullAddress.val('').change();
            $message.html('<span style="color: red;font-weight: bold">Адрес не выбран из списка</span>');
        }
    });

    $( "input#activation_date" ).flatpickr({
        "disable": [
            function(date) {
                return (date.getDay() === 0 || date.getDay() === 6);
            }
        ],
        dateFormat: 'd.m.Y',
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                longhand: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
            },
            months: {
                shorthand: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
                longhand: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            },
        },
    });
});