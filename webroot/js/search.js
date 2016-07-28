
    // При загрузке страницы вызывается эта функция
    window.onload = function() {
    var select = $("#result");
    // Событие, когда мы отпустили клавишу, вызыв. функция
    $("#search_param").keyup(function () {
    $.ajax({
    url: "http://mymvc.local/news/search/" + $(this).val(),
    method: 'get'
}).done(function(data) {
    console.log(data);
    data = JSON.parse(data);
    select.html("");
    for (key in data) {
    select.append(
    $('<option value="' + data[key].value + '">' + data[key].value + '</option>')
    )
}
});
});
}

<script src="jquery-3.1.0.min.js"></script>