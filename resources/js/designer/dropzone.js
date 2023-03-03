Dropzone.autoDiscover = false;
let defaultPreviewTemplate = "<div></div>"
let dropzone = new Dropzone('#file-upload',{
    acceptedFiles:".xlsx,.xls,.pdf",
    addRemoveLinks: true,
    autoProcessQueue: true,
    previewTemplate: defaultPreviewTemplate,
})
