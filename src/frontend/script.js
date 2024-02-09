console.log(123);

$(document).ready(function () {
    $.ajax({
        type: 'post',
        url: '../backend/api.php',
        success: function (response) {
            console.log(response);
            for (const [key, value] of Object.entries(JSON.parse(response))) {
                $("#categories-list").append(`<li class="list-group-item">${value}</li>`)
            }
        }
    });
});