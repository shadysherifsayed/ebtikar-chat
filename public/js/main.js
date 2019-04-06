$(document).on('click', '#user-search', function () {

    let $this = $(this);

    let term = $(`#${$this.attr('data-input')}`).val();

    showSearchResults(term);

});


$(document).on('click', '#clear-user-search', function () {

    let $this = $(this);

    $(`#${$this.attr('data-input')}`).val(null);

    $('.user-conversations').show();

    $('.user-search').hide();
});


$(document).on('keydown', '#user-search-input', function (e) {

    let code = e.keyCode || e.which;

    if (code == 13) {
        showSearchResults($(this).val());
    }


});

const showSearchResults = term => {

    axios.post('/search/user', {
            term
        })
        .then(response => {
            let users = response.data;
            $('.user-conversations').hide();
            let $userSearch = $('.user-search');
            $userSearch.empty();
            if (users.length) {
                users.forEach(user => {
                    $userSearch.append(
                        `
                        <a class="conversation shadow-sm my-2 d-block" href="/users/${user.id}">
                            ${user.name}
                        </a>`
                    )
                });
            } else {
                $userSearch.html('<h3> There are no users found </h3>')
            }

            $userSearch.show();
        });
}


$(document).on('submit', '.new-message form', function(e) {

    e.preventDefault();

    let $this = $(this);

    let $input = $this.find('input[name="message"]');

    let message = $input.val();

    axios.post($this.attr('action'), {
        message
    }).then(response => {
        let message = response.data.message;
        $('.conversation-messages').prepend(
            `
            <div class="shadow-sm my-2 p-2 message">
                <h3 class="m-0"> ${message.body} </h3>
                <div>
                    <small> ${message.sender.name} (Just now) </small>
                </div>
            </div>
            `
        );
        $input.val(null);
    })
});
