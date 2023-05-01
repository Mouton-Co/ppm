/*
|--------------------------------------------------------------------------
| INITIALIZE DROPZONE
|--------------------------------------------------------------------------
*/
Dropzone.autoDiscover = false;
let defaultPreviewTemplate = '' +
    '<div class="uploaded-file-row">' +
    '<div class="flex items-center mb-1">' +
    '<span class="w-1/12 flex justify-center"><img class="h-8" src=""></span>' +
    '<span class="uploaded-file-name w-6/12" data-dz-name></span>' +
    '<span class="w-1/12" data-dz-size></span>' +
    '<div class="w-3/12 h-4 rounded-full progress-container">' +
    '<div data-dz-uploadprogress class="pro progress h-4 bg-green-500 rounded-full"></div>' +
    '</div>' +
    '<span class="w-1/12 flex justify-center cursor-pointer-important hover:opacity-70">' +
    '<img id="remove-file" data-dz-remove class="h-5 cursor-pointer-important" src="' + delete_img + '">' +
    '</span>' +
    '</div>' +
    '<hr>' +
    '</div>';
let initialMessage = '' +
    '<div class="flex items-center gap-2">' +
    '<img class="h-5" src="' + upload_img + '">' +
    '<span class="text-slate-600">Drop files to upload, or <a class="underline">browse</a></span>' +
    '</div>' +
    '<div class="">' +
    '<span class="text-slate-400">xlsx, xls, pdf</span>' +
    '</div>';

let dropzone = new Dropzone('#file-upload', {
    acceptedFiles: ".xlsx,.pdf",
    previewTemplate: defaultPreviewTemplate,
    dictDefaultMessage: initialMessage,
    headers: { "submission_code": submission_code },
});

/*
|--------------------------------------------------------------------------
| WHEN FILE IS ADDED
|--------------------------------------------------------------------------
*/
dropzone.on('addedfile', file => {
    createThumbnail(file);
    removeDuplicates();
    removeDuplicateExcels();
    writeSubmissionFeedback();
});

/*
|--------------------------------------------------------------------------
| WHEN FILE IS DELETED
|--------------------------------------------------------------------------
*/
dropzone.on('removedfile', file => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "remove",
        data: {
            file: JSON.stringify(file),
            submission_code: submission_code,
        },
    }).done(function (msg) {});
});

/*
|--------------------------------------------------------------------------
| GENERAL FUNCTIONS
|--------------------------------------------------------------------------
*/
function createThumbnail(file) {
    try {
        let uploads = document.getElementsByClassName('uploaded-file-row');
        let lastUpload = uploads[uploads.length - 1];
        let image = lastUpload.childNodes[0].childNodes[0].childNodes[0];

        if (file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            image.setAttribute('src', excel_img);
        } else if (file.type === "application/pdf") {
            image.setAttribute('src', pdf_img);
        }
    } catch (error) { }
}
function removeDuplicates() {
    let uniqueNames = [];
    $(".uploaded-file-name").each(function (index) {
        let fileName = $(this).text();
        if (uniqueNames.includes(fileName)) {
            $(this).parent().parent().remove();
            outputError("File has already been uploaded");
        } else {
            uniqueNames.push(fileName);
        }
    });
}
function removeDuplicateExcels() {
    let hasExcel = false;
    $(".uploaded-file-name").each(function (index) {
        let fileName = $(this).text();
        if(fileName.includes(".xlsx")) {
            if (hasExcel) {
                $(this).parent().parent().remove();
                outputError("Only one excel sheet is allowed");
            } else {
                hasExcel = true;
            }
        }
    });
}
function outputError(error) {
    let errorMsg = $("#error-message");
    errorMsg.text(error);
    errorMsg.parent().parent().removeClass('hidden');
}
function writeSubmissionFeedback() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "feedback",
        data: {
            submission_code: submission_code,
        },
    }).done(function (response) {
        $('#submission-feedback').empty();

        if (response.heading != undefined || response.heading != null) {
            $('#submission-feedback').append(
                "<h3 class='mb-4 text-gray-700'>"+response.heading+"</h3>"
            );
        }

        let color = "";
        if (response.type != undefined || response.type != null) {
            if (response.type == 'error') {
                color = "text-red-600";
            } else if (response.type == 'success') {
                color = "text-green-600";
            }
        }

        if (response.message != undefined || response.message != null) {
            $('#submission-feedback').append(
                "<hr><h3 class='mt-4 text-red-600'>"+response.message+"</h3>"
            );
        }

        if (response.output != undefined || response.output != null) {
            $('#submission-feedback').append("<ul>");
            response.output.forEach(element => {
                $('#submission-feedback').append(
                    "<li class='text-red-600'>"+element+"</li>"
                );
            });
            $('#submission-feedback').append("</ul>");
        }
        
        $('#submission-feedback').parent().parent().removeClass('hidden');
    });
}
