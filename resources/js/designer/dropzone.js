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
    '<span class="text-slate-400">xlsx, pdf, step, stp, dwg, dxf</span>' +
    '</div>';

let dropzone = new Dropzone('#file-upload', {
    acceptedFiles: ".xlsx,.pdf,.step,.stp,.dwg,.dxf",
    previewTemplate: defaultPreviewTemplate,
    dictDefaultMessage: initialMessage,
    headers: { "submission_code": submission_code },
});

let allowedFiles = ['xlsx', 'pdf', 'step', 'stp', 'dwg', 'dxf'];

/*
|--------------------------------------------------------------------------
| WHEN FILE IS ADDED
|--------------------------------------------------------------------------
*/
dropzone.on('addedfile', file => {
    clearSubmissionFeedback();
    let fileType = getFileType(file);
    if (!allowedFiles.includes(fileType)) {
        removeFile(file);
    } else {
        createThumbnail(fileType);
        removeDuplicates();
        removeDuplicateExcels();
    }
    writeSubmissionFeedback();
});

/*
|--------------------------------------------------------------------------
| WHEN FILE IS DELETED
|--------------------------------------------------------------------------
*/
dropzone.on('removedfile', file => {
    clearSubmissionFeedback();
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
    writeSubmissionFeedback();
});

/*
|--------------------------------------------------------------------------
| GENERAL FUNCTIONS
|--------------------------------------------------------------------------
*/
function removeFile(file) {
    $(".uploaded-file-name").each(function () {
        if ($(this).text() == file.name) {
            $(this).parent().parent().remove();
            outputError("File type not allowed");
        }
    });
}
function getFileType(file) {
    return file.name.split('.').slice(-1)[0];
}
function createThumbnail(fileType) {
    try {
        let uploads = document.getElementsByClassName('uploaded-file-row');
        let lastUpload = uploads[uploads.length - 1];
        let image = lastUpload.childNodes[0].childNodes[0].childNodes[0];

        if (fileType == 'xlsx') {
            image.setAttribute('src', excel_img);
        } else if (fileType == 'pdf') {
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
function clearSubmissionFeedback() {
    $('#submission-feedback').empty();
    $('#submission-feedback').append(
        "<hr class='my-4'><h3 class='flex items-center gap-2'>Checking <img src='"+dots_loading+"'/></h3>"
    );
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

        for (let i=0;i<response.lines.length;i++) {
            let line = response.lines[i];

            // get color
            let color = line.type == 'error' ? 'text-red-600' : 'text-green-600';

            // check if line has green tick
            let tick = (line.tick != undefined && line.tick) == "true"
            ? "<img class='aspect-square w-7' src='"+green_tick+"'/>"
            : "";

            // output line text
            $('#submission-feedback').append(
                "<hr class='my-4'><h3 class='flex items-center gap-2 "+color+"'>"+line.text+tick+"</h3>"
            );

            // output list if there is one
            if (line.list != undefined || line.list != null) {
                $('#submission-feedback').append("<ul>");
                line.list.forEach(element => {
                    if (line.list_files != undefined && line.list_files == "true") {
                        // output a checked list if it is files
                        let checked = (element.checked != undefined && element.checked) == "true"
                        ? "<img class='aspect-square w-5' src='"+green_tick+"'/>"
                        : "";
                        $('#submission-feedback').append(
                            "<li class='flex items-center gap-2 "+element.color+"'>"+element.text+checked+"</li>"
                        );
                    } else {
                        if (line.list_case != undefined && line.list_case == "upper") {
                            element = element.toUpperCase();
                        }
                        $('#submission-feedback').append(
                            "<li class='"+color+"'>"+element+"</li>"
                        );
                    }
                });
                $('#submission-feedback').append("</ul>");
            }

        }
        
        $('#submission-feedback').parent().parent().removeClass('hidden');
    });
}
