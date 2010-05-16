function checkvideo() {
    var category_id = document.getElementById("category_id").value;
    if (category_id == "") {
        alert("Please select a category");
        document.getElementById("category_id").focus();
        return false;
    }
    this.disabled=true;
}

function checkVideoUpload() {
    if (document.getElementById("file").value == "") {
        /*var file = document.getElementById("file").value;
        if (!movie_LimitAttachVideo(file)) {
            return false;
        }*/
        alert("Please select a video file");
        document.getElementById("file").focus();
        return false;
    }

    var category_id = document.getElementById("category_id").value;
    if (category_id == "") {
        alert("Please select a category");
        document.getElementById("category_id").focus();
        return false;
    }

    var video_title = document.getElementById("video_title").value;
    if (video_title == "") {
        alert("Please input video title");
        document.getElementById("video_title").focus();
        return false;
    }

    return true;
}

function movie_LimitAttachVideo(field)
{
    file = field.value;
    var allowSubmit = false;
    var extArray = new Array(".mpg", ".mpeg", ".avi", ".divx", ".mp4", ".flv", ".wmv", ".rm", ".mov", ".moov", ".asf", ".swf", ".vob");
    while (file.indexOf("\\") != -1) file = file.slice(file.indexOf("\\") + 1);
    ext = file.slice(file.lastIndexOf(".")).toLowerCase();
    for (var i = 0; i < extArray.length; i++)
    {
        if (extArray[i] == ext)
        {
            allowSubmit = true;
            break;
        }
    }
    if (!allowSubmit)
    {
        alert("You must upload movie with format contain: " + (extArray.join("  ")) + "\nPlease choose other file!");
        field.value='';
    }

    return allowSubmit;
}

function movie_LimitAttachVideoNonFFmpeg(field)
{
    file = field.value;
    var allowSubmit = false;
    var extArray = new Array(".flv");
    while (file.indexOf("\\") != -1) file = file.slice(file.indexOf("\\") + 1);
    ext = file.slice(file.lastIndexOf(".")).toLowerCase();
    for (var i = 0; i < extArray.length; i++)
    {
        if (extArray[i] == ext)
        {
            allowSubmit = true;
            break;
        }
    }
    if (!allowSubmit)
    {
        alert("You must upload movie with format contain: " + (extArray.join("  ")) + "\nPlease choose other file!");
        field.value='';
    }

    return allowSubmit;
}

function thumbnail_LimitAttachVideo(field)
{
    file = field.value;
    var allowSubmit = false;
    var extArray = new Array(".gif", ".jpg", ".png");
    while (file.indexOf("\\") != -1) file = file.slice(file.indexOf("\\") + 1);
    ext = file.slice(file.lastIndexOf(".")).toLowerCase();
    for (var i = 0; i < extArray.length; i++)
    {
        if (extArray[i] == ext)
        {
            allowSubmit = true;
            break;
        }
    }
    if (!allowSubmit)
    {
        alert("You must upload thumbnail with format contain: " + (extArray.join("  ")) + "\nPlease choose other file!");
        field.value='';
    }

    return allowSubmit;
}