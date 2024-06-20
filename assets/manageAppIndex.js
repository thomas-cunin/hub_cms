const menus = document.querySelectorAll('.edit-menu-btn');

menus.forEach(menu => {
    menu.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                document.getElementById('form-container').innerHTML = html;
                bindFormSubmit();
            });
    });

} );

document.getElementById('add-menu-btn').addEventListener('click', function() {
    const url = this.getAttribute('data-url');
    console.log(url);
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
.then(response => response.text())
        .then(html => {
            document.getElementById('form-container').innerHTML = html;
            bindFormSubmit();
        });
});

function bindFormSubmit() {
    const form = document.querySelector('#form-container form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('form-container').innerHTML = '';
                } else {
                    document.getElementById('form-container').innerHTML = data.form;
                    bindFormSubmit();
                }
            });
    });
}