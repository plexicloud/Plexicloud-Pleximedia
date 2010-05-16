function checkvideo() {
    var category_id = document.getElementById("category_id").value;
    if (category_id == "") {
        alert("Please select a category");
        document.getElementById("category_id").focus();
        return false;
    }
    return true;
}