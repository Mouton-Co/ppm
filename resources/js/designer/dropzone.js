/*
|--------------------------------------------------------------------------
| INITIALIZE DROPZONE
|--------------------------------------------------------------------------
*/
Dropzone.autoDiscover = false;
let defaultPreviewTemplate = '' +
    '<div class="uploaded-file-row">' +
    '<div class="flex items-center mb-1">' +
    '<span class="w-2/12 flex justify-center"><img class="h-8 min-w-[2rem] mr-5 md:mr-0" src=""></span>' +
    '<span class="uploaded-file-name w-8/12 md:w-7/12 !mb-0 label-dark truncate" data-dz-name></span>' +
    '<span class="hidden md:flex text-sm justify-center md:gap-1 w-2/12 label-black !mb-0" data-dz-size></span>' +
    '<span class="w-[51px] flex justify-center !cursor-pointer hover:opacity-70">' +
    '<svg data-dz-remove class="h-5 w-5 !cursor-pointer" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>' +
    '</span>' +
    '</div>' +
    '<hr>' +
    '</div>';
let initialMessage = '' +
    '<div class="flex items-center gap-2">' +
    '<svg class="text-gray-400 w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <path d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>' +
    '<span class="text-gray-600">Drop files to upload, or <a class="underline">browse</a></span>' +
    '</div>' +
    '<div class="">' +
    '<span class="text-gray-400">xlsx, pdf, step, stp, dwg, dxf</span>' +
    '</div>';

let dropzone = new Dropzone('#file-upload', {
    acceptedFiles: ".xlsx,.pdf,.step,.stp,.dwg,.dxf",
    previewTemplate: defaultPreviewTemplate,
    dictDefaultMessage: initialMessage,
});

let allowedFiles = ['xlsx', 'pdf', 'step', 'stp', 'dwg', 'dxf'];

let dots_loading = '<svg class="w-5" id="dots" width="132px" height="58px" viewBox="0 0 132 58" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><title>dots</title> <desc>Created with Sketch.</desc> <defs></defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="dots" sketch:type="MSArtboardGroup" fill="#A3A3A3"> <circle id="dot1" sketch:type="MSShapeGroup" cx="25" cy="30" r="13"></circle> <circle id="dot2" sketch:type="MSShapeGroup" cx="65" cy="30" r="13"></circle> <circle id="dot3" sketch:type="MSShapeGroup" cx="105" cy="30" r="13"></circle> </g> </g> </svg>';

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
| REFRESH BUTTON
|--------------------------------------------------------------------------
*/
$('#refresh-feedback').on('click', function() {
    clearSubmissionFeedback();
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
        } else if (fileType == 'dwg' || fileType == 'dxf') {
            image.setAttribute('src', dwg_img);
        } else if (fileType == 'step' || fileType == 'stp') {
            image.setAttribute('src', step_img);
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
        "<hr class='my-2'><h3 class='flex items-center gap-2'>Checking"+dots_loading+"</h3>"
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

        for (let line of response.lines) {

            // get color
            let color = line.type == 'error' ? 'text-white' : 'text-gray-400';

            // check if line has green tick
            let tick = (line.tick != undefined && line.tick) == "true"
            ? '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path></svg>'
            : "";

            // output line text
            $('#submission-feedback').append(
                "<hr class='my-2'><h3 class='flex items-center gap-2 "+color+"'>"+line.text+tick+"</h3>"
            );

            if (line.text.includes("Excel sheet found") && line.assembly_name != undefined) {
                $('#assembly_name').val(line.assembly_name);
            }

            if (line.text.includes('All required files found')) {
                $('#submission-feedback').append(
                    "<hr class='my-2'>"
                );
            }

            // output list if there is one
            if (line.list != undefined || line.list != null) {
                $('#submission-feedback').append("<ul>");
                line.list.forEach(element => {
                    if (line.list_files != undefined && line.list_files == "true") {
                        // output a checked list if it is files
                        let checked = (element.checked != undefined && element.checked) == "true"
                        ? 'line-through'
                        : "";
                        $('#submission-feedback').append(
                            "<li class='text-sm flex items-center gap-2 "+element.color+" "+checked+"'>"+element.text+"</li>"
                        );
                    } else {
                        if (line.list_case != undefined && line.list_case == "upper") {
                            element = element.toUpperCase();
                        }
                        $('#submission-feedback').append(
                            "<li class='"+color+" text-sm'>"+element+"</li>"
                        );
                    }
                });
                $('#submission-feedback').append("</ul>");
            }

            // check if button can be enabled
            if (line.show_button != undefined && line.show_button == "true") {
                $('#submit-button').removeClass('btn-disabled');
                $('#submit-button').addClass('btn-sky');
                $('#submit-button').removeAttr('disabled');
            } else {
                $('#submit-button').removeClass('btn-sky');
                $('#submit-button').addClass('btn-disabled');
                $('#submit-button').attr('disabled', '');
            }

        }
        
        $('#submission-feedback').removeClass('hidden');
        $('#submission-line').removeClass('hidden');
    });
}
