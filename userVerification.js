window.onload = function () {
    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'userVerification.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

    xhr.onload = function () {
        if (this.status == 200) {
            if (this.responseText == false) {
                window.location.href = "login.html";
            }
        }
    }
    xhr.send();
}