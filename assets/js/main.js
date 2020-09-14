$(document).ready(function () {

    /**
     * Creates a new root with helping the technology ajax
     */
    $('#createRoot').on('submit', function (event) {
        event.preventDefault();
        const form = $(this);
        sendAjaxPostRequest(form, '#tree');
    });


});
/*====================================
* functions
*===================================== */

/**
 * Sends an ajax request on a server
 * @param element is the object of this element
 * @param id is id of this element
 */
function sendAjaxPostRequest(element, id) {
    $.post(
        element.attr('action'),
        element.serialize(),
        function (data) {
            $(id).html(data);
        }
    );
}

/**
 * Adds a new root with helping the technology ajax
 * @param element
 * @param parentId
 */
function addRoot(element, parentId) {
    $.post(
        '/add',
        {
            parent_id: parentId,
            title: 'Root: '
        },
        function (data) {
            $('#tree').html(data);
        }
    );
}

/**
 * Remove a root and its children with helping the technology ajax
 * @param element
 * @param rootId
 */
function removeRoot(element, rootId) {
    const modal = $('#deleteModal');
    setTimeout(function () {
        let span = modal.find('span.text-danger');
        let count = span.text();
        let timerId = setInterval(function () {
            count--;
            if (count <= 0) {
                clearInterval(timerId);
                count = 30;
                removeAjax(element, rootId);
                modal.modal('hide');
            } else if (modal.attr('aria-hidden')) {
                clearInterval(timerId);
                count = 30;
            }

            span.html(count);
        }, 1000);
        $('#sendConfirmation').on('click', function () {
            removeAjax(element, rootId);
        });
    }, 500);
}

/**
 * Sends an ajax request for removing this roots
 * @param element
 * @param id
 */
function removeAjax(element, id) {
    $.post(
        '/delete',
        {
            id: id,
        },
        function (data) {
            $('#tree').html(data);
        }
    );
}





